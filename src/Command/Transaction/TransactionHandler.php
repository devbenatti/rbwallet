<?php

namespace App\Command\Transaction;

use App\Command\CommandHandler;
use App\Command\CommandHandlerCapabilities;
use App\Driven\Database\DAO\TransactionDAO;
use App\Driven\Http\TransactionAuthorizer;
use App\Driven\Uuid\UuidGenerator;
use App\Model\VO\TransactionStatus;
use App\Model\VO\Uuid;
use App\Model\Wallet\Flow;
use App\Model\Wallet\WalletRepository;
use Exception;

final class TransactionHandler implements CommandHandler
{

    use CommandHandlerCapabilities;
    
    /**
     * @var WalletRepository
     */
    private WalletRepository $walletRepository;
    
    /**
     * @var TransactionDAO
     */
    private TransactionDAO $transactionDAO;
    
    /**
     * @var UuidGenerator
     */
    private UuidGenerator $uuidAdapter;

    /**
     * @var Uuid 
     */
    private Uuid $code;
    
    /**
     * @var TransactionAuthorizer
     */
    private TransactionAuthorizer $authorizer;

    /**
     * TransactionHandler constructor.
     * @param WalletRepository $walletRepository
     * @param TransactionDAO $transactionDAO
     * @param UuidGenerator $uuidAdapter
     * @param TransactionAuthorizer $authorizer
     */
    public function __construct(
        WalletRepository $walletRepository,
        TransactionDAO $transactionDAO,
        UuidGenerator $uuidAdapter,
        TransactionAuthorizer $authorizer
    ) {
        $this->walletRepository = $walletRepository;
        $this->transactionDAO = $transactionDAO;
        $this->uuidAdapter = $uuidAdapter;
        $this->authorizer = $authorizer;
    }

    /**
     * @param Transaction $command
     * @throws Exception
     */
    public function __invoke(Transaction $command): void
    {
        $this->code = $this->uuidAdapter->generate();
        
        $transaction = \App\Model\Wallet\Transaction::build([
            'code' => $this->code,
            'amount' => $command->getAmount(),
            'payerId' => $command->getPayerId(),
            'payeeId' => $command->getPayeeId()
        ]);
        
        try {
            
            $this->transactionDAO->create($transaction);
            
            $this->authorizer->authorize();
            
            $this->transactionDAO->getDatabase()->beginTransaction();
            
            $outFlow = Flow::buildCashOutflow($transaction);
            $payerWallet = $this->walletRepository->findByPerson($command->getPayerId());
            $payerWallet->updateBalance($outFlow);
            $this->walletRepository->updateBalance($payerWallet);

            $inFlow = Flow::buildCashInflow($transaction);
            $payeeWallet = $this->walletRepository->findByPerson($command->getPayeeId());
            $payeeWallet->updateBalance($inFlow);
            $this->walletRepository->updateBalance($payeeWallet);
            
            $this->transactionDAO->getDatabase()->commit();
            
            $this->transactionDAO->updateStatus($transaction->getCode(), TransactionStatus::COMPLETED);
            
        } catch (Exception $exception) {
            $this->transactionDAO->getDatabase()->rollBack();
            $this->transactionDAO->updateStatus($this->code, TransactionStatus::NOT_COMPLETED);
        }
    }
}

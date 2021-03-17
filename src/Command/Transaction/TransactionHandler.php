<?php

namespace App\Command\Transaction;

use App\Command\CommandHandler;
use App\Command\CommandHandlerCapabilities;
use App\Driven\Database\DAO\TransactionDAO;
use App\Driven\Http\TransactionAuthorizer;
use App\Driven\Http\TransactionUnauthorizedException;
use App\Model\Exception\InsufficientFundsException;
use App\Model\VO\FailReason;
use App\Model\VO\TransactionStatus;
use App\Model\VO\Uuid;
use App\Model\Flow;
use App\Model\WalletRepository;
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
     * @param TransactionAuthorizer $authorizer
     */
    public function __construct(
        WalletRepository $walletRepository,
        TransactionDAO $transactionDAO,
        TransactionAuthorizer $authorizer
    ) {
        $this->walletRepository = $walletRepository;
        $this->transactionDAO = $transactionDAO;
        $this->authorizer = $authorizer;
    }

    /**
     * @param Transaction $command
     * @throws Exception
     */
    public function __invoke(Transaction $command): void
    {
        $this->code = new Uuid($command->getCode());
        
        $transaction = \App\Model\Transaction::build([
            'code' => $command->getCode(),
            'amount' => $command->getAmount(),
            'payerId' => $command->getPayerId(),
            'payeeId' => $command->getPayeeId()
        ]);
        
        $this->transactionDAO->create($transaction);
        
        try {
            $this->transactionDAO->getDatabase()->beginTransaction();
            
            $outFlow = Flow::buildCashOutflow($transaction);
            $payerWallet = $this->walletRepository->findByPerson($command->getPayerId());
            $payerWallet->updateBalance($outFlow);
            $this->walletRepository->updateBalance($payerWallet);
            
            $this->authorizer->authorize();
            
            $inFlow = Flow::buildCashInflow($transaction);
            $payeeWallet = $this->walletRepository->findByPerson($command->getPayeeId());
            $payeeWallet->updateBalance($inFlow);
            $this->walletRepository->updateBalance($payeeWallet);
            
            $this->transactionDAO->getDatabase()->commit();
            
            $this->transactionDAO->updateStatus($transaction->getCode(), TransactionStatus::SUCCESS);
            
        } catch (InsufficientFundsException $exception) {
            $this->transactionDAO->getDatabase()->rollBack();

            $this->transactionDAO->updateStatus(
                $this->code,
                TransactionStatus::FAILED,
                FailReason::INSUFFICIENT_FUNDS
            );
        } catch (TransactionUnauthorizedException $exception) {
            $this->transactionDAO->getDatabase()->rollBack();
            $this->transactionDAO->updateStatus(
                $this->code,
                TransactionStatus::FAILED,
                FailReason::UNAUTHORIZED
            );
        } catch (Exception $exception) {
            $this->transactionDAO->getDatabase()->rollBack();

            $this->transactionDAO->updateStatus(
                $this->code,
                TransactionStatus::FAILED,
                FailReason::UNKNOWN
            );
            
            throw $exception;
        }
    }
}

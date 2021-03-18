<?php

namespace App\Command\Transaction;

use App\Command\CommandHandler;
use App\Command\CommandHandlerCapabilities;
use App\Driven\Database\DAO\NotificationRetryDAO;
use App\Driven\Database\DAO\TransactionDAO;
use App\Driven\Database\DAO\WalletDAO;
use App\Driven\Http\NotifierUnavailableException;
use App\Driven\Http\TransactionAuthorizer;
use App\Driven\Http\TransactionNotifier;
use App\Driven\Http\TransactionUnauthorizedException;
use App\Model\Exception\InsufficientFundsException;
use App\Model\VO\FailReason;
use App\Model\VO\TransactionStatus;
use App\Model\VO\Uuid;
use App\Model\Flow;
use Exception;

final class TransactionHandler implements CommandHandler
{

    use CommandHandlerCapabilities;
    
    /**
     * @var WalletDAO
     */
    private WalletDAO $walletDAO;
    
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
     * @var TransactionNotifier
     */
    private TransactionNotifier $notifier;
    
    /**
     * @var NotificationRetryDAO
     */
    private NotificationRetryDAO $notificationRetryDAO;

    /**
     * TransactionHandler constructor.
     * @param WalletDAO $walletDAO
     * @param TransactionDAO $transactionDAO
     * @param TransactionAuthorizer $authorizer
     * @param TransactionNotifier $notifier
     * @param NotificationRetryDAO $notificationRetryDAO
     */
    public function __construct(
        WalletDAO $walletDAO,
        TransactionDAO $transactionDAO,
        TransactionAuthorizer $authorizer,
        TransactionNotifier $notifier,
        NotificationRetryDAO $notificationRetryDAO
    ) {
        $this->walletDAO = $walletDAO;
        $this->transactionDAO = $transactionDAO;
        $this->authorizer = $authorizer;
        $this->notifier = $notifier;
        $this->notificationRetryDAO = $notificationRetryDAO;
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
            $this->walletDAO->beginTransaction();
            
            $outFlow = Flow::buildCashOutflow($transaction);
            $payerWallet = $this->walletDAO->findByPerson($command->getPayerId());
            $payerWallet->updateBalance($outFlow);
            $this->walletDAO->updateBalance($payerWallet);
            
            $this->authorizer->authorize($transaction);
            
            $inFlow = Flow::buildCashInflow($transaction);
            $payeeWallet = $this->walletDAO->findByPerson($command->getPayeeId());
            $payeeWallet->updateBalance($inFlow);
            $this->walletDAO->updateBalance($payeeWallet);
            
            $this->walletDAO->commit();
            
            $this->transactionDAO->updateStatus($transaction->getCode(), TransactionStatus::SUCCESS);
        
            $this->notifier->notify($transaction);
            
        } catch (InsufficientFundsException $exception) {
            $this->transactionDAO->rollBack();

            $this->transactionDAO->updateStatus(
                $this->code,
                TransactionStatus::FAILED,
                FailReason::INSUFFICIENT_FUNDS
            );
        } catch (TransactionUnauthorizedException $exception) {
            $this->transactionDAO->rollBack();
            
            $this->transactionDAO->updateStatus(
                $this->code,
                TransactionStatus::FAILED,
                FailReason::UNAUTHORIZED
            );
        } catch (NotifierUnavailableException $exception) {
            $this->notificationRetryDAO->create($this->code);
        } catch (Exception $exception) {
            $this->transactionDAO->rollBack();
            
            $this->transactionDAO->updateStatus(
                $this->code,
                TransactionStatus::FAILED,
                FailReason::UNKNOWN
            );
        }
    }
}

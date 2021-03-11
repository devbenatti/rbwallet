<?php

namespace App\Command\Transaction;

final class Transaction
{
    public function __construct()
    {
        $step1 = 'busca carteira do pagador';
        $step2 = 'verifica se tem saldo';
        $step3 = 'cria transação debito';
        $step4 = 'consultar autorizador';
        $step4 = 'debita valor da transação payer';
        $step5 = 'credita o valor da transação para o payee';
        $step6 = 'atualiza transação para completed';
        $step7 = 'notifica o recebedor';
    }
}

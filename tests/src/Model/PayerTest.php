<?php

namespace Tests\Model;

use App\Model\Person\Payer;
use App\Model\VO\DocumentType;
use PHPUnit\Framework\TestCase;

class PayerTest extends TestCase
{

    public function testSuccess()
    {
        $payerData = $this->getPayerData();
        
        $payer = Payer::build($payerData);
        
        static::assertEquals($payerData, $payer->toArray());
    }

    public function testPayerMerchantShouldReturnTrue()
    {
        $payerData = $this->getPayerData([
            'document' => [
                'type' => DocumentType::CNPJ,
                'identifier' => '42145831000100'
            ]
        ]);

        $payer = Payer::build($payerData);
        
        static::assertTrue($payer->isMerchant());
    }
    
    /**
     * @param array $data
     * @return array
     */
    private function getPayerData(array $data = [])
    {
        return array_filter(
            array_merge([
                'id' => '5f63d951-5439-444b-9a05-e29d80b85da5',
                'document' => [
                    'type' => DocumentType::CPF,
                    'identifier' => '05719027540'
                ],
                'email' => 'xablau@gmail.com',
                'name' => 'Xablau testador'
            ], $data)
        );
    }
}

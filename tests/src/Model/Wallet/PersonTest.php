<?php

namespace Tests\Model\Wallet;

use App\Model\Wallet\Person;
use App\Model\VO\DocumentType;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{

    public function testSuccess()
    {
        $payerData = $this->getPayerData();
        
        $payer = Person::build($payerData);
        
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

        $payer = Person::build($payerData);
        
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
                'id' => 1,
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

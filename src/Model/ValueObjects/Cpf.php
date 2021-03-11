<?php

namespace App\Model\ValueObjects;

use InvalidArgumentException;

final class Cpf
{
    use ValueObject;

    /**
     * @var array
     */
    const INVALID_SEQUENCE = [
        '11111111111',
        '22222222222',
        '33333333333',
        '44444444444',
        '55555555555',
        '66666666666',
        '77777777777',
        '88888888888',
        '99999999999'
    ];

    /**
     * Cpf constructor.
     * @param $value
     */
    public function __construct($value)
    {
        if (empty($value) || !$this->isValid($value)) {
            throw new InvalidArgumentException(sprintf('Invalid value for %s', $value));
        }
        
        $this->value = $value;
    }

    /**
     * @param $value
     * @return bool
     */
    private function isValid($value): bool
    {
        if (!$this->isAcceptedType($value)) {
            return false;
        }
        
        $cpf = $this->sanitize($value);

        if (strlen($cpf) != 11) {
            return false;
        }
        
        if (in_array($cpf, self::INVALID_SEQUENCE)) {
            return false;
        }

        for ($aux = 9; $aux < 11; $aux++) {
            for ($dec = 0, $ct = 0; $ct < $aux; $ct++) {
                $dec += $cpf[$ct] * (($aux + 1) - $ct);
            }
            $dec = ((10 * $dec) % 11) % 10;
            
            if ($cpf[$ct] != $dec) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * @param $value
     * @return bool
     */
    private function isAcceptedType($value)
    {
        if (!is_int($value) && !is_string($value)) {
            return false;
        }
        
        return true;
    }

    /**
     * @param $value
     * @return string
     */
    private function sanitize($value)
    {
        $sanitizedValue = preg_replace("/[^0-9]/", "", $value);
        return str_pad($sanitizedValue, 11, '0', STR_PAD_LEFT);
    }
}

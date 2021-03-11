<?php

namespace App\Model\Person;

final class Payer implements Person
{
    use PersonCapabilities;
    
    /**
     * @return bool
     */
    public function isMerchant(): bool
    {
        return !$this->document->isCPF();
    }
}

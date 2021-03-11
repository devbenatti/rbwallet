<?php

namespace App\Model\ValueObjects;

final class DocumentType
{
    use EnumCapabilities;

    /**
     * @var string
     */
    const CPF = 'CPF';

    /**
     * @var string
     */
    const CNPJ = 'CNPJ';
}

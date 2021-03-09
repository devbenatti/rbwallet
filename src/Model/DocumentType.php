<?php

namespace App\Model;

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

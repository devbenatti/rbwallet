<?php

namespace App\Command;

trait CommandHandlerCapabilities
{
    public function handle(Command $command): void
    {
        $this->__invoke($command);
    }
}

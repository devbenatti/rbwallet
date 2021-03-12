<?php

namespace App\Command;

interface CommandHandler
{
    /**
     * @param Command $command
     */
    public function handle(Command $command): void;

}

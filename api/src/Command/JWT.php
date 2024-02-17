<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
#[AsCommand(
    name: 'jwt',
    description: 'Run Symfony server',
)]
class JWT extends Command
{
    /**
     * @return void
     */
    protected function configure(): void
    {
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        exec('mkdir -p ./config/jwt');
        exec('openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096');
        exec('openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout');
        return Command::SUCCESS;
    }
}

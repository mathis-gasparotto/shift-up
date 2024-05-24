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
    name: 'serve',
    description: 'Run Symfony server',
)]
class Serve extends Command
{
    /**
     * @param string $apiIp
     * @param string $apiPort
     */
    public function __construct(
        private readonly string $apiIp,
        private readonly string $apiPort,
    )
    {
        parent::__construct();
    }

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
        exec('php -S ' . $this->apiIp .  ':' . $this->apiPort . ' -t public/');
        return Command::SUCCESS;
    }
}

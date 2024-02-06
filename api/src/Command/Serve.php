<?php

namespace App\Command;

use App\Entity\User;
use App\Helper\GlobalHelper;
use App\Helper\NotificationHelper;
use App\Repository\SiteRepository;
use App\Service\EmailService;
use App\Service\SquareImportUserService;
use App\Service\SquareupService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
        exec('php -S localhost:8000 -t public/');
        return Command::SUCCESS;
    }
}

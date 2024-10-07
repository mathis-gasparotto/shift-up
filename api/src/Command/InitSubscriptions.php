<?php

namespace App\Command;

use App\Entity\MediaObject;
use App\Entity\Subscription;
use App\Helper\MediaObjectHelper;
use App\Helper\SubscriptionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
#[AsCommand(
    name: 'init-subscriptions',
    description: 'Init subscriptions',
)]
class InitSubscriptions extends Command
{

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
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

        $subscriptions = [];
        foreach (SubscriptionHelper::SUBSCRIPTION_OBJECTS as $subscription) {
            $newSub = (new Subscription())
                ->setLabel($subscription['label'])
                ->setPrice($subscription['price'])
                ->setRecurrence($subscription['recurrence'])
                ->setDescription($subscription['description']);
            if (isset($subscription['stripeProductId'])) {
                $newSub->setStripeProductId($subscription['stripeProductId']);
            }
            if (isset($subscription['stripePriceId'])) {
                $newSub->setStripePriceId($subscription['stripePriceId']);
            }
            $subscriptions[] = $newSub;
        }

        foreach ($subscriptions as $subscription) {
            $this->entityManager->persist($subscription);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}

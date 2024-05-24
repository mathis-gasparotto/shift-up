<?php

namespace App\Command;

use App\Entity\MediaObject;
use App\Helper\MediaObjectHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
#[AsCommand(
    name: 'init-project-pictures',
    description: 'Init project pictures',
)]
class InitProjectPictures extends Command
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
        $mediaObject1 = (new MediaObject())->setCategory(MediaObjectHelper::PROJECT_CATEGORY)->setFilePath('project-illustration-1.jpg');
        $mediaObject2 = (new MediaObject())->setCategory(MediaObjectHelper::PROJECT_CATEGORY)->setFilePath('project-illustration-2.jpg');
        $mediaObject3 = (new MediaObject())->setCategory(MediaObjectHelper::PROJECT_CATEGORY)->setFilePath('project-illustration-3.jpg');
        $mediaObject4 = (new MediaObject())->setCategory(MediaObjectHelper::PROJECT_CATEGORY)->setFilePath('project-illustration-4.jpg');
        $mediaObject5 = (new MediaObject())->setCategory(MediaObjectHelper::PROJECT_CATEGORY)->setFilePath('project-illustration-5.jpg');
        $mediaObject6 = (new MediaObject())->setCategory(MediaObjectHelper::PROJECT_CATEGORY)->setFilePath('project-illustration-6.jpg');
        $mediaObject7 = (new MediaObject())->setCategory(MediaObjectHelper::PROJECT_CATEGORY)->setFilePath('project-illustration-7.jpg');
        $mediaObject8 = (new MediaObject())->setCategory(MediaObjectHelper::PROJECT_CATEGORY)->setFilePath('project-illustration-8.jpg');

        $this->entityManager->persist($mediaObject1);
        $this->entityManager->persist($mediaObject2);
        $this->entityManager->persist($mediaObject3);
        $this->entityManager->persist($mediaObject4);
        $this->entityManager->persist($mediaObject5);
        $this->entityManager->persist($mediaObject6);
        $this->entityManager->persist($mediaObject7);
        $this->entityManager->persist($mediaObject8);

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}

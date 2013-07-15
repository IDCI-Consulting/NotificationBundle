<?php

namespace IDCI\Bundle\NotificationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use IDCI\Bundle\NotificationBundle\Entity\NotificationEntity;

class NotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tms:notification:send')
            ->setDescription('Send notification from spool')
            ->setHelp(<<<EOT
                The <info>%command.name%</info> command send a notification to the selected source.
                You may add some options : width, height, mode(base64, file) and format (jpg, png, gif)
EOT
            )        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {                
            $notifications = new NotificationEntity();
            var_dump($notifications->getId());
    }
}


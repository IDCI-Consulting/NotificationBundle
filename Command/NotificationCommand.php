<?php

namespace IDCI\Bundle\NotificationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class NotificationCommand extends ContainerAwareCommand
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('tms:notification:send')
            ->setDescription('Send notification from spool')
            ->setHelp(<<<EOT
                The <info>%command.name%</info> command send all notifications.
EOT
            )
        ;
    }

    /**
     * Execute
     *
     * @param sInputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this
                ->getContainer()
                ->get('notification_manager')
                ->send()
            ;
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}


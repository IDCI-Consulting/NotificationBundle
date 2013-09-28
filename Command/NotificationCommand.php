<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

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
            ->setName('idci:notification:send')
            ->setDescription('Send notification from spool')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command sends all notifications and show in the console a detail of notifications sent.
Here is an example of usage of this command <info>php app/console tms:notification:send</info>
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
            $logs = $this
                ->getContainer()
                ->get('idci_notification.manager')
                ->send()
            ;

            $total = 0;
            $totalErrors = 0;

            $output->writeln("Notifications logs");
            foreach($logs as $notifierServiceName => $infos) {
                if (!empty($infos)) {
                    $output->writeln(sprintf('[%s]', $notifierServiceName));
                    foreach($infos as $status => $count) {
                        $output->writeln(sprintf('[%s] : %d', $status, $count));
                        $total++;
                        if ($status == Notification::STATUS_ERROR) {
                            $totalErrors++;
                        }
                    }
                }
            }

            $output->writeln(sprintf('%d notification(s) processed, %d error(s)',
                $total,
                $totalErrors
            ));

        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}


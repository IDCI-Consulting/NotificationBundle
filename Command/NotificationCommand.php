<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class NotificationCommand extends ContainerAwareCommand
{
    /**
     * Configure.
     */
    protected function configure()
    {
        $this
            ->setName('idci:notification:send')
            ->setDescription('Send notification from spool')
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Limit the number of notification sent')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command sends all notifications and show in the console a detail of notifications sent.
Here is an example of usage of this command <info>php app/console %command.name%</info>

To send an limited number of notifications on each run, you can use the optionnal application parameter <info>idci_notification.batch.limit</info> or use the limit option of this command.
The example below will send 10 firsts notifications:
    <info>php app/console %command.name% --limit=10</info>
EOT
            )
        ;
    }

    /**
     * Execute.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $notificationManager = $this->getContainer()->get('idci_notification.manager.notification');
        $options = $input->getOptions();

        $limit = $this->getContainer()->getParameter('idci_notification.batch.limit');
        if ($options['limit']) {
            $limit = $options['limit'];
        }

        $countErrors = 0;
        $notifications = $notificationManager->findBy(
            array('status' => Notification::STATUS_NEW),
            array(
                'priority' => 'DESC',
                'id' => 'ASC',
            ),
            $limit
        );
        $output->writeln(sprintf('<info>Send notifications (%d)</info>', count($notifications)));
        foreach ($notifications as $notification) {
            $notificationManager->notify($notification);
            if ($notification->getStatus() == Notification::STATUS_ERROR) {
                ++$countErrors;
                $output->writeln(sprintf('<error>Notification %s not send</error>', $notification));
            } else {
                $output->writeln(sprintf('<comment>Notifications %s sent</comment>', $notification));
            }
        }
        $output->writeln(sprintf('%d notification(s) processed, %d error(s)',
            count($notifications),
            $countErrors
        ));
    }
}

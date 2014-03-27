<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class NotifierCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('idci_notification.manager.notification')) {
            return;
        }

        $definition = $container->getDefinition('idci_notification.manager.notification');
        $taggedServices = $container->findTaggedServiceIds('idci_notification.notifier');
        $notifiers = array();
        $notifiersConfiguration = $container->getParameter('idci_notification.notifiers.configuration');
        // In order to declare untagged 'form.type' directly to the form.extension
        $formExtensionDefinition = $container->getDefinition('form.extension');

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $alias = $attributes["alias"];
                $notifierReference = new Reference($id);
                $notifiers[$alias] = $alias;
                $definition->addMethodCall(
                    'addNotifier',
                    array($notifierReference, $alias)
                );

                // Add the notifiers configuration to the right notifier
                if(!$container->hasDefinition($id)) {
                    throw new UndefindedDefinitionException($id);
                }
                $notifierDefinition = $container->getDefinition($id);
                $notifierDefinition->replaceArgument(1, $notifiersConfiguration[$alias]);

                // Define Notification form
                $notificationFormAlias = sprintf('notification_%s', $alias);
                $notificationFormServiceId = sprintf('idci_notification.form.type.%s', $notificationFormAlias);
                $notificationFormDefinition = new DefinitionDecorator('idci_notification.form.type.abstract_notification');
                $notificationFormDefinition->replaceArgument(0, $alias);
                $notificationFormDefinition->replaceArgument(1, $notifierReference);
                $container->setDefinition($notificationFormServiceId, $notificationFormDefinition);

                // Define NotifierConfiguration form
                $notifierConfigurationFormAlias = sprintf('notifier_configuration_%s', $alias);
                $notifierConfigurationFormServiceId = sprintf('idci_notification.form.type.%s', $notifierConfigurationFormAlias);
                $notifierConfigurationFormDefinition = new DefinitionDecorator('idci_notification.form.type.abstract_notifier_configuration');
                $notifierConfigurationFormDefinition->replaceArgument(0, $alias);
                $notifierConfigurationFormDefinition->replaceArgument(1, $notifierReference);
                $container->setDefinition($notifierConfigurationFormServiceId, $notifierConfigurationFormDefinition);

                // Declare untagged 'form.type' directly to the form.extension
                $types = (null === $formExtensionDefinition->getArgument(1)) ?
                    array() :
                    $formExtensionDefinition->getArgument(1)
                ;
                $types[$notificationFormAlias] = $notificationFormServiceId;
                $types[$notifierConfigurationFormAlias] = $notifierConfigurationFormServiceId;
                $formExtensionDefinition->replaceArgument(1, $types);
            }
        }

        $container->setParameter('idci_notification.notifiers', $notifiers);
    }
}

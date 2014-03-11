<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
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
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $notifierReference = new Reference($id);
                $notifiers[$attributes["alias"]] = $attributes["alias"];
                $definition->addMethodCall(
                    'addNotifier',
                    array($notifierReference, $attributes["alias"])
                );

                $formAlias = sprintf('notification_%s', $attributes["alias"]);
                $formServiceId = sprintf('idci_notification.form.type.%s', $formAlias);
                $formDefinition = new DefinitionDecorator('idci_notification.form.type.abstract_notification');
                $formDefinition->replaceArgument(0, $attributes["alias"]);
                $formDefinition->replaceArgument(1, $notifierReference);
                $formDefinition->setAbstract(false);
                $container->setDefinition($formServiceId, $formDefinition);

                // Declare untagged 'form.type' directly to the form.extension
                $formExtensionDefinition = $container->getDefinition('form.extension');
                $types = (null === $formExtensionDefinition->getArgument(1)) ?
                    array() :
                    $formExtensionDefinition->getArgument(1)
                ;
                $types[$formAlias] = $formServiceId;
                $formExtensionDefinition->replaceArgument(1, $types);
            }
        }

        $container->setParameter('idci_notification.notifiers', $notifiers);
    }
}

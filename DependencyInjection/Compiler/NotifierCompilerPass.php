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
        if (!$container->hasDefinition('idci_notification.manager')) {
            return;
        }

        $definition = $container->getDefinition('idci_notification.manager');
        $taggedServices = $container->findTaggedServiceIds('idci_notification.notifier');
        $notifiers = array();
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addNotifier',
                    array(new Reference($id), $attributes["alias"])
                );

                $notifiers[$attributes["alias"]] = $attributes["alias"];
            }
        }

        $container->setParameter('idci_notification.notifiers', $notifiers);
    }
}

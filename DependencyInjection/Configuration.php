<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('idci_notification');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->arrayNode('notifiers')
                    ->children()
                        ->append($this->addEmailParametersNode())
                        ->append($this->addSmsOcitoParametersNode())
                        ->append($this->addFacebookParametersNode())
                        ->append($this->addTwitterParametersNode())
                        ->append($this->addMailParametersNode())
                        ->append($this->addPushIOSParameterNode())
                        ->append($this->addPushAndroidParameterNode())
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    public function addEmailParametersNode()
    {
        $builder = new TreeBuilder();
        $emailNode = $builder->root('email');

        $emailNode
            ->children()
                ->scalarNode('default_configuration')
                    ->defaultValue('default')
                ->end()
                ->arrayNode('configurations')
                    ->useAttributeAsKey('')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('transport')
                                ->isRequired()
                                ->validate()
                                ->ifNotInArray(array('smtp', 'sendmail', 'mail'))
                                    ->thenInvalid('Invalid transport "%s"')
                                ->end()
                            ->end()
                            ->scalarNode('from')->end()
                            ->scalarNode('fromName')->end()
                            ->scalarNode('replyTo')->end()
                            ->scalarNode('server')->end()
                            ->scalarNode('login')->end()
                            ->scalarNode('password')->end()
                            ->integerNode('port')->min(0)->max(65536)->treatNullLike(0)->end()
                            ->scalarNode('encryption')
                                ->validate()
                                ->ifNotInArray(array(null, 'ssl', 'tls'))
                                    ->thenInvalid('Invalid encryption "%s"')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $emailNode;
    }

    public function addSmsOcitoParametersNode()
    {
        $builder = new TreeBuilder();
        $smsOcitoNode = $builder->root('sms_ocito');

        $smsOcitoNode
            ->children()
                ->scalarNode('default_configuration')
                    ->defaultValue('default')
                ->end()
                ->arrayNode('configurations')
                    ->useAttributeAsKey('')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('userName')->isRequired()->end()
                            ->scalarNode('password')->isrequired()->end()
                            ->scalarNode('senderAppId')->isRequired()->end()
                            ->scalarNode('senderId')->end()
                            ->integerNode('flag')->defaultValue(3)->end()
                            ->scalarNode('priority')->end()
                            ->integerNode('timeToLiveDuration')->min(0)->end()
                            ->integerNode('timeToSendDuration')->min(0)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $smsOcitoNode;
    }

    public function addFacebookParametersNode()
    {
        $builder = new TreeBuilder();
        $facebookNode = $builder->root('facebook');

        $facebookNode
            ->children()
                ->scalarNode('default_configuration')
                    ->defaultValue('default')
                ->end()
                ->arrayNode('configurations')
                    ->useAttributeAsKey('')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('login')->isRequired()->end()
                            ->scalarNode('password')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $facebookNode;
    }

    public function addTwitterParametersNode()
    {
        $builder = new TreeBuilder();
        $twitterNode = $builder->root('twitter');

        $twitterNode
            ->children()
                ->scalarNode('default_configuration')
                    ->defaultValue('default')
                ->end()
                ->arrayNode('configurations')
                    ->useAttributeAsKey('')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('consumerKey')->isRequired()->end()
                            ->scalarNode('consumerSecret')->isRequired()->end()
                            ->scalarNode('oauthAccessToken')->isRequired()->end()
                            ->scalarNode('oauthAccessTokenSecret')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $twitterNode;
    }

    public function addMailParametersNode()
    {
        $builder = new TreeBuilder();
        $mailNode = $builder->root('mail');

        $mailNode
            ->children()
                ->scalarNode('default_configuration')
                    ->defaultValue('default')
                ->end()
                ->arrayNode('configurations')
                    ->useAttributeAsKey('')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('firstName')->isRequired()->end()
                            ->scalarNode('lastName')->isRequired()->end()
                            ->scalarNode('address')->isRequired()->end()
                            ->scalarNode('postalCode')->isRequired()->end()
                            ->scalarNode('city')->isRequired()->end()
                            ->scalarNode('country')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $mailNode;
    }

    public function addPushIOSParameterNode()
    {
        $builder = new TreeBuilder();
        $pushIOSNode = $builder->root('push_ios');

        $pushIOSNode
            ->children()
                ->scalarNode('certificates_directory')
                    ->defaultValue("%kernel.root_dir%/../bin/certificates")
                ->end()
                ->scalarNode('default_configuration')
                    ->defaultValue('default')
                ->end()
                ->arrayNode('configurations')
                    ->useAttributeAsKey('')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('certificate')->isRequired()->end()
                            ->scalarNode('passphrase')->isRequired()->end()
                            ->booleanNode('useSandbox')->defaultValue(false)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $pushIOSNode;
    }

    public function addPushAndroidParameterNode()
    {
        $builder = new TreeBuilder();
        $pushAndroidNode = $builder->root('push_android');

        $pushAndroidNode
            ->children()
                ->scalarNode('default_configuration')
                    ->defaultValue('default')
                ->end()
                ->arrayNode('configurations')
                    ->useAttributeAsKey('')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('apiKey')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $pushAndroidNode;
    }
}

IDCINotificationBundle Configuration Reference
==============================================

Default Configuration
---------------------

### Configuration Structure
Structure your configuration in your `app/config/config.yml` file :
Exemple : Structure of an email and an sms configuration.
```yml
xxx_notification:
notifiers:
    email:
        default_configuration: default
        configurations:
            default:
                transport:  %notifier_email.default.transport%
                from:       %notifier_email.default.from%
                server:     %notifier_email.default.server%
                login:      %notifier_email.default.login%
                password:   %notifier_email.default.password%
                port:       %notifier_email.default.port%
                encryption: %notifier_email.default.encryption%
    sms:
        default_configuration: default
        configurations:
            default:
                phone_number: %notifier_sms.default.phone_number%
```
Note: It is possible to have more than one configuration for each notifier(check Advanced Configuration).

Add parameters in your `app/config/parameter.yml` file :
```yml
# Email notifier default configuration
notifier_email.default.transport: smtp
notifier_email.default.from: test@test.fr
notifier_email.default.server: smtp.xxx.com
notifier_email.default.login: your_login
notifier_email.default.password: your_password
notifier_email.default.port: 587
notifier_email.default.encryption: ssl

# SMS notifier default configuration
notifier_sms.default.phone_number: 0616941545

```
Add parameters in your `app/config/parameter.yml.dist` file :
```yml
# Email notifier default configuration
notifier_email.default.transport: ~
notifier_email.default.from: ~
notifier_email.default.server: ~
notifier_email.default.login: ~
notifier_email.default.password: ~
notifier_email.default.port: ~
notifier_email.default.encryption: ~

# SMS notifier default configuration
notifier_sms.default.phone_number: ~
```

### Validation and merging
Your configuration from app/config files has to be validated and merged.
Validate and merge your configuration in `DependencyInjection/Configuration.php` file of your Bundle :
```php
<?php
// DependencyInjection/Configuration.php
public function getConfigTreeBuilder()
{
    $treeBuilder = new TreeBuilder();
    $rootNode = $treeBuilder->root('xxx_notification');

    // Here you should define the parameters that are allowed to
    // configure your bundle. See the documentation linked above for
    // more information on that topic.

    $rootNode
        ->children()
            ->arrayNode('notifiers')
                ->children()
                    ->append($this->addEmailParametersNode())
                    ->append($this->addSmsParametersNode())
                    // ...
                ->end()
            ->end()
        ->end()
    ;

    return $treeBuilder;
}
```
Note : How to add a section.
Add a section with the instruction `append()`
```php
->append($this->addParametersNode())
```
Your Section
```php
<?php
// DependencyInjection/Configuration.php
// ...
public function addParametersNode()
{
    $builder = new TreeBuilder();
    $node = $builder->root('parameters');

    $node
        ->isRequired()
        ->requiresAtLeastOneElement()
        ->useAttributeAsKey('name')
        ->prototype('array')
            ->children()
                ->scalarNode('value')->isRequired()->end()
            ->end()
        ->end()
    ;

    return $node;
}
```
For more details please check the documentation : [Symfony](http://symfony.com/fr/doc/current/components/config/definition.html#ajouter-des-sections)
Exemple : addSmsParametersNode()
```php
<?php
// DependencyInjection/Configuration.php
public function addSmsParametersNode()
{
    $builder = new TreeBuilder();
    $smsNode = $builder->root('sms');

    $smsNode
        ->children()
            ->scalarNode('default_configuration')
                ->defaultValue('default')
            ->end()
            ->arrayNode('configurations')
                ->useAttributeAsKey('')
                ->prototype('array')
                    ->children()
                        ->integerNode('phone_number')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
        ->end()
    ;

    return $smsNode;
}
```

Advanced Configuration
----------------------

The advanced configuration allow you to have more than one configuration for each notifier.

### Configuration Structure
Structure your configuration in your `app/config/config.yml` file :
Exemple : Structure of an email configuration with two configurations(default and myconfiguration).
```yml
xxx_notification:
notifiers:
    email:
        default_configuration: default
        configurations:
            default:
                transport:  %notifier_email.default.transport%
                from:       %notifier_email.default.from%
                server:     %notifier_email.default.server%
                login:      %notifier_email.default.login%
                password:   %notifier_email.default.password%
                port:       %notifier_email.default.port%
                encryption: %notifier_email.default.encryption%
            myconfiguration:
                transport:  %notifier_email.myconfiguration.transport%
                from:       %notifier_email.myconfiguration.from%
                server:     %notifier_email.myconfiguration.server%
                login:      %notifier_email.myconfiguration.login%
                password:   %notifier_email.myconfiguration.password%
                port:       %notifier_email.myconfiguration.port%
                encryption: %notifier_email.myconfiguration.encryption%
```

Add parameters in your `app/config/parameter.yml` file :
```yml
# Email notifier default configuration
notifier_email.default.transport: smtp
notifier_email.default.from: test@test.fr
notifier_email.default.server: smtp.xxx.com
notifier_email.default.login: your_login
notifier_email.default.password: your_password
notifier_email.default.port: 587
notifier_email.default.encryption: ssl

# Email notifier myconfiguration configuration
notifier_email.myconfiguration.transport: sendmail
notifier_email.myconfiguration.from: test2@test.fr
notifier_email.myconfiguration.server: smtp.xxx.com
notifier_email.myconfiguration.login: your_login
notifier_email.myconfiguration.password: your_password
notifier_email.myconfiguration.port: 587
notifier_email.myconfiguration.encryption: tls
```
Add parameters in your `app/config/parameter.yml.dist` file :
```yml
# Email notifier default configuration
notifier_email.default.transport: ~
notifier_email.default.from: ~
notifier_email.default.server: ~
notifier_email.default.login: ~
notifier_email.default.password: ~
notifier_email.default.port: ~
notifier_email.default.encryption: ~

# Email notifier myconfiguration configuration
notifier_email.myconfiguration.transport: ~
notifier_email.myconfiguration.from: ~
notifier_email.myconfiguration.server: ~
notifier_email.myconfiguration.login: ~
notifier_email.myconfiguration.password: ~
notifier_email.myconfiguration.port: ~
notifier_email.myconfiguration.encryption: ~
```

### Validation and merging
The validation and merging process is still the same.

Note : How to repeat a configuration with a prototype

A prototype can be used to add a definition which may be repeated many times inside the current node.
```php
<?php
// DependencyInjection/Configuration.php
// ...
public function addParametersNode()
{
    $builder = new TreeBuilder();
    $node = $builder->root('parameters');

    $node
        ->isRequired()
        ->requiresAtLeastOneElement()
        ->useAttributeAsKey('name')
        ->prototype('array')
            ->children()
                ->scalarNode('value')->isRequired()->end()
            ->end()
        ->end()
    ;

    return $node;
}
```
For more details please check the documentation : [Symfony](http://symfony.com/fr/doc/current/components/config/definition.html#noeuds-tableau)
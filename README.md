NotificationBundle
==================

Symfony2 notification bundle


Installation
------------

Add dependencies in your `composer.json` file:
```json
"require": {
    ...
    "jms/serializer-bundle":          "dev-master",
    "friendsofsymfony/rest-bundle":   "dev-master",
    "idci/notification-bundle":       "dev-master"
},
```

Install these new dependencies of your application:
```sh
$ php composer.phar update
```

Enable bundles in your application kernel:
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FOS\RestBundle\FOSRestBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new IDCI\Bundle\NotificationBundle\IDCINotificationBundle(),
    );
}
```

Import the routing configuration:
```yml
# app/config/routing.yml

idci_notification_api:
    type:     rest
    resource: "@IDCINotificationBundle/Resources/config/routing.yml"
    prefix:   /api
```


Documentation
-------------

[Read the Documentation](Resources/doc/index.md)


Tests
-----

Install bundle dependencies:
```sh
$ php composer.phar update
```

To execute unit tests:
```sh
$ phpunit --coverage-text
```

NotificationBundle
==================

Symfony2 notification bundle


Installation
============

To install this bundle please follow the next steps:

First add the dependency in your `composer.json` file:

```json
"require": {
    ...
    "idci/notification-bundle": "dev-master"
},
```

Then install the bundle with the command:

```sh
php composer update
```

Enable the bundle in your application kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new IDCI\Bundle\NotificationBundle\IDCINotificationBundle(),
    );
}
```

TODO: to test the bundle, you should declare a route as follow : 

```php
// app/config/routing.yml

notification:
    resource: "../../vendor/idci/notification-bundle/IDCI/Bundle/NotificationBundle/Controller/"
    type:     annotation
```

Now the Bundle is installed.


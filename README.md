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
        //
        new IDCI\Bundle\NotificationBundle\IDCINotificationBundle(),
    );
}
```

To test the bundle, you should declare a route as follow : 

```php
// app/config/routing.yml

notification:
    resource: "../../vendor/idci/notification-bundle/IDCI/Bundle/NotificationBundle/Controller/"
    type:     annotation
```

Now the Bundle is installed.


How to use the REST API
=======================

This bundle provides a REST API which can be called by another application.

The following routes are available:

Create a notification:
----------------------

| Route          | Method | Parameters
|----------------|--------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------
| /notifications | POST   | typeA=[{dataA1, dataA2, ..., dataAN}]&typeB=[{dataB1, dataB2, ..., dataBN}](&source_name=MySource)


Parameters examples:

    email=[{"to": {"to": "toto@titi.fr", "cc": "titi@toto.fr, tutu@titi.fr", "bcc": null}, "content": {"subject": "A subject message", "message": "the message to be send", "htmlMessage": "<h1>Titre</h1><p>Message</p>", "attachements": []}}]&
    sms=[{"to": "0612345678, 0610111213", "content": "this is a sms"}, {"to": "0698765432", "content": "this is an other sms"}]&
    mail=[{"to": {"first_name": "fName", "last_name": "lName", "address": "adress", "postal_code": "75001", "city": "Paris", "country": "FR"}, "content": "Mail message"}]
    source_name="my_notification_source"

The source name parameter is optional, it's just used to associate a notification with a source name.
By default the given API sets the notification client IP address in this field.

Create a notification with a Symfony2 application:
--------------------------------------------------

In order to simplify the usage of this Notification Bundle, you can use [IDCINotificationApiClientBundle](https://github.com/IDCI-Consulting/NotificationApiClientBundle.git)


Advanced Server
---------------

If you wish to use an advanced Http Api server, we suggest you the [DaApiServerBundle](https://github.com/Gnuckorg/DaApiServerBundle.git).
Follow the documentation to install it.


How to extend this bundle
==========================

If you wish to create your own notification type

Create a class which extends `IDCI\Bundle\NotificationBundle\Notifier\AbstractNotifier`

```php
<?php

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Notifier\AbstractNotifier;

class MyNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        // Here the code to send your notification
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        // To add custom fields store in to
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        // To add custom fields store in content
    }
}
```

Now declare your notifier as service:

```yml
idci_notification.notifier.mynotifier:
    class: IDCI\Bundle\NotificationBundle\Notifier\MyNotifier
    arguments: []
    tags:
        - { name: idci_notification.notifier, alias: my_notifier }
```

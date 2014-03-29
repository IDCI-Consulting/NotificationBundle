IDCINotificationBundle Notification Service
===========================================


Notification
------------
### What is a Notification ?
...

### How to create a Notification ?
...

### How to send a Notification ?
...
(crontab with sf2 cmd)


Notifier
--------
### What is a Notifier ?
...

### How to create a Notifier
If you wish to add your own notifier, create a class which extends `IDCI\Bundle\NotificationBundle\Notifier\AbstractNotifier`
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

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        // // To add custom fields store in from
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

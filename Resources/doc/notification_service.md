IDCINotificationBundle Notification Service
===========================================


Notification
------------
### What is a Notification ?
A notification is a message used to inform consumer about a subject.

### How to create a Notification ?
Use a web service to create a notification.

| Type   | Path                     | Description                                         | Valide types                        |
|--------|--------------------------|-----------------------------------------------------|-------------------------------------|
| GET    | /notification/new/{type} | Displays a form to create a new Notification entity | email, sms, mail, facebook, twitter |


### How to send a Notification ?
To send a Notification you can use this command line : `idci:notification:send`
```sh
$php app/console idci:notification:send
```

You can also use [IDCINotificationApiClientBundle](https://github.com/IDCI-Consulting/NotificationApiClientBundle.git).
Precisely you can use this command line `tms:notification:notify`:
```sh
$ php app/console tms:notification:notify email '{"notifierAlias":"sfr",
"from": {"transport": "smtp", "from":"","server": "smtp.tessi.fr",login":"sender@tessi.com", "password": "password", "port": "465", "encryption": "ssl"},"to": {"to": "test@email.fr", "cc": "titi@toto.fr, tutu@titi.fr", "bcc": null},"content": {"subject": "notification via command line", "message": "the message to be send", "htmlMessage": "<h1>Titre</h1><p>Message</p>", "attachments": []}}'
```

Notifier
--------
### What is a Notifier ?
A notifier is an object. It is used to manage the way to send a notification.

### How to create a Notifier
If you wish to add your own notifier, create a class which extends `IDCI\Bundle\NotificationBundle\Notifier\AbstractNotifier`
```php
<?php

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;

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
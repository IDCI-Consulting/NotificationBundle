IDCINotificationBundle Notification Service
===========================================


Notification
------------
### What is a Notification ?
A notification is an object. It is used to inform a user about a subject.

Object Notification :

![UML Notification Class Diagram](Notification_class.png)


### What is a notifierAlias ?
| Attribute         | Type   |
|-------------------|--------|
| notifierAlias     | string |

A notifierAlias is an attribut of the object Notification. It is used to identity which configuration has to be used to send a notification. This notifierAlias can identify a configuration in a database or in `app/config/config.yml` file (more details : [Several_configurations_for_each_type_of_notifier](https://github.com/IDCI-Consulting/NotificationBundle/blob/master/Resources/doc/configuration_reference.md#several-configurations-for-each-type-of-notifier)).

Example : a configuration identified by an alias in a database
| Id | Alias    | Type  | Configuration                                                             |
|----|----------|-------|---------------------------------------------------------------------------|
| 1  | myalias1 | email | {"transport": "smtp","server": "smtp.test.com","login": "toto@test.fr","password": "test","port": 587,"encryption": "tls"} |
Note 1 : valides types : email, sms, mail, facebook, twitter
Note 2 : the couple value {alias, type} is used to define the unique constraint in your database.

Example : a configuration identified by an alias in `app/config/config.yml`.
```yml
# Notification
idci_notification:
    notifiers:
        email:
            default_configuration: default
            configurations:
                default:
                    transport:  smtp
                    from:       test@test.fr
                    server:     smtp.xxx.com
                    login:      yourlogin
                    password:   yourpassword
                    port:       587
                    encryption: ssl
                myAlias2:
                    transport: sendmail
                    from:      test2@test.fr
```
Note : Primery the notifierAlias is used to find a configuration in the database. Secondly, if there is no configuration in there then it is used to check in `app/config/config.yml` file.

### How to create a Notification ?
There are 3 methods to create an notification.

Method 1 : Use a web service of the bundle.

| Type   | Path                                                     | Description            |
|--------|----------------------------------------------------------|------------------------|
| POST   | [/notifications](api/notification/post_notifications.md) | Create an notification |

Method 2 : Create an interface to manage a notification (CRUD)

Method 3 : Use [IDCINotificationApiClientBundle](https://github.com/IDCI-Consulting/NotificationApiClientBundle.git).
Exemple : Using the command line `tms:notification:notify` to create an email notification.
```sh
$ php app/console tms:notification:notify email '{"notifierAlias":"sfr",
"from": {"transport": "smtp", "from":"","server": "smtp.tessi.fr",login":"sender@tessi.com", "password": "password", "port": "465", "encryption": "ssl"},"to": {"to": "test@email.fr", "cc": "titi@toto.fr, tutu@titi.fr", "bcc": null},"content": {"subject": "notification via command line", "message": "the message to be send", "htmlMessage": "<h1>Titre</h1><p>Message</p>", "attachments": []}}'
```
Note : This command line uses the web service of IDCINotificationBundle to create a notification.

### How to send a Notification ?
To send a Notification you can use this command line : `idci:notification:send`
```sh
$ php app/console idci:notification:send
```
Process of this command :

1. Find all notifications with status NEW.

2. Use the suitable notifier for each type of notification to send it.

3. Send notifications one by one.

Notifier
--------
### What is a Notifier ?
A notifier is an object. It is used to send a notification.
There are five notifier types.
| Type     | Notifier         | development state |
|----------|------------------|-------------------|
| email    | emailNotifier    | 100%              |
| sms      | smsNotifier      | 10%               |
| mail     | mailNotifier     | 10%               |
| facebook | facebookNotifier | 10%               |
| twitter  | twitterNotifier  | 10%               |

Notifier UML schema:

![Notifier class diagram](Notifier_class_diagram.png)

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
        return array(
            'toField1'  => array('text', array('required' => true)),
            'toField2'  => array('text', array('required' => false)),
            //...
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        // To add custom fields store in content
        return array(
            'contentField1'  => array('text', array('required' => false)),
            'contentField2'  => array('text', array('required' => false)),
            //...
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        // To add custom fields store in from
        return array(
            'fromField1'  => array('text', array('required' => false)),
            'fromField2'  => array('text', array('required' => true)),
            //...
        );
    }
}
```
Detail : the values in array are used to create form field
```
'field'  => array('text', array('required' => false))
```
'field'    : field name
'text'     : [built-in field type](http://symfony.com/doc/current/book/forms.html#built-in-field-types)
'required' : field type options ([required option](http://symfony.com/doc/current/book/forms.html#field-type-options))

Now declare your notifier as service:
```yml
idci_notification.notifier.mynotifier:
    class: IDCI\Bundle\NotificationBundle\Notifier\MyNotifier
    arguments: []
    tags:
        - { name: idci_notification.notifier, alias: my_notifier }
```

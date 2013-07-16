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

This bundle provides a REST API which can be called by an other application.

The following routes are available:

Create a notification:
----------------------

  Author Name         First Book to Read       Genre
  ----------------    ----------------------   ---------
  Larry Niven         The Mode in God's Eye    SF
  James Lovegrove     Worldstorm               Fantasy
  China Mieville      Perdido Stree Station    Fantasy


| **Route**      | /notifications/add                                                                                                                                                  |
|----------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Method**     | POST                                                                                                                                                                |
|----------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Parameters** | typeA=[{dataA1, dataA2, ..., dataAN}]&                                                                                                                              |
:                : typeB=[{dataB1, dataB2, ..., dataBN}]                                                                                                                               |
:                :                                                                                                                                                                     |
:                : ex:                                                                                                                                                                 |
:                :   email=[{"to": "toto@titi.fr", "cc": ["titi@toto.fr", "tutu@titi.fr"], "bcc": "", "message": "the message to be send", "attachements": []}]&                       |
:                :   sms=[{"to": ["0612345678", "0610111213"], "message": "this is a sms"}, {"to": "0698765432", "message": "this is an other sms"}]&                                  |
:                :   mail=[{"first_name": "fName", "last_name": "lName", "address": "adress", "postal_code": "75001", "city": "Paris", "country": "FR", "message": "Mail message"}]    |


How to extends this bundle
==========================

if you wish to create your own notification you have to create a notification object
which must extend AbstractNotification as for example :

```php
// Facebook notification example

class FacebookNotification extends AbstractNotification
{
    //......
}
```



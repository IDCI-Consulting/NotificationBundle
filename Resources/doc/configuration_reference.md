IDCINotificationBundle Configuration Reference
==============================================

Email
-----

###Parameters :
| Parameter  | Type    | Required | Valide values        |
|------------|---------|----------|----------------------|
| transport  | string  | yes      | smtp, sendmail, mail |
| from       | string  | no       | string value         |
| server     | string  | no       | string value         |
| login      | string  | no       | string value         |
| password   | string  | no       | string value         |
| port       | integer | no       | 0 <= value <= 65536  |
| encryption | string  | no       | null, ssl, tls       |

###Configuration
Add an email notifier configuration in `app/config/config.yml` :
```yml
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
```
SMS
---

###Parameters :
| Parameter     | Type    | Required | Valide values        |
|---------------|---------|----------|----------------------|
| phone_number  | integer | yes      | integer value        |

###Configuration
Add an sms notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        sms:
            default_configuration: default
            configurations:
                default:
                    phone_number: 0635214255
```

Mail
----

###Parameters :
| Parameter   | Type    | Required | Valide values        |
|-------------|---------|----------|----------------------|
| first_name  | string  | yes      | string value         |
| last_name   | string  | yes      | string value         |
| address     | string  | yes      | string value         |
| postal_code | integer | yes      | integer value        |
| city        | string  | yes      | string value         |
| country     | string  | yes      | string value         |

###Configuration
Add an mail notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        email:
            default_configuration: default
            configurations:
                default:
                    first_name:  Toto
                    last_name:   Titi
                    address:     '5 avenue Anatole'
                    postal_code: 75007
                    city:        Paris
                    country:     FR
```

Facebook
--------

###Parameters :
| Parameter   | Type    | Required | Valide values        |
|-------------|---------|----------|----------------------|
| login       | string  | yes      | string value         |
| password    | string  | yes      | string value         |

###Configuration
Add an facebook notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        email:
            default_configuration: default
            configurations:
                default:
                    login:    test@facebook.com
                    password: password
```

Twitter
-------

###Parameters :
| Parameter   | Type    | Required | Valide values        |
|-------------|---------|----------|----------------------|
| login       | string  | yes      | string value         |
| password    | string  | yes      | string value         |

###Configuration
Add an twitter notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        email:
            default_configuration: default
            configurations:
                default:
                    login:    '@@test'
                    password: password
```
More informations
-----------------
### Why using a default configuration ?
In case the notifierAlias and the configuration sent in a notification are not provided ; the default configuration is used to configure a notifier in order to send a notification.

### Several configurations for each type of notifier
Exemple : Several email notifier configurations in `app/config/config.yml` :
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
                notifierAlias:
                    transport: sendmail
                    from:      test2@test.fr
```
Note : Previously you defined a default configuration for each notifier. Now you can also add a personnal configuration to send a notification. This personnal configuration is use only if an notifierAlias is provided and if there is no configuration in database corresponding to this notifierAlias.


Overview of `app/config/config.yml`
-----------------------------------
```yml
//...
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
                myconfiguration:
                    transport: sendmail
                    from:      test2@test.fr
        sms:
            default_configuration: default
            configurations:
                default:
                    phone_number: 0635214255
        mail:
            default_configuration: default
            configurations:
                default:
                    first_name:  Toto
                    last_name:   Titi
                    address:     '5 avenue Anatole'
                    postal_code: 75007
                    city:        Paris
                    country:     FR
        facebook:
            default_configuration: default
            configurations:
                default:
                    login:    test@facebook.com
                    password: password
        twitter:
            default_configuration: default
            configurations:
                default:
                    login:    '@@test'
                    password: password
```

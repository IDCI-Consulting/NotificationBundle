IDCINotificationBundle Configuration Reference
==============================================

Email
-----

### Parameters :
| Parameter  | Type    | Required | Valide values
|------------|---------|----------|----------------------
| transport  | string  | yes      | smtp, sendmail, mail
| replyTo    | string  | no       | string value
| from       | string  | no       | string value
| server     | string  | no       | string value
| login      | string  | no       | string value
| password   | string  | no       | string value
| port       | integer | no       | 0 <= value <= 65536
| encryption | string  | no       | null, ssl, tls

### Configuration
Add an email notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        email:
            default_configuration: default
            configurations:
                default:
                    transport:  smtp
                    replyTo:    replyto@test.fr
                    from:       test@test.fr
                    server:     smtp.xxx.com
                    login:      yourlogin
                    password:   yourpassword
                    port:       587
                    encryption: ssl
```
SMS
---

### Parameters :
| Parameter     | Type    | Required | Valide values        |
|---------------|---------|----------|----------------------|
| phone_number  | string  | yes      | string value         |

### Configuration
Add an sms notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        sms:
            default_configuration: default
            configurations:
                default:
                    phoneNumber: 0635214255
```

SMS Ocito
---

### Parameters :
| Parameter         | Type    | Required | Valide values
|-------------------|---------|----------|--------------
| userName          | string  | yes      | string value
| password          | string  | yes      | string value
| senderAppId       | string  | yes      | string value
| senderId          | string  | no       | string value
| flag              | integer | yes      | integer value
| priority          | string  | no       | string value
| timeToLiveTimeout | integer | no       | integer value in seconde
| timeToSendTimeout | integer | no       | integer value in seconde

### Configuration
Add an sms notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        sms_ocito:
            default_configuration: default
            configurations:
                default:
                    userName:          username_ocito
                    password:          password_ocito
                    senderAppId:       1234
                    senderId:          SenderValue
                    flag:              3
                    priority:          L
                    timeToLiveTimeout: 123
                    timeToSendTimeout: 456
```

Mail
----

### Parameters :
| Parameter   | Type    | Required | Valide values        |
|-------------|---------|----------|----------------------|
| first_name  | string  | yes      | string value         |
| last_name   | string  | yes      | string value         |
| address     | string  | yes      | string value         |
| postal_code | string  | yes      | string value         |
| city        | string  | yes      | string value         |
| country     | string  | yes      | string value         |

### Configuration
Add an mail notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
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
```

Facebook
--------

### Parameters :
| Parameter   | Type    | Required | Valide values        |
|-------------|---------|----------|----------------------|
| login       | string  | yes      | string value         |
| password    | string  | yes      | string value         |

### Configuration
Add an facebook notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        facebook:
            default_configuration: default
            configurations:
                default:
                    login:    test@facebook.com
                    password: password
```

Twitter
-------

### Parameters :
| Parameter                 | Type    | Required | Valide values
|---------------------------|---------|----------|--------------
| consumerKey               | string  | yes      | string value
| consumerSecret            | string  | yes      | string value
| oauthAccessToken          | string  | yes      | string value
| oauthAccessTokenSecret    | string  | yes      | string value

### Configuration
Add an twitter notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        twitter:
            default_configuration: default
            configurations:
                default:
                    consumerKey:            'your_consumer_key'
                    consumerSecret:         'your_consumer_secret'
                    oauthAccessToken:       'your_oauth_access_token'
                    oauthAccessTokenSecret: 'your_oauth_access_token_secret'
```
#### Note 1 : What you have to do to obtain twitter configuration :
- Make sure that you have a twitter account. If not, create it one : [create_twitter_account](https://twitter.com/signup).

- Create an application to access to your twitter account : [create_application](https://apps.twitter.com/).

#### Note 2 : What you have to do to link your application to your twitter account
1. Associate a phone number to your twitter account : use the twitter application on smartphone to do this.
2. Return to your [application](https://apps.twitter.com/), change permission into `Read, Write and Access direct messages`(in Permission section) and regenerate your access token (in API keys section).
3. Return to your twitter account and check parameters, you should see your application.

Push iOS
--------

### Parameters :
| Parameter   | Type    | Required | Valide values
|-------------|---------|----------|--------------
|certificate  | string  | yes      | string value
|passphrase   | string  | yes      | string value

### Configuration
Add a push iOS notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        push_ios:
            default_configuration: default
            configurations:
                default:
                    certificate: '\/path\/of\/your\/certificate_file.pem'
                    passphrase:  'your_passphrase'
```

Push Android
------------

### Parameters :
| Parameter   | Type    | Required | Valide values
|-------------|---------|----------|--------------
| apiKey      | string  | yes      | string value using to identify an android application

### Configuration
Add an push_android notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        push_android:
            default_configuration: default
            configurations:
                default:
                    apiKey: 'your_api_key'

```

More informations
-----------------
### Why using a default configuration ?
The notifier provider default_configuration parameter allow to identify which configuration has to be used when a notification is received without notifier informations to send it.

### Several configurations for each type of notifier
Example : Several email notifier configurations in `app/config/config.yml` :
```yml
# Notification
idci_notification:
    notifiers:
        email:
            default_configuration: default
            configurations:
                default:
                    transport:  smtp
                    replyTo:    replyto@test.fr
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
Note : This possibility allows to have a flexible configuration. When a notification is received with an notifierAlias you can use it to choose which configuration has to be used.
For more details about [notiferAlias](notification_service.md#what-is-the-role-of-a-notifieralias-).

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
                    replyTo:    replyto@test.fr
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
        sms_ocito:
            default_configuration: default
            configurations:
                default:
                    userName:          username_ocito
                    password:          password_ocito
                    senderAppId:       1234
                    senderId:          SenderValue
                    flag:              3
                    priority:          L
                    timeToLiveTimeout: 123
                    timeToSendTimeout: 456
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
                    consumerKey:            'your_consumer_key'
                    consumerSecret:         'your_consumer_secret'
                    oauthAccessToken:       'your_oauth_access_token'
                    oauthAccessTokenSecret: 'your_oauth_access_token_secret'
        push_ios:
            default_configuration: default
            configurations:
                default:
                    certificate: '\/path\/of\/your\/certificate_file.pem'
                    passphrase:  'your_passphrase'
        push_android:
            default_configuration: default
            configurations:
                default:
                    apiKey: 'your_api_key'

```

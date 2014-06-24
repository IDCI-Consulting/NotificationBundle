IDCINotificationBundle Configuration Reference
==============================================

Email
-----

### Parameters :
| Parameter  | Type    | Required | Description
|------------|---------|----------|----------------------
| transport  | string  | yes      | Transport data (smtp, sendmail, mail)
| from       | string  | no       | Sender email address
| fromName   | string  | no       | The name associated to an email address
| replyTo    | string  | no       | Email address to reply
| server     | string  | no       | Server data
| login      | string  | no       | Login data
| password   | string  | no       | Password data
| port       | integer | no       | Port value : 0 <= value <= 65536
| encryption | string  | no       | Encryption value (null, ssl, tls)

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
                    from:       test@test.fr
                    fromName:   Test
                    replyTo:    replyto@test.fr
                    server:     smtp.xxx.com
                    login:      yourlogin
                    password:   yourpassword
                    port:       587
                    encryption: ssl
```

SMS Ocito
---

### Parameters :
| Parameter          | Type    | Required | Description
|--------------------|---------|----------|--------------
| userName           | string  | yes      | SMS Manager's account name
| password           | string  | yes      | SMS Manager's password
| senderAppId        | string  | yes      | Id of the application used to send SMS
| senderId           | string  | no       | Id of the sender
| flag               | integer | yes      | Flag value
| priority           | string  | no       | H : high, L : low
| timeToLiveDuration | integer | no       | Duration used to define "time to live" of a SMS
| timeToSendDuration | integer | no       | Duration used to define the moment when the SMS should be sent (deferred message).

Note : How to define the flag

| Flag    | Requirements | Possible values | Description
|---------|--------------|-----------------|------------
| F0      |              | 0 or 1          | SMS Manger Push asks acquittals and acknowledgments from carrier
| F1      | F0 enabled   | 0 or 2          | Ask SMS Manager Push to send acquittals and acknowledgments to client applcation
| F2      |              | 0 or 4          | Enable special class (F2=0 enable class 1)
| F3 + F4 | F2 enabled   | 0, 8, 16 or 24  | [00] class 0 : flash; [01] class 1 : default value from cellphone; [10] class 2 : saved on SIM card ; [11] class 3: saved in cellphone

Exemple :

1. Client application doesn't want to recieve acquittals and acknowledgments : flag = 0
2. SMS with acquittals and acknowledgments : flag = 1*2⁰ + 1*2¹ = 3
3. Class 2 SMS with acquittals and acknowledgments : flag = 1*2⁰ + 1*2¹ + 1*2² + 1*2⁴ = 23

### Configuration
Add an sms notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        sms_ocito:
            default_configuration: default
            configurations:
                default:
                    userName:           username_ocito
                    password:           password_ocito
                    senderAppId:        1234
                    senderId:           SenderValue
                    flag:               3
                    priority:           L
                    timeToLiveDuration: 200
                    timeToSendDuration: 100
```
Note : "timeToLiveDuration" must be greater than "timeToSendDuration".

Mail
----

### Parameters :
| Parameter   | Type    | Required | Description
|-------------|---------|----------|------------
| firstName   | string  | yes      | Sender first name
| lastName    | string  | yes      | Sender last name
| address     | string  | yes      | Sender address
| postalCode  | string  | yes      | Sender postalCode
| city        | string  | yes      | Sender city
| country     | string  | yes      | Sender country

### Configuration
Add an mail notifier configuration in `app/config/config.yml` :
```yml
idci_notification:
    notifiers:
        mail:
            default_configuration: default
            configurations:
                default:
                    firstName:   Toto
                    lastName:    Titi
                    address:     '5 avenue Anatole'
                    postalCode:  75007
                    city:        Paris
                    country:     FR
```

Facebook
--------

### Parameters :
| Parameter   | Type    | Required | Description
|-------------|---------|----------|------------
| login       | string  | yes      | Login value
| password    | string  | yes      | password value

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
| consumerKey               | string  | yes      | The "consumer key" of your twitter application
| consumerSecret            | string  | yes      | The "consumer secret" of your twitter application
| oauthAccessToken          | string  | yes      | The "oauth access token" of your twitter application
| oauthAccessTokenSecret    | string  | yes      | The "oauth access token secret" of your twitter application

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
| Parameter    | Type    | Required | Description
|--------------|---------|----------|--------------
| certificate  | string  | yes      | The path of the certificate
| passphrase   | string  | yes      | The passphrase to use the certificate
| useSandbox   | boolean | no       | true : send push iOS in a sandbox

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
                    useSandbox:  false
```

Push Android
------------

### Parameters :
| Parameter   | Type    | Required | Description
|-------------|---------|----------|------------
| apiKey      | string  | yes      | The key using to identify an android application

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
                    from:       test@test.fr
                    fromName:   Test
                    replyTo:    replyto@test.fr
                    server:     smtp.xxx.com
                    login:      yourlogin
                    password:   yourpassword
                    port:       587
                    encryption: ssl
                myconfiguration:
                    transport: sendmail
                    from:      test2@test.fr
        sms_ocito:
            default_configuration: default
            configurations:
                default:
                    userName:           username_ocito
                    password:           password_ocito
                    senderAppId:        1234
                    senderId:           SenderValue
                    flag:               3
                    priority:           L
                    timeToLiveDuration: 123
                    timeToSendDuration: 456
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
                    sandBox:     false
        push_android:
            default_configuration: default
            configurations:
                default:
                    apiKey: 'your_api_key'

```

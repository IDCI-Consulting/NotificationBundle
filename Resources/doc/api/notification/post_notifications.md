IDCINotificationBundle API: [POST] Notifications
================================================

Create one notification

## General
|             | Values
|-------------|-------
| **Method**  | POST
| **Path**    | /notifications
| **Formats** | json|xml
| **Secured** | true

## HTTP Request parameters
| Name       | Optional | Default | Requirements | Description
|------------|----------|---------|--------------|------------
| sourceName | true     |         |              | The source name
| email      | true     |         |              | Email data
| facebook   | true     |         |              | Facebook data
| mail       | true     |         |              | Mail data
| sms        | true     |         |              | Sms data
| twitter    | true     |         |              | Twitter data

### source_name
To associate a source name with the notifications, the source name parameter is optional.
Automaticaly add the source IP: `"[ip] - source_name"`.

### Email

#### Field "notifierAlias" :

| Optional | Requirements | Description
|----------|--------------|------------
| true     | string value | The notifier alias used to identify a configuration

#### Field "to" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| to          | false    | string value | Email delivery address
| cc          | true     | string value | Carbon copy adresses
| bcc         | true     | string value | Blind carbon copy addresses

#### Field "from" :

| Subfield    | Optional | Requirements         | Description
|-------------|----------|----------------------|------------
| transport   | true     | smtp, sendmail, mail | Transport data
| from        | true     | string value         | Sender email address
| login       | true     | string value         | Login data
| password    | true     | string value         | Password data
| server      | true     | string value         | Server data
| port        | true     | 0 <= value <= 65536  | Port data
| encryption  | true     | null, ssl, tls       | Encryption data

#### Field "content" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| subject     | false    | string value | Subject data
| message     | true     | string value | Email message
| htmlMessage | true     | string value | Email message in html format
| attachments | true     | string value | Attachments data

#### Case 1 : notification with notifier parameters
```
email=[
    {
        "to": {
            "to": "toto@titi.fr",
            "cc": "titi@toto.fr, tutu@titi.fr",
            "bcc": null
        },
        "from": {
            "transport":"smtp",
            "from" :"test@test.fr",
            "login":"mail@mxserver.com",
            "password": "password",
            "server": "smtp.mxserver.fr",
            "port": "465",
            "encryption": "ssl"
        },
        "content": {
            "subject": "A subject message",
            "message": "the message to be send",
            "htmlMessage": "<h1>Titre</h1><p>Message</p>",
            "attachments": []
        }
    }
]
```
#### Case 2 : notification without notifier parameters
```
email=[
    {
        "notifierAlias" : "my_email_alias",
        "to": {
            "to": "toto@titi.fr",
            "cc": "titi@toto.fr, tutu@titi.fr",
            "bcc": null
        },
        "content": {
            "subject": "A subject message",
            "message": "the message to be send",
            "htmlMessage": "<h1>Titre</h1><p>Message</p>",
            "attachments": []
        }
    }
]
```

### Facebook

#### Field "notifierAlias" :

| Optional | Requirements | Description
|----------|--------------|------------
| true     | string value | The notifier alias used to define a configuration

#### Field "to" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| to          | false    | string value | Facebook delivery address

#### Field "from" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| login       | true     | string value | Login data
| password    | true     | string value | Password data

#### Field "content" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| message     | true     | string value | Message data

#### Case 1 : notification with notifier parameters
```
facebook=[
    {
        "to": {
            "to": "toto@facebook.com"
        },
        "from": {
            "login": "mylogin@facebook.com",
            "password" : "mypassword"
        },
        "content": {
            "message" : "The message to be sent."
        }
    }
]
```
#### Case 2 : notification without notifier parameters
```
facebook=[
    {
        "notifierAlias" : "my_facebook_alias",
        "to": {
            "to": "toto@facebook.com"
        },
        "content": {
            "message" : "The message to be sent."
        }
    }
]
```

### Mail

#### Field "notifierAlias" :

| Optional | Requirements | Description
|----------|--------------|------------
| true     | string value | The notifier alias used to define a configuration

#### Field "to" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| firstName   | false    | string value | Recipient first name
| lastName    | false    | string value | Recipient last name
| address     | false    | string value | Recipient address
| postalCode  | false    | 0 <= value   | Recipient postal code
| city        | false    | string value | Recipient city
| country     | false    | string value | Recipient country (for France, use FR)

#### Field "from" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|-------------
| firstName   | true     | string value | Sender first name
| lastName    | true     | string value | Sender last name
| address     | true     | string value | Sender address
| postalCode  | true     | 0 <= value   | Sender postal code
| city        | true     | string value | Sender city
| country     | true     | string value | Sender country (for France, use FR)

#### Field "content" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| message     | true     | string value | Message data

#### Case 1 : notification with notifier parameters
```
mail=[
    {
        "to": {
            "firstName": "fName",
            "lastName": "lName",
            "address": "address",
            "postalCode": "75001",
            "city": "Paris",
            "country": "FR"
        },
        "from": {
            "firstName": "senderFirstName",
            "lastName": "senderLastName",
            "address": "address",
            "postalCode": "75001",
            "city": "Paris",
            "country": "FR"
        },
        "content": {"message" : "Mail message"}
    }
]
```
#### Case 2 : notification without notifier parameters
```
mail=[
    {
        "notifierAlias" : "my_mail_alias",
        "to": {
            "firstName": "fName",
            "lastName": "lName",
            "address": "address",
            "postalCode": "75001",
            "city": "Paris",
            "country": "FR"
        },
        "content": {"message" : "Mail message"}
    }
]
```

### Sms

#### Field "notifierAlias" :

| Optional | Requirements | Description
|----------|--------------|------------
| true     | string value | The notifier alias used to define a configuration

#### Field "to" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| to          | false    | string value | Recipient phone number

#### Field "from" :

| Subfield     | Optional | Requirements | Description
|--------------|----------|--------------|------------
| phone_number | true     | 0 <= value   | Sender phone number

#### Field "content" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| message     | true     | string value | Message data

#### Case 1 : notification with notifier parameters
```
sms=[
    {
        "to": {"to": "0612345678, 0610111213"},
        "from" : {"phone_number": "0614589655"},
        "content": {"message" :"this is a sms"}
    }
]
```
#### Case 2 : notification without notifier parameters
```
sms=[
    {
        "notifierAlias" : "my_sms_alias",
        "to": {"to": "0612345678, 0610111213"},
        "content": {"message" :"this is a sms"}
    }
]
```

### Twitter

#### Field "notifierAlias" :

| Optional | Requirements | Description
|----------|--------------|------------
| true     | string value | The notifier alias used to define a configuration

#### Field "to" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| to          | false    | string value | Recipient twitter address

#### Field "from" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| login       | true     | string value | Login data
| password    | true     | string value | Password data

#### Field "content" :

| Subfield    | Optional | Requirements | Description
|-------------|----------|--------------|------------
| message     | true     | string value | Message data

#### Case 1 : notification with notifier parameters
```
twitter=[
    {
        "to": {
            "to": "@toto"
        },
        "from": {
            "login": "@mylogin",
            "password" : "mypassword"
        },
        "content": {
            "message" : "The message to be sent."
        }
    }
]
```
#### Case 2 : notification without notifier parameters

```
twitter=[
    {
        "notifierAlias" : "my_twitter_alias",
        "to": {
            "to": "@toto"
        },
        "content": {
            "message" : "The message to be sent."
        }
    }
]
```

### Examples

To send one email notification:
```
sourceName="my_notification_source" &
email=[
    {
        "to": {
            "to": "toto@titi.fr",
            "cc": "titi@toto.fr, tutu@titi.fr",
            "bcc": null
        },
        "from": {
            "transport":"smtp",
            "from" :"test@test.fr",
            "login":"mail@mxserver.com",
            "password": "password",
            "server": "smtp.mxserver.fr",
            "port": "465",
            "encryption": "ssl",
        },
        "content": {
            "subject": "A subject message",
            "message": "the message to be send",
            "htmlMessage": "<h1>Titre</h1><p>Message</p>",
            "attachments": []
        }
    }
]
```

To send two email notifications:
```
sourceName="my_notification_source" &
email=[
    {
        "to": {
            "to": "toto1@titi.fr",
            "cc": "titi1@toto.fr, tutu@titi.fr",
            "bcc": null
        },
        "from": {
            "transport":"smtp",
            "from" :"test&=1@test.fr",
            "login":"mail1@mxserver.com",
            "password": "password1",
            "server": "smtp.mxserver.fr",
            "port": "465",
            "encryption": "ssl",
        },
        "content": {
            "subject": "the subject of the first message",
            "message": "the message to be send",
            "htmlMessage": "<h1>Titre</h1><p>Message</p>",
            "attachments": []
        }
    },
    {
        "notifierAlias" : "my_email_alias"
        ,
        "to": {
            "to": "toto2@titi.fr",
            "cc": "titi2@toto.fr, tutu@titi.fr",
            "bcc": null
        },
        "content": {
            "subject": "the subject of the seconde message",
            "message": "the message to be send",
            "htmlMessage": "<h1>Titre</h1><p>Message</p>",
            "attachments": []
        }
    }
]
```

To send two email and one sms notifications:
```
sourceName="my_notification_source" &
email=[
    {
        "to": {
            "to": "toto1@titi.fr",
            "cc": "titi1@toto.fr, tutu@titi.fr",
            "bcc": null
        },
        "from": {
            "transport":"smtp",
            "from" :"test&=1@test.fr",
            "login":"mail1@mxserver.com",
            "password": "password1",
            "server": "smtp.mxserver.fr",
            "port": "465",
            "encryption": "ssl",
        },
        "content": {
            "subject": "the subject of the first message",
            "message": "the message to be send",
            "htmlMessage": "<h1>Titre</h1><p>Message</p>",
            "attachments": []
        }
    },
    {
        "notifierAlias" : "my_email_alias2",
        "to": {
            "to": "toto2@titi.fr",
            "cc": "titi2@toto.fr, tutu@titi.fr",
            "bcc": null
        },
        "content": {
            "subject": "the subject of the seconde message",
            "message": "the message to be send",
            "htmlMessage": "<h1>Titre</h1><p>Message</p>",
            "attachments": []
        }
    }
] &
sms=[
    {
        "to": {"to": "0612345678, 0610111213"},
        "from" : {"phone_number": "0614589655"},
        "content": {"message" :"this is a sms"}
    }
]
```

## HTTP Response codes
| Code | Description
|------|------------
| 201  | Created
| 400  | Bad request (wrong query parameters)
| 501  | Not implemented (the notifier doesn't exist)
| 500  | Server error

## HTTP Response content examples
No response content

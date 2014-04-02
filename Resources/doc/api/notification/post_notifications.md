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

| Type    | Optional | Valide values
|---------|----------|--------------
| string  | true     | string value

#### Field "to" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|---------------
| to          | string  | false    | string value
| cc          | string  | true     | string value
| bcc         | string  | true     | string value

#### Field "from" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|--------------
| transport   | string  | true     | smtp, sendmail, mail
| from        | string  | true     | string value
| login       | string  | true     | string value
| password    | string  | true     | string value
| server      | string  | true     | string value
| port        | integer | true     | 0 <= value <= 65536
| encryption  | string  | true     | ull, ssl, tls

#### Field "content" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|--------------
| subject     | string  | false    | string value
| message     | string  | true     | string value
| htmlMessage | string  | true     | string value
| attachments | string  | true     | string value

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

| Type    | Optional | Valide values
|---------|----------|--------------
| string  | true     | string value 

#### Field "to" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|---------------
| to          | string  | false    | string value

#### Field "from" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|--------------
| login       | string  | true     | string value
| password    | string  | true     | string value

#### Field "content" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|--------------
| message     | string  | true     | string value

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

| Type    | Optional | Valide values
|---------|----------|--------------
| string  | true     | string value 

#### Field "to" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|---------------
| firstName   | string  | false    | string value
| lastName    | string  | false    | string value
| address     | string  | false    | string value
| postalCode  | integer | false    | 0 <= value
| city        | string  | false    | string value
| country     | string  | false    | string value

#### Field "from" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|---------------
| firstName   | string  | true     | string value
| lastName    | string  | true     | string value
| address     | string  | true     | string value
| postalCode  | integer | true     | 0 <= value
| city        | string  | true     | string value
| country     | string  | true     | string value

#### Field "content" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|--------------
| message     | string  | true     | string value

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

| Type    | Optional | Valide values
|---------|----------|--------------
| string  | true     | string value 

#### Field "to" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|---------------
| to          | string  | false    | string value

#### Field "from" :

| Subfield     | Type    | Optional | Valide values
|--------------|---------|----------|---------------
| phone_number | integer | true     | 0 <= value

#### Field "content" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|--------------
| message     | string  | true     | string value

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

| Type    | Optional | Valide values
|---------|----------|--------------
| string  | true     | string value 

#### Field "to" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|---------------
| to          | string  | false      | string value

#### Field "from" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|---------------
| login       | string  | true     | string value
| password    | string  | true     | string value

#### Field "content" :

| Subfield    | Type    | Optional | Valide values
|-------------|---------|----------|--------------
| message     | string  | true     | string value

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
source_name="my_notification_source" &
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
source_name="my_notification_source" &
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
        "notifierAlias" : "my_email_alias",
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
source_name="my_notification_source" &
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
        "notifierAlias" : "my_sms_alias",
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

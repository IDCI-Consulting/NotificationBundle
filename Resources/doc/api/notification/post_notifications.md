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
| Name           | Optional | Default | Requirements | Description
|----------------|----------|---------|--------------|------------
| source_name    | true     |         |              | The source name
| email          | true     |         |              | Email data
| facebook       | true     |         |              | Facebook data
| mail           | true     |         |              | Mail data
| sms            | true     |         |              | Sms data
| twitter        | true     |         |              | Twitter data

### source_name
To associate a source name with the notifications, the source name parameter is optional.
Automaticaly add the source IP: `"[ip] - source_name"`.

### Email
```
email=[
    {
        "notifier_alias" : "my_email_alias"
        ,
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

### Facebook
```
facebook=[
    {
        "notifier_alias" : "my_facebook_alias"
        ,
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

### Mail
```
mail=[
    {
        "notifier_alias" : "my_mail_alias"
        ,
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

### Sms
```
sms=[
    {
        "notifier_alias" : "my_sms_alias",
        "to": {"to": "0612345678, 0610111213"},
        "from" : {"phone_number": "0614589655"},
        "content": {"message" :"this is a sms"}
    }
]
```

### Twitter
```
twitter=[
    {
        "notifier_alias" : "my_twitter_alias"
        ,
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

### Examples

To send one email notification:
```
source_name="my_notification_source" &
email=[
    {
        "notifier_alias" : "my_email_alias"
        ,
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
        "notifier_alias" : "my_email_alias1"
        ,
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
        "notifier_alias" : "my_email_alias2"
        ,
        "to": {
            "to": "toto2@titi.fr",
            "cc": "titi2@toto.fr, tutu@titi.fr",
            "bcc": null
        },
        "from": {
            "transport":"smtp",
            "from" :"test2@test.fr",
            "login":"mail2@mxserver.com",
            "password": "password2",
            "server": "smtp.mxserver.fr",
            "port": "465",
            "encryption": "ssl",
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
        "notifier_alias" : "my_email_alias1"
        ,
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
        "notifier_alias" : "my_email_alias2"
        ,
        "to": {
            "to": "toto2@titi.fr",
            "cc": "titi2@toto.fr, tutu@titi.fr",
            "bcc": null
        },
        "from": {
            "transport":"smtp",
            "from" :"test2@test.fr",
            "login":"mail2@mxserver.com",
            "password": "password2",
            "server": "smtp.mxserver.fr",
            "port": "465",
            "encryption": "ssl",
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
        "notifier_alias" : "my_sms_alias",
        "to": {"to": "0612345678, 0610111213"},
        "from" : {"phone_number": "0614589655"},
        "content": {"message" :"this is a sms"}
    }
]
```

```
source_name="my_notification_source" &
email=[
    {
        "notifier_alias" : "my_email_alias"
        ,
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
    },
    {...}
] &
facebook=[
    {
        "notifier_alias" : "my_facebook_alias"
        ,
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
    },
    {...}
] &
mail=[
    {
        "notifier_alias" : "my_mail_alias"
        ,
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
    },
    {...}
] &
sms=[
    {
        "notifier_alias" : "my_sms_alias",
        "to": {"to": "0612345678, 0610111213"},
        "from" : {"phone_number": "0614589655"},
        "content": {"message" :"this is a sms"}
    },
    {...}
] &
twitter=[
    {
        "notifier_alias" : "my_twitter_alias"
        ,
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
    {...}
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

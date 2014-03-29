IDCINotificationBundle API: [POST] Notifications
================================================

Create one notification

## General
|             | Values
|-------------|-------
| **Method**  | POST
| **Path**    | /notifications.{_format}
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

### email
...

### facebook
...

### mail
...

### sms
...

### twiter
...

### examples

To send one email notification:
```
TODO
```

To send two email notifications:
```
TODO
```

To send two email and one sms notifications:
```
TODO
```

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
    },
    {...}
] &
sms=[
    {
        "to": "0612345678, 0610111213",
        "content": "this is a sms"
    },
    {...}
] &
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
    },
    {...}
]
```

## HTTP Response codes
| Code | Description
|------|------------
| 201  | Created
| 400  | Bad request (wrong query parameters)
| 501  | not implemented (the notifier doesn't exist)
| 500  | Server error

## HTTP Response content examples
No response content


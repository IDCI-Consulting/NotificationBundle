IDCINotificationBundle API: [GET] Notification Parameters
==========================================================

Retrieve one notification parameters

## General
|             | Values
|-------------|-------
| **Method**  | GET
| **Path**    | /notificationparameters/{type}
| **Formats** | json
| **Secured** | false

## HTTP Request parameters
| Name     | Optional | Description
|----------|----------|------------
| field    | true     | Notification field (to, from, content)

## HTTP Response codes
| Code | Description
|------|------------
| 200  | Ok
| 404  | Not found
| 500  | Server error

## HTTP Response content examples
Example : /notificationparameters/email
### json
```json
{
    "to":{
        "to":["text",{"required":true}],
        "cc":["text",{"required":false}],
        "bcc":["text",{"required":false}]
    },
    "from":{
        "transport":["choice",{
            "required":false,
            "choices":{"smtp":"smtp","sendmail":"sendmail","mail":"mail"}
        }],
        "from":["text",{"required":false}],
        "fromName":["text",{"required":false}],
        "replyTo":["text",{"required":false}],
        "server":["text",{"required":false}],
        "login":["text",{"required":false}],
        "password":["password",{"required":false}],
        "port":["integer",{"required":false}],
        "encryption":["choice",{"required":false,"choices":{"ssl":"ssl","tls":"tls"}}]
    },
    "content":{
        "subject":["text",{"required":true}],
        "message":["textarea",{"required":false}],
        "htmlMessage":["textarea",{"required":false}],
        "attachments":["text",{"required":false}]
    }
}
```

Example : /notificationparameters/email?field=from
```json
{
    "from":{
        "transport":["choice",{
            "required":false,
            "choices":{"smtp":"smtp","sendmail":"sendmail","mail":"mail"}
        }],
        "from":["text",{"required":false}],
        "fromName":["text",{"required":false}],
        "replyTo":["text",{"required":false}],
        "server":["text",{"required":false}],
        "login":["text",{"required":false}],
        "password":["password",{"required":false}],
        "port":["integer",{"required":false}],
        "encryption":["choice",{"required":false,"choices":{"ssl":"ssl","tls":"tls"}}]
    }
}
```
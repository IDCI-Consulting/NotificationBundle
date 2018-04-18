IDCINotificationBundle API: [GET] Notification Parameters
=========================================================

List all notification parameters

## General
|             | Values
|-------------|-------
| **Method**  | GET
| **Path**    | /notificationparameters
| **Formats** | json
| **Secured** | false

## HTTP Response codes
| Code | Description
|------|------------
| 200  | Ok
| 404  | Not found
| 500  | Server error

## HTTP Response content examples

### json
```json
{
    "email":{
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
        }
    },
    "sms_ocito":{
        "to":{
            "phoneNumber":["text",{"required":true,"max_length":30}]
        },
        "from":{
            "userName":["text",{"required":false,"max_length":30}],
            "password":["text",{"required":false,"max_length":30}],
            "senderAppId":["text",{"required":false,"max_length":10}],
            "senderId":["text",{"required":false,"max_length":11}],
            "flag":["integer",{"required":false,"max_length":10,"data":3}],
            "priority":["choice",{"required":false,"choices":{"H":"high","L":"low"}}],
            "timeToLiveDuration":["integer",{"required":false}],
            "timeToSendDuration":["integer",{"required":false}]
        },
        "content":{
            "message":["text",{"required":true,"max_length":70}]
        }
    }
}
```

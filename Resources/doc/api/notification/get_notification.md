IDCINotificationBundle API: [GET] Notification
==============================================

Retrieve one notification

## General
|             | Values
|-------------|-------
| **Method**  | GET
| **Path**    | /notifications/{id}.{_format}
| **Formats** | json|xml
| **Secured** | true

## HTTP Request parameters
No request parameters

## HTTP Response codes
| Code | Description
|------|------------
| 200  | Ok
| 404  | Not found (wrong id)
| 500  | Server error

## HTTP Response content examples

### json
```json
{
    "class": "IDCI\\Bundle\\NotificationBundle\\Entity\\Notification",
    "data": {
        "id": 1,
        "type": "email",
        "to": "\"Array\"",
        "createdAt": "2013-09-14T15:36:00+0200",
        "updatedAt": "2013-10-31T00:11:23+0100",
        "status": "NEW",
        "content": "\"Array\"",
        "source": "[127.0.0.1] MyApplicationSource",
        "log": "logdetails"
    }
}
```

### xml
```xml
<result>
    <entry>IDCI\Bundle\NotificationBundle\Entity\Notification</entry>
    <entry>
        <id>1</id>
        <type>email</type>
        <to>"Array"</to>
        <createdAt>2013-09-14T15:36:00+0200</createdAt>
        <updatedAt>2013-10-31T00:11:23+0100</updatedAt>
        <status>NEW</status>
        <content>"Array"</content>
        <source>[127.0.0.1] MyApplicationSource</source>
        <log>logdetails</log>
    </entry>
</result>
```

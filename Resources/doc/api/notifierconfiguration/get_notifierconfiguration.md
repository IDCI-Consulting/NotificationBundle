IDCINotificationBundle API: [GET] Notifier Configuration
========================================================

Retrieve one notifierconfiguration

## General
|             | Values
|-------------|-------
| **Method**  | GET
| **Path**    | /notifierconfigurations/{id}.{_format}
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
    "class": "IDCI\\Bundle\\NotificationBundle\\Entity\\NotifierConfiguration",
    "data": {
        "id": 1,
        "alias": "test",
        "type": "email",
        "configuration": ""
    }
}
```

### xml
```xml
<result>
    <entry>IDCI\Bundle\NotificationBundle\Entity\NotifierConfiguration</entry>
    <entry>
        <id>1</id>
        <alias>test</alias>
        <type>email</type>
        <configuration></configuration>
    </entry>
</result>
```

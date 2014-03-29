IDCINotificationBundle API: [GET] Notifications
===============================================

List all notifications

## General
|             | Values
|-------------|-------
| **Method**  | GET
| **Path**    | /notifications.{_format}
| **Formats** | json|xml
| **Secured** | false

## HTTP Request parameters
| Name     | Optional | Default | Requirements | Description
|----------|----------|---------|--------------|------------
| limit    | true     | 20      | \d+          | Pagination limit
| offset   | true     | 0       | \d+          | Pagination offet

## HTTP Response codes
| Code | Description
|------|------------
| 200  | Ok
| 400  | Bad request (wrong query parameters)
| 500  | Server error

## HTTP Response content examples

### json
```json
[
    {
        "id": 1,
        "type": "email",
        "createdAt": "2013-09-14T15:36:00+0200",
        "updatedAt": "2013-10-31T00:11:23+0100",
        "source": "[127.0.0.1] MyApplicationSource"
    },
    {
        "id": 2,
        "type": "sms",
        "createdAt": "2013-10-30T21:04:36+0100",
        "updatedAt": "2013-10-30T21:04:36+0100",
        "source": "[127.0.0.1] MyApplicationSource"
    },
    ...
]
```

### xml
```xml
<result>
    <entry>
        <id>1</id>
        <type>email</type>
        <createdAt>2013-09-14T15:36:00+0200</createdAt>
        <updatedAt>2013-10-31T00:11:23+0100</updatedAt>
        <source>[127.0.0.1] MyApplicationSource</source>
    </entry>
    <entry>
        <id>2</id>
        <type>sms</type>
        <createdAt>2013-10-30T21:04:36+0100</createdAt>
        <updatedAt>2013-10-30T21:04:36+0100</updatedAt>
        <source>[127.0.0.1] MyApplicationSource</source>
    </entry>
    ...
</result>
```

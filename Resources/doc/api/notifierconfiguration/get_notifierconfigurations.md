IDCINotificationBundle API: [GET] Notifier Configurations
=========================================================

List all notifications

## General
|             | Values
|-------------|-------
| **Method**  | GET
| **Path**    | /notifierconfigurations.{_format}
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
        "alias":"alias1",
        "type":"email"
    },
    {
        "id": 1,
        "alias":"alias2",
        "type":"sms"
    },
    ...
]
```

### xml
```xml
<result>
    <entry>
        <id>1</id>
        <alias>alias1</alias>
        <type>email</type>
    </entry>
    <entry>
        <id>2</id>
        <alias>alias2</alias>
        <type>sms</type>
    </entry>
    ...
</result>
```

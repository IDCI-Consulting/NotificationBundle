IDCINotificationBundle API Reference
====================================


API List
--------

### Notification
| Method | Path                                                                     | Description
|--------|--------------------------------------------------------------------------|------------
| GET    | [/notifications.{_format}](api/notification/get_notifications.md)        | List all notifications
| GET    | [/notifications/{id}.{_format}](api/notification/get_notification.md)    | Retrieve one notification
| POST   | [/notifications/{id}.{_format}](api/notification/post_notifications.md)   | Create one notification

### NotifierConfiguration
| Method | Path                                                                                               | Description
|--------|----------------------------------------------------------------------------------------------------|------------
| GET    | [/notifierconfigurations.{_format}](api/notifierconfiguration/get_notifierconfigurations.md)       | List all notifierconfigurations
| GET    | [/notifierconfigurations/{id}.{_format}](api/notifierconfiguration/get_notifierconfiguration.md)   | Retrieve one notifierconfiguration


API Client
----------

If you wish to use this notification provider with a Symfony2 application,
you can use [IDCINotificationApiClientBundle](https://github.com/IDCI-Consulting/NotificationApiClientBundle.git).


API Server
----------

If you wish to use an advanced Http Api server,
we suggest the [DaApiServerBundle](https://github.com/Gnuckorg/DaApiServerBundle.git).


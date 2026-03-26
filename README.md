# PHP Centrifugo

Composite dispatcher for sending messages to Centrifugo with support for multiple transports.

## Installation

```bash
composer require inexdigital/php-centrifugo:^1.0
```

## Usage

```php
use Inexdigital\Centrifugo\Factory\RealtimeMessagingDispatcherFactory;
use Inexdigital\Centrifugo\RealtimeMessagingInterface;
use Inexdigital\Centrifugo\RealtimeMessageInterface;

$realtimeMessaging = new RealtimeMessagingDispatcher(1);
$realtimeMessaging->addTransport(new RealtimeMessagingInterface());
$realtimeMessaging->addTransport(new RealtimeMessagingInterface());

$realtimeMessaging->publish(new RealtimeMessageInterface());
```

<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo\Service;

use Inexdigital\Centrifugo\Enum\RealtimeMessageTransportVersion;
use Inexdigital\Centrifugo\RealtimeMessageInterface;
use Inexdigital\Centrifugo\VersionedRealtimeMessagingInterface;
use phpcent\Client;
use Psr\Log\LoggerInterface;

class CentrifugoHttpRealtimeMessaging implements VersionedRealtimeMessagingInterface
{
    public function __construct(
        private readonly Client $client,
        private readonly ?LoggerInterface $logger = null
    ) {
    }

    public function publish(RealtimeMessageInterface $message): void
    {
        $this->publishRaw($message->getChannel(), $message->getData());
    }

    private function publishRaw(string $channel, array $data): void
    {
        $this->logger?->debug(__CLASS__ . '::' . __METHOD__, [
            'channel' => $channel,
            'data' => $data
        ]);

        $this->client->publish($channel, $data);
    }

    public function getVersion(): RealtimeMessageTransportVersion
    {
        return RealtimeMessageTransportVersion::V1;
    }
}

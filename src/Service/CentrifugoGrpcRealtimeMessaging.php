<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo\Service;

use Inexdigital\Centrifugo\Enum\RealtimeMessageTransportVersion;
use Inexdigital\Centrifugo\Exception\RealtimeMessagingException;
use Inexdigital\Centrifugo\Grpc\Centrifugal\Api\CentrifugoApiClient;
use Inexdigital\Centrifugo\VersionedRealtimeMessagingInterface;
use Inexdigital\Centrifugo\Grpc\Centrifugal\Api\PublishRequest;
use JsonException;
use Inexdigital\Centrifugo\RealtimeMessageInterface;
use Psr\Log\LoggerInterface;

use const Grpc\STATUS_OK;

final class CentrifugoGrpcRealtimeMessaging implements VersionedRealtimeMessagingInterface
{
    public function __construct(
        private readonly CentrifugoApiClient $client,
        private readonly int $timeoutMicroseconds = 2000000,
        private readonly ?LoggerInterface $logger = null
    ) {
    }

    /**
     * @throws JsonException
     */
    public function publish(RealtimeMessageInterface $message): void
    {
        $channels = $message->getChannels();
        $data = $message->getData();

        foreach ($channels as $channel) {
            try {
                $this->publishToChannel($channel, $data);
            } catch (RealtimeMessagingException $e) {
                $this->logger?->error(
                    'Realtime publish failed',
                    [
                        'channel' => $channel,
                        'data' => $data,
                        'exception' => $e,
                    ]
                );
            }
        }
    }

    public function getVersion(): RealtimeMessageTransportVersion
    {
        return RealtimeMessageTransportVersion::V2;
    }

    /**
     * @throws JsonException
     * @throws RealtimeMessagingException
     */
    private function publishToChannel(string $channel, array $data): void
    {
        $request = (new PublishRequest())
            ->setChannel($channel)
            ->setData(
                (string) json_encode($data, JSON_THROW_ON_ERROR)
            );

        [$response, $status] = $this->client
            ->Publish(
                argument: $request,
                options: ['timeout' => $this->timeoutMicroseconds]
            )
            ->wait();

        if ($status->code !== STATUS_OK) {
            throw new RealtimeMessagingException(
                sprintf('Centrifugo gRPC error (%d): %s', $status->code, $status->details)
            );
        }

        if ($response === null) {
            throw new RealtimeMessagingException('Centrifugo gRPC returned empty response');
        }

        if ($response->getError()) {
            throw new RealtimeMessagingException(
                sprintf('Centrifugo API error: %s', $response->getError()->getMessage())
            );
        }
    }
}
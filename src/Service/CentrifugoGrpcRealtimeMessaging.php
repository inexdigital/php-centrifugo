<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo\Service;

use Inexdigital\Centrifugo\Enum\RealtimeMessageTransportVersion;
use Inexdigital\Centrifugo\Grpc\Centrifugal\Api\CentrifugoApiClient;
use Inexdigital\Centrifugo\VersionedRealtimeMessagingInterface;
use Inexdigital\Centrifugo\Grpc\Centrifugal\Api\PublishRequest;
use JsonException;
use RuntimeException;
use Inexdigital\Centrifugo\RealtimeMessageInterface;

use const Grpc\STATUS_OK;

final class CentrifugoGrpcRealtimeMessaging implements VersionedRealtimeMessagingInterface
{
    public function __construct(
        private readonly CentrifugoApiClient $client,
        private readonly int $timeoutMicroseconds = 2000000
    ) {
    }

    /**
     * @throws JsonException
     */
    public function publish(RealtimeMessageInterface $message): void
    {
        $request = (new PublishRequest())
            ->setChannel($message->getChannel())
            ->setData(
                (string) json_encode($message->getData(), JSON_THROW_ON_ERROR)
            );

        [$response, $status] = $this->client
            ->Publish(
                argument: $request,
                options: ['timeout' => $this->timeoutMicroseconds]
            )
            ->wait();

        if ($status->code !== STATUS_OK) {
            throw new RuntimeException(
                sprintf('Centrifugo gRPC error (%d): %s', $status->code, $status->details)
            );
        }

        if ($response === null) {
            throw new RuntimeException('Centrifugo gRPC returned empty response');
        }

        if ($response->getError()) {
            throw new RuntimeException(
                sprintf('Centrifugo API error: %s', $response->getError()->getMessage())
            );
        }
    }

    public function getVersion(): RealtimeMessageTransportVersion
    {
        return RealtimeMessageTransportVersion::V2;
    }
}
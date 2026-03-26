<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo\Factory;

use Inexdigital\Centrifugo\Grpc\Centrifugal\Api\CentrifugoApiClient;
use Grpc\ChannelCredentials;

final class CentrifugoApiClientFactory
{
    public function create(string $endpoint): CentrifugoApiClient
    {
        return new CentrifugoApiClient(
            $endpoint,
            [
                'credentials' => ChannelCredentials::createInsecure(),
            ]
        );
    }
}

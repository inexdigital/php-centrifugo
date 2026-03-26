<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo\Factory;

use Inexdigital\Centrifugo\Enum\RealtimeMessageTransportVersion;
use Inexdigital\Centrifugo\Service\RealtimeMessagingDispatcher;
use Inexdigital\Centrifugo\VersionedRealtimeMessagingInterface;
use Psr\Log\LoggerInterface;

final class RealtimeMessagingDispatcherFactory
{
    public function __construct(
        private readonly int $versionAllowed,
    ) {
    }

    /**
     * @param iterable<VersionedRealtimeMessagingInterface> $transports
     */
    public function create(
        iterable $transports,
        LoggerInterface $logger = null,
    ): RealtimeMessagingDispatcher {
        $realtimeMessagingDispatcher = new RealtimeMessagingDispatcher($logger);

        foreach ($transports as $transport) {
            if (!$this->allows($transport->getVersion())) {
                continue;
            }

            $realtimeMessagingDispatcher->addTransport($transport);
        }

        return $realtimeMessagingDispatcher;
    }

    public function allows(RealtimeMessageTransportVersion $version): bool
    {
        return ($this->versionAllowed & $version->value) === $version->value;
    }
}
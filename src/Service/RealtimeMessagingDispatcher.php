<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo\Service;

use Inexdigital\Centrifugo\RealtimeMessageInterface;
use Inexdigital\Centrifugo\RealtimeMessagingDispatcherInterface;
use Inexdigital\Centrifugo\RealtimeMessagingInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class RealtimeMessagingDispatcher implements RealtimeMessagingDispatcherInterface
{
    /** @param RealtimeMessageInterface[] $transports */
    private array $transports = [];

    public function __construct(
        private readonly ?LoggerInterface $logger = null
    ) {
    }

    public function addTransport(RealtimeMessagingInterface $transport): void
    {
        $this->transports[] = $transport;
    }

    public function publish(RealtimeMessageInterface $message): void
    {
        foreach ($this->transports as $transport) {
            try {
                $transport->publish($message);
            } catch (Throwable $e) {
                $this->logger?->error(
                    'Realtime publish failed',
                    [
                        'transport' => get_class($transport),
                        'exception' => $e,
                    ]
                );
            }

        }
    }
}
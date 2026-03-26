<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo;

interface RealtimeMessagingDispatcherInterface extends RealtimeMessagingInterface
{
    public function addTransport(RealtimeMessagingInterface $transport): void;
}
<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo;

interface RealtimeMessagingInterface
{
    public function publish(RealtimeMessageInterface $message): void;
}

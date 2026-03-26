<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo\Message;

use Inexdigital\Centrifugo\RealtimeMessageInterface;

class RealtimeMessage implements RealtimeMessageInterface
{
    public function __construct(
        private readonly string $channel,
        private readonly array $data
    ) {
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getData(): array
    {
        return $this->data;
    }
}

<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo\Message;

use Inexdigital\Centrifugo\RealtimeMessageInterface;

class RealtimeMessage implements RealtimeMessageInterface
{
    /**
     * @param string[] $channels
     */
    public function __construct(
        private readonly array $channels,
        private readonly array $data
    ) {
    }

    /**
     * @return string[]
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    public function getData(): array
    {
        return $this->data;
    }
}

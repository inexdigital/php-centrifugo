<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo;

interface RealtimeMessageInterface
{
    /**
     * @return string[]
     */
    public function getChannels(): array;
    public function getData(): array;
}

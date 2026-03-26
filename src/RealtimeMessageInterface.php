<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo;

interface RealtimeMessageInterface
{
    public function getChannel(): string;
    public function getData(): array;
}

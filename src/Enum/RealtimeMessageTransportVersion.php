<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo\Enum;

enum RealtimeMessageTransportVersion: int
{
    case V1 = 1;
    case V2 = 2;
}

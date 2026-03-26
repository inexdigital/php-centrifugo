<?php

declare(strict_types=1);

namespace Inexdigital\Centrifugo;

use Inexdigital\Centrifugo\Enum\RealtimeMessageTransportVersion;

interface VersionedRealtimeMessagingInterface extends RealtimeMessagingInterface
{
    public function getVersion(): RealtimeMessageTransportVersion;
}

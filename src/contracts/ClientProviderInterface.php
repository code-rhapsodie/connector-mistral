<?php

declare(strict_types=1);

namespace CodeRhapsodie\Contracts\ConnectorMistral;

use CodeRhapsodie\Bundle\ConnectorMistral\Client\MistralClient;

interface ClientProviderInterface
{
    public function getClient(): MistralClient;
}

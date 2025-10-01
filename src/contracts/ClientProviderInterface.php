<?php

declare(strict_types=1);

namespace CodeRhapsodie\Contracts\ConnectorMistral;

interface ClientProviderInterface
{
    public function getClient(): AiClientInterface;
}

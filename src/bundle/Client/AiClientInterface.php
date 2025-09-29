<?php
declare(strict_types=1);

namespace CodeRhapsodie\Bundle\ConnectorMistral\Client;

interface AiClientInterface
{
    public function generate(array $prompts, array $config): array;
}

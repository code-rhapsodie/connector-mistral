<?php
declare(strict_types=1);

namespace CodeRhapsodie\Bundle\ConnectorMistral\Client;

interface AiClientInterface
{
    /**
     * @param array<int, array<string, list<array<string, string>>|string>> $prompts
     * @param array<string, mixed> $config
     * @return array<array-key, mixed>
     */
    public function generate(array $prompts, array $config): array;
}

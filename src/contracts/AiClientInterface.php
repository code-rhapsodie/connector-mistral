<?php
declare(strict_types=1);

namespace CodeRhapsodie\Contracts\ConnectorMistral;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface AiClientInterface
{
    /**
     * @param array<int, array<string, list<array<string, string>>|string>> $prompts
     * @param array<string, mixed> $config
     * @return array<array-key, mixed>
     */
    public function generate(array $prompts, array $config): array;

    public static function generateClient(HttpClientInterface $client, string $token): self;
}

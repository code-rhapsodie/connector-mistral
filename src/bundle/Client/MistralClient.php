<?php
declare(strict_types=1);

namespace CodeRhapsodie\Bundle\ConnectorMistral\Client;


use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MistralClient implements AiClientInterface
{
    private HttpClientInterface $client;

    public static function generateClient(HttpClientInterface $client): MistralClient
    {
        $obj = new self();
        $obj->client = $client;
        return $obj;
    }

    private function __construct()
    {
    }

    public function generate(array $prompts, array $config): array
    {
        $response = $this->client->request('POST', '/v1/chat/completions', [
            'headers' => ['Accept' => 'application/json'],
            'json' => [
                ...$config,
                'messages' => $prompts
            ]
        ]);
        return $response->toArray();
    }
}

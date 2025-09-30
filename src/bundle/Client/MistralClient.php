<?php
declare(strict_types=1);

namespace CodeRhapsodie\Bundle\ConnectorMistral\Client;


use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MistralClient implements AiClientInterface
{
    private HttpClientInterface $client;

    private string $token;

    public static function generateClient(HttpClientInterface $client, string $token): MistralClient
    {
        $obj = new self();
        $obj->client = $client;
        $obj->token = $token;
        return $obj;
    }

    private function __construct()
    {
    }

    public function generate(array $prompts, array $config): array
    {
        $response = $this->client->request('POST', '/v1/chat/completions', [
            'auth_bearer' => $this->token,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                ...$config,
                'messages' => $prompts
            ]
        ]);
        return $response->toArray();
    }
}

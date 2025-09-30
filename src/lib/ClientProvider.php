<?php


declare(strict_types=1);

namespace CodeRhapsodie\ConnectorMistral;

use CodeRhapsodie\Bundle\ConnectorMistral\Client\MistralClient;
use CodeRhapsodie\Contracts\ConnectorMistral\ClientProviderInterface;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class ClientProvider implements ClientProviderInterface
{
    public function __construct(
        private ConfigResolverInterface $configResolver,
        private HttpClientInterface $httpClient
    ) {
    }

    public function getClient(): MistralClient
    {
        $client = $this->httpClient->withOptions([
            'base_uri'=>'https://api.mistral.ai',
            'auth_bearer' => $this->configResolver->getParameter('connector_mistral.mistral.api_key'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);

        return MistralClient::generateClient($client,$this->configResolver->getParameter('connector_mistral.mistral.api_key'));
    }
}

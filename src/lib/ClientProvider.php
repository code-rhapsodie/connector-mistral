<?php


declare(strict_types=1);

namespace CodeRhapsodie\ConnectorMistral;

use CodeRhapsodie\Bundle\ConnectorMistral\Client\MistralClient;
use CodeRhapsodie\Contracts\ConnectorMistral\ClientProviderInterface;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Symfony\Component\HttpClient\HttpClient;

final readonly class ClientProvider implements ClientProviderInterface
{
    public function __construct(private ConfigResolverInterface $configResolver)
    {
    }

    public function getClient(): MistralClient
    {
        $client = HttpClient::createForBaseUri('https://api.mistral.ai', [
            'headers' => [
                'authorization' => 'Bearer ' . $this->configResolver->getParameter('connector_mistral.mistral.api_key'),
                'Content-Type' => 'application/json',
            ]]);
        return MistralClient::generateClient($client);
    }
}

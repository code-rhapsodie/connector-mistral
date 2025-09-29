<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace CodeRhapsodie\Tests\Bundle\ConnectorMistral\DependencyInjection\Configuration\SiteAccessAware;

use CodeRhapsodie\Bundle\ConnectorMistral\DependencyInjection\Configuration\SiteAccessAware\ConnectorMistralParser;
use Ibexa\Bundle\Core\DependencyInjection\IbexaCoreExtension;
use Ibexa\Tests\Bundle\Core\DependencyInjection\Configuration\Parser\AbstractParserTestCase;

final class ConnectorMistralParserTest extends AbstractParserTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new IbexaCoreExtension(
                [
                    new ConnectorMistralParser(),
                ]
            ),
        ];
    }

    public function testEmptyConfiguration(): void
    {
        $this->load($this->buildConfiguration([]));

        $this->assertConfigResolverParameterIsNotSet('mistral.api_key', 'ibexa_demo_site');
    }

    public function testMistralConfiguration(): void
    {
        $config = $this->buildConfiguration([
            'mistral' => ['api_key' => '1!2@3#'],
        ]);
        $this->load($config);

        $this->assertConfigResolverParameterValue(
            'connector_mistral.mistral.api_key',
            '1!2@3#',
            'ibexa_demo_site'
        );
    }

    /**
     * @param array<string, mixed> $config
     *
     * @return array<string, array<mixed>>
     */
    private function buildConfiguration(array $config): array
    {
        return [
            'system' => [
                'ibexa_demo_site' => [
                    'connector_mistral' => $config,
                ],
            ],
        ];
    }

    private function assertConfigResolverParameterIsNotSet(string $parameterName, ?string $scope = null): void
    {
        $chainConfigResolver = $this->getConfigResolver();
        self::assertFalse(
            $chainConfigResolver->hasParameter($parameterName, 'ibexa.site_access.config', $scope),
            sprintf('Parameter "%s" should not exist in scope "%s"', $parameterName, $scope)
        );
    }
}

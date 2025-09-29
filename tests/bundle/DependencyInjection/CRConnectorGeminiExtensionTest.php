<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace CodeRhapsodie\Tests\Bundle\ConnectorMistral\DependencyInjection;

use CodeRhapsodie\Bundle\ConnectorMistral\DependencyInjection\CRConnectorMistralExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

final class CRConnectorMistralExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new CRConnectorMistralExtension(),
        ];
    }

    public function testDefaultConfiguration(): void
    {
        $this->load();

        $this->assertParameter([
            'mistral-medium-latest' => 'Mistral Medium',
            'mistral-small-latest' => 'Mistral Small',
        ], 'text_to_text', 'models');
        $this->assertParameter('mistral-small-latest', 'text_to_text', 'default_model');
        $this->assertParameter(4096, 'text_to_text', 'default_max_tokens');
        $this->assertParameter(1.0, 'text_to_text', 'default_temperature');

        $this->assertParameter([
            'mistral-medium-latest' => 'Mistral Medium',
            'mistral-small-latest' => 'Mistral Small',
        ], 'image_to_text', 'models');
        $this->assertParameter('mistral-small-latest', 'image_to_text', 'default_model');
        $this->assertParameter(4096, 'image_to_text', 'default_max_tokens');
        $this->assertParameter(1.0, 'image_to_text', 'default_temperature');
    }

    public function testCustomConfiguration(): void
    {
        $this->load([
            'text_to_text' => [
                'models' => [
                    'mistral-small-latest' => 'Mistral Small',
                ],
                'default_model' => 'mistral-small-latest',
                'default_max_tokens' => 1000,
                'default_temperature' => 0.5,
            ],
            'image_to_text' => [
                'models' => [
                    'mistral-medium-latest' => 'Mistral Medium',
                    'mistral-small-latest' => 'Mistral Small',
                ],
                'default_model' => 'mistral-small-latest',
                'default_max_tokens' => 2048,
                'default_temperature' => 2.0,
            ],
        ]);

        $this->assertParameter([
            'mistral-small-latest' => 'Mistral Small',
        ], 'text_to_text', 'models');
        $this->assertParameter('mistral-small-latest', 'text_to_text', 'default_model');
        $this->assertParameter(1000, 'text_to_text', 'default_max_tokens');
        $this->assertParameter(0.5, 'text_to_text', 'default_temperature');

        $this->assertParameter([
            'mistral-medium-latest' => 'Mistral Medium',
            'mistral-small-latest' => 'Mistral Small',
        ], 'image_to_text', 'models');
        $this->assertParameter('mistral-small-latest', 'image_to_text', 'default_model');
        $this->assertParameter(2048, 'image_to_text', 'default_max_tokens');
        $this->assertParameter(2.0, 'image_to_text', 'default_temperature');
    }

    /**
     * @param mixed $expectedValue
     */
    private function assertParameter($expectedValue, string $actionType, string $name): void
    {
        $key = sprintf('cr.connector_mistral.%s.%s', $actionType, $name);

        self::assertEquals($expectedValue, $this->container->getParameter($key));
    }
}

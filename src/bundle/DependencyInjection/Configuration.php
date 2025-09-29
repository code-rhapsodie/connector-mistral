<?php

declare(strict_types=1);

namespace CodeRhapsodie\Bundle\ConnectorMistral\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder(CRConnectorMistralExtension::EXTENSION_NAME);

        $rootNode = $builder->getRootNode();
        $rootNode
            ->children()
                ->append($this->getActionConfigurationNode('text_to_text', [
                    'mistral-small-latest' => 'Mistral Small',
                    'mistral-medium-latest' => 'Mistral Medium',
                    'mistral-large-latest' => 'Mistral Large',
                ]))
                ->append($this->getActionConfigurationNode('image_to_text', [
                    'mistral-ocr-latest' => 'Mistral OCR',
                    'magistral-medium-latest' => 'Magistral Medium',
                ]))
            ->end();

        return $builder;
    }

    /**
     * @param array<string, string> $models
     */
    private function getActionConfigurationNode(string $name, array $models): NodeDefinition
    {
        $builder = new TreeBuilder($name);

        $rootNode = $builder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->variableNode('models')
                    ->isRequired()
                    ->defaultValue($models)
                ->end()
                ->scalarNode('default_model')
                    ->isRequired()
                    ->defaultValue('mistral-small-latest')
                    ->info('Default model identifier.')
                ->end()
                ->integerNode('default_max_tokens')
                    ->isRequired()
                    ->defaultValue(4096)
                    ->info('Default maximum number of tokens that can be generated in the chat completion.')
                ->end()
                ->floatNode('default_temperature')
                    ->isRequired()
                    ->defaultValue(0.8)
                    ->min(0.0)
                    ->max(2.0)
                    ->info('Default sampling temperature to use, between 0 and 2. Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic.')
                ->end()
            ->end();

        return $rootNode;
    }
}

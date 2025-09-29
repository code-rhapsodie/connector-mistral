<?php

declare(strict_types=1);

namespace CodeRhapsodie\Bundle\ConnectorMistral;

use CodeRhapsodie\Bundle\ConnectorMistral\DependencyInjection\Configuration\SiteAccessAware\ConnectorMistralParser;
use CodeRhapsodie\Bundle\ConnectorMistral\DependencyInjection\CRConnectorMistralExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class CRConnectorMistralBundle extends Bundle
{
    #[\Override]
    public function getContainerExtension(): ExtensionInterface
    {
        return new CRConnectorMistralExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        /** @var \Ibexa\Bundle\Core\DependencyInjection\IbexaCoreExtension $ibexaExtension */
        $ibexaExtension = $container->getExtension('ibexa');
        $ibexaExtension->addConfigParser(new ConnectorMistralParser());
        $ibexaExtension->addDefaultSettings(__DIR__ . '/Resources/config', ['default_settings.yaml']);
    }
}

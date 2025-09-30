<?php

declare(strict_types=1);

namespace CodeRhapsodie\ConnectorMistral\ActionHandler;

use CodeRhapsodie\Contracts\ConnectorMistral\ClientProviderInterface;
use Ibexa\Contracts\ConnectorAi\Action\DataType\Text;
use Ibexa\Contracts\ConnectorAi\Action\Response\TextResponse;
use Ibexa\Contracts\ConnectorAi\Action\TextToText\Action as TextToTextAction;
use Ibexa\Contracts\ConnectorAi\ActionInterface;
use Ibexa\Contracts\ConnectorAi\ActionResponseInterface;
use Ibexa\Contracts\ConnectorAi\ActionType\ActionTypeRegistryInterface;
use Ibexa\Contracts\ConnectorAi\PromptResolverInterface;
use Ibexa\Contracts\Core\Repository\LanguageResolver;
use Ibexa\Contracts\Core\Repository\LanguageService;

final class TextToTextActionHandler extends AbstractActionHandler
{
    use ResponseFormatter;

    public const string INDEX = 'mistral-text-to-text';

    public function __construct(
        ClientProviderInterface $clientProvider,
        ActionTypeRegistryInterface $actionTypeRegistry,
        LanguageService $languageService,
        LanguageResolver $languageResolver,
        private PromptResolverInterface $promptResolver
    ) {
        parent::__construct($clientProvider, $actionTypeRegistry, $languageService, $languageResolver);
    }

    public function supports(ActionInterface $action): bool
    {
        return $action instanceof TextToTextAction;
    }

    /**
     * @param \Ibexa\Contracts\ConnectorAi\Action\TextToText\Action $action
     */
    public function handle(ActionInterface $action, array $context = []): ActionResponseInterface
    {
        $options = $this->resolveOptions($action);

        $data = $this->client->generate(
            [
                [
                    'role' => 'user',
                    'content' => $this->promptResolver->getPrompt($options),
                ]
            ],
            [
                'model' => $options['model'],
                'temperature' => $options['temperature'],
                'max_tokens' => $options['max_tokens'],
                'safe_prompt' => false,
                'random_seed' => null,
                'stream'=> false,
                'response_format' => [
                    'type' => 'json_object'
                ]
            ]
        );

        return new TextResponse(new Text($this->format($data)));
    }

    public static function getIdentifier(): string
    {
        return self::INDEX;
    }
}

<?php

declare(strict_types=1);

namespace CodeRhapsodie\ConnectorMistral\ActionHandler;

use CodeRhapsodie\Contracts\ConnectorMistral\ClientProviderInterface;
use Ibexa\Contracts\ConnectorAi\Action\DataType\Text;
use Ibexa\Contracts\ConnectorAi\Action\ImageToText\Action as ImageToTextAction;
use Ibexa\Contracts\ConnectorAi\Action\Response\TextResponse;
use Ibexa\Contracts\ConnectorAi\ActionInterface;
use Ibexa\Contracts\ConnectorAi\ActionResponseInterface;
use Ibexa\Contracts\ConnectorAi\ActionType\ActionTypeRegistryInterface;
use Ibexa\Contracts\ConnectorAi\PromptResolverInterface;
use Ibexa\Contracts\Core\Repository\LanguageResolver;
use Ibexa\Contracts\Core\Repository\LanguageService;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ImageToTextActionHandler extends AbstractActionHandler
{
    use ResponseFormatter;

    public const string INDEX = 'mistral-image-to-text';

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
        return $action instanceof ImageToTextAction;
    }

    /**
     * @param \Ibexa\Contracts\ConnectorAi\Action\ImageToText\Action $action
     */
    public function handle(ActionInterface $action, array $context = []): ActionResponseInterface
    {
        $options = $this->resolveOptions($action);

        preg_match('#data:(image/[a-z-+]+);base64,([a-zA-Z0-9,+/]+={0,2})#', $action->getInput()->getBase64(),
            $matches);

        if (\count($matches) !== 3) {
            throw new \Exception('Invalid image data');
        }

        $data = $this->client->generate(
            [
                [
                    "role" => "user",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => $this->promptResolver->getPrompt($options)
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => $action->getInput()->getBase64()
                        ]
                    ],
                ]
            ],
            [
                'model' => $options['model'],
                'temperature' => $options['temperature'],
                'top_p' => 1,
                'max_tokens' => $options['max_tokens'],
                'safe_prompt' => false,
                'random_seed' => null,
                'response_format' => [
                    'type' => 'json_object'
                ]
            ]);
        return new TextResponse(new Text($this->format($data)));
    }

    public static function getIdentifier(): string
    {
        return self::INDEX;
    }

    #[\Override]
    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->define('max_length')->allowedTypes('int');
    }
}

<?php

declare(strict_types=1);

namespace CodeRhapsodie\ConnectorMistral\ActionHandler;

use RuntimeException;

trait ResponseFormatter
{
    /**
     * @param array<mixed> $data
     *
     * @return non-empty-array<string>
     */
    public function format(array $data): array
    {
        if (!isset($data['choices'][0]['message']['content'])) {
            throw new RuntimeException('Unable to create response from response data.');
        }

        return [$data['choices'][0]['message']['content']];
    }
}

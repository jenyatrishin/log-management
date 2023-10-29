<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Model\File;

use Jentry\LogsManagement\Api\ArchiveNameProviderInterface;

class ArchiveNameProvider implements ArchiveNameProviderInterface
{
    public function __construct(
        private readonly string $extension
    ) {
    }

    public function buildArchiveName(string $fileName): string
    {
        $today = (new \DateTime())->format('d-m-Y');

        return $fileName . '-' . $today . '.' . $this->extension;
    }
}

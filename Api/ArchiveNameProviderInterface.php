<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Api;

interface ArchiveNameProviderInterface
{
    public function buildArchiveName(string $fileName): string;
}

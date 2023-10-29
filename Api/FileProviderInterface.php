<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Api;

interface FileProviderInterface
{
    public function getFilesList(): array;

    public function getFilePathByName(string $fileName): string;

    public function getFileLinesCount(string $fileName): int;
}

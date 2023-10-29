<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Api;

interface FileReaderInterface
{
    public function readFileByName(string $fileName, int $startLine = 1, int $endLine = 100): iterable;
}

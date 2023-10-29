<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Model;

use Jentry\LogsManagement\Api\FileReaderInterface;
use SplFileObject;
use LimitIterator;

class FileReader implements FileReaderInterface
{
    public function readFileByName(string $fileName, int $startLine = 1, int $endLine = 100): array
    {
        $output = [];

        $limit = $endLine - $startLine;

        $iter =  new LimitIterator(
            new SplFileObject($fileName),
            $startLine,
            $limit
        );

        foreach ($iter as $line) {
            $output[] = $line;
        }

        return $output;
    }
}

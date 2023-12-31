<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Model;

use Jentry\LogsManagement\Api\FileReaderInterface;
use Jentry\LogsManagement\Api\FileSearchInterface;
use SplFileObject;
use LimitIterator;

class FileReader implements FileReaderInterface, FileSearchInterface
{
    /**
     * Read file segment by name
     *
     * @param string $fileName
     * @param int $startLine
     * @param int $endLine
     *
     * @return array
     */
    public function readFileByName(string $fileName, int $startLine = 0, int $endLine = 100): iterable
    {
        $limit = $endLine - $startLine;

        $iter =  new LimitIterator(
            new SplFileObject($fileName),
            $startLine,
            $limit
        );

        foreach ($iter as $line) {
            yield $line;
        }
    }

    /**
     * Search in file by pattern string
     *
     * @param string $fileName
     * @param string $searchText
     *
     * @return iterable
     */
    public function search(string $fileName, string $searchText): iterable
    {
        foreach ($this->readFileByName($fileName, 0, -1) as $fileLine) {
            if (stripos($fileLine, $searchText) !== false) {
                yield $fileLine;
            }
        }
    }
}

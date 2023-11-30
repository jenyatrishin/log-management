<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Api;

interface FileReaderInterface
{
    /**
     * Read file segment by its name
     *
     * @param string $fileName
     * @param int $startLine
     * @param int $endLine
     *
     * @return iterable
     */
    public function readFileByName(string $fileName, int $startLine, int $endLine): iterable;
}

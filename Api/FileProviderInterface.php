<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Api;

use Magento\Framework\Exception\FileSystemException;

interface FileProviderInterface
{
    /**
     * Retrieve files list
     *
     * @return array
     * @throws FileSystemException
     */
    public function getFilesList(): array;

    /**
     * Retrieve file path by name
     *
     * @param string $fileName
     *
     * @return string
     * @throws FileSystemException
     */
    public function getFilePathByName(string $fileName): string;

    /**
     * Retrieve file lines count
     *
     * @param string $fileName
     *
     * @return int
     * @throws FileSystemException
     */
    public function getFileLinesCount(string $fileName): int;
}

<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Api;

interface FileSearchInterface
{
    /**
     * Search in file by pattern string
     *
     * @param string $fileName
     * @param string $searchText
     *
     * @return iterable
     */
    public function search(string $fileName, string $searchText): iterable;
}

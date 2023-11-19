<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Api;

interface ArchiveNameProviderInterface
{
    /**
     * Build archive name for provided file
     *
     * @param string $fileName
     *
     * @return string
     */
    public function buildArchiveName(string $fileName): string;
}

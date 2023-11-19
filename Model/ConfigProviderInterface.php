<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Model;

interface ConfigProviderInterface
{
    /**
     * Check if cron archiving is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Retrieve archive folder path
     *
     * @return string|null
     */
    public function getArchiveFolderPath(): string|null;

    /**
     * Retrieve file lines count to show
     *
     * @return int
     */
    public function getFileLinesCount(): int;
}

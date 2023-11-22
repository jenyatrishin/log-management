<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Test\Unit\Model;

use Jentry\LogsManagement\Model\ConfigProviderInterface;

class StaticConfigProvider implements ConfigProviderInterface
{
    /**
     * @param array $configValues
     */
    public function __construct(
        private readonly array $configValues
    ) {
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->configValues['enabled'];
    }

    /**
     * @return string|null
     */
    public function getArchiveFolderPath(): string|null
    {
        return $this->configValues['archive_folder_path'];
    }

    /**
     * @return int
     */
    public function getFileLinesCount(): int
    {
        return $this->configValues['lines_count'];
    }
}

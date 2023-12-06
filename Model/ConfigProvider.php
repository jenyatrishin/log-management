<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Model;

use Jentry\LogsManagement\Api\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * Config paths
     */
    private const XML_PATH_LOGS_MANAGEMENT_LINES_COUNT = 'logs/general/lines_count';
    private const XML_PATH_LOGS_MANAGEMENT_ARCHIVE_FOLDER = 'logs/logs_cron/archive_folder';
    private const XML_PATH_LOGS_MANAGEMENT_CRON_ENABLED = 'logs/logs_cron/enabled';
    private const XML_PATH_LOGS_MANAGEMENT_CRON_SCHEDULE = 'logs/logs_cron/cron_schedule';

    // @codingStandardsIgnoreStart
    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }
    // @codingStandardsIgnoreEnd

    /**
     * Check if cron archiving is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_LOGS_MANAGEMENT_CRON_ENABLED);
    }

    /**
     * Retrieve archive folder path
     *
     * @return string|null
     */
    public function getArchiveFolderPath(): string|null
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LOGS_MANAGEMENT_ARCHIVE_FOLDER);
    }

    /**
     * Retrieve cron schedule
     *
     * @return string|null
     */
    public function getCronSchedule(): string|null
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LOGS_MANAGEMENT_CRON_SCHEDULE);
    }

    /**
     * Retrieve file lines count to show
     *
     * @return int
     */
    public function getFileLinesCount(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_LOGS_MANAGEMENT_LINES_COUNT);
    }
}

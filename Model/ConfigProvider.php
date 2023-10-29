<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider
{
    private const XML_PATH_LOGS_MANAGEMENT_LINES_COUNT = 'logs/general/lines_count';
    private const XML_PATH_LOGS_MANAGEMENT_ARCHIVE_FOLDER = 'logs/logs_cron/archive_folder';
    private const XML_PATH_LOGS_MANAGEMENT_CRON_ENABLED = 'logs/logs_cron/enabled';
    private const XML_PATH_LOGS_MANAGEMENT_CRON_SCHEDULE = 'logs/logs_cron/cron_schedule';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_LOGS_MANAGEMENT_CRON_ENABLED);
    }

    public function getArchiveFolderPath(): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LOGS_MANAGEMENT_ARCHIVE_FOLDER);
    }

    public function getCronSchedule(): string|null
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LOGS_MANAGEMENT_CRON_SCHEDULE);
    }

    public function getFileLinesCount(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_LOGS_MANAGEMENT_LINES_COUNT);
    }
}

<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Cron;

use Jentry\LogsManagement\Model\ConfigProvider;
use Jentry\LogsManagement\Model\FileArchiver;
use Magento\Framework\Exception\FileSystemException;

class Archiving
{
    // @codingStandardsIgnoreStart
    /**
     * @param ConfigProvider $configProvider
     * @param FileArchiver $fileArchiver
     */
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly FileArchiver $fileArchiver
    ) {
    }
    // @codingStandardsIgnoreEnd

    /**
     * Archive log files
     *
     * @return void
     * @throws FileSystemException
     */
    public function execute(): void
    {
        if ($this->configProvider->isEnabled()) {
            $this->fileArchiver->archiveLogFiles();
        }
    }
}

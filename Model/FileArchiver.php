<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Model;

use Jentry\LogsManagement\Api\FileProviderInterface;
use Magento\Framework\Archive;
use Magento\Framework\Filesystem\Driver\File;
use Jentry\LogsManagement\Api\ArchiveNameProviderInterface;
use Magento\Framework\Exception\FileSystemException;

class FileArchiver
{
    /**
     * @param ConfigProviderInterface $configProvider
     * @param FileProviderInterface $fileProvider
     * @param Archive $archive
     * @param File $file
     * @param ArchiveNameProviderInterface $archiveNameProvider
     */
    public function __construct(
        private readonly ConfigProviderInterface $configProvider,
        private readonly FileProviderInterface $fileProvider,
        private readonly Archive $archive,
        private readonly File $file,
        private readonly ArchiveNameProviderInterface $archiveNameProvider
    ) {
    }

    /**
     * Archive and remove log files
     *
     * @return void
     * @throws FileSystemException
     */
    public function archiveLogFiles(): void
    {
        if (!$this->isArchiveFolderExists()) {
            $this->createArchiveFolder();
        }
        foreach ($this->fileProvider->getFilesList() as $file) {
            $path = $this->fileProvider->getFilePathByName($file['name']);
            $newName = $this->archiveNameProvider->buildArchiveName($file['name']);

            $this->archive->pack($path, $this->configProvider->getArchiveFolderPath() . DIRECTORY_SEPARATOR . $newName);
            $this->file->deleteFile($path);
        }
    }

    /**
     * Check if archive folder exists
     *
     * @return bool
     * @throws FileSystemException
     */
    private function isArchiveFolderExists(): bool
    {
        return $this->file->isExists($this->configProvider->getArchiveFolderPath());
    }

    /**
     * Create archive folder
     *
     * @return void
     * @throws FileSystemException
     */
    private function createArchiveFolder(): void
    {
        $this->file->createDirectory($this->configProvider->getArchiveFolderPath());
    }
}

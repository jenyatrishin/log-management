<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Cron;

use Jentry\LogsManagement\Model\ConfigProvider;
use Jentry\LogsManagement\Api\FileProviderInterface;
use Magento\Framework\Archive;
use Magento\Framework\Filesystem\Driver\File;
use Jentry\LogsManagement\Api\ArchiveNameProviderInterface;

class Archiving
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly FileProviderInterface $fileProvider,
        private readonly Archive $archive,
        private readonly File $file,
        private readonly ArchiveNameProviderInterface $archiveNameProvider
    ) {
    }

    public function execute(): void
    {
        if ($this->configProvider->isEnabled()) {
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
    }

    private function isArchiveFolderExists(): bool
    {
        return $this->file->isExists($this->configProvider->getArchiveFolderPath());
    }

    private function createArchiveFolder(): void
    {
        $this->file->createDirectory($this->configProvider->getArchiveFolderPath());
    }
}

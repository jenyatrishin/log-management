<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Model\File;

use Jentry\LogsManagement\Api\FileProviderInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Io\File as FileDriver;
use Psr\Log\LoggerInterface;
use SplFileObject;

class FileProvider implements FileProviderInterface
{
    // @codingStandardsIgnoreStart
    /**
     * @param DirectoryList $directoryList
     * @param LoggerInterface $logger
     * @param FileDriver $fileDriver
     * @param string $folderName
     * @param string $varFolderCode
     */
    public function __construct(
        private readonly DirectoryList $directoryList,
        private readonly LoggerInterface $logger,
        private readonly FileDriver $fileDriver,
        private readonly string $folderName = 'log',
        private readonly string $varFolderCode = 'var'
    ) {
    }
    // @codingStandardsIgnoreEnd

    /**
     * Retrieve file path by name
     *
     * @param string $fileName
     *
     * @return string
     * @throws FileSystemException
     */
    public function getFilePathByName(string $fileName): string
    {
        $path = $this->directoryList->getPath($this->varFolderCode) . DIRECTORY_SEPARATOR . $this->folderName;

        return $path . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * Retrieve files list
     *
     * @return array
     */
    public function getFilesList(): array
    {
        $output = [];
        try {
            $path = $this->directoryList->getPath($this->varFolderCode) . DIRECTORY_SEPARATOR . $this->folderName;

            $this->fileDriver->cd($path);

            foreach ($this->fileDriver->ls() as $folderElement) {
                $fileType = $folderElement['filetype'] ?? null;
                if ($fileType !== 'log') {
                    continue;
                }
                $output[$folderElement['text']] = [
                    'id' => $folderElement['text'],
                    'name' => $folderElement['text'],
                    'size' => $folderElement['size']/pow(1024, 2) . ' MB',
                    'edited' => $folderElement['mod_date']
                ];
            }
        } catch (LocalizedException $e) {
            $this->logger->error(__('Retrieve files list error: %1', $e->getMessage()));
        }

        return $output;
    }

    /**
     * Retrieve file lines count
     *
     * @param string $fileName
     *
     * @return int
     * @throws FileSystemException
     */
    public function getFileLinesCount(string $fileName): int
    {
        $filePath = $this->getFilePathByName($fileName);
        $file = new SplFileObject($filePath, 'r');
        $file->seek(PHP_INT_MAX);

        return $file->key();
    }
}

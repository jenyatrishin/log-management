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
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Exception\FileSystemException;

class FileProvider implements FileProviderInterface
{
    /**
     * @param DirectoryList $directoryList
     * @param File $file
     * @param string $folderName
     * @param string $varFolderCode
     */
    public function __construct(
        private readonly DirectoryList $directoryList,
        private readonly File $file,
        private readonly string $folderName = 'log',
        private readonly string $varFolderCode = 'var'
    ) {
    }

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
     * @throws FileSystemException
     */
    public function getFilesList(): array
    {
        $path = $this->directoryList->getPath($this->varFolderCode) . DIRECTORY_SEPARATOR . $this->folderName;
        $paths =  $this->file->readDirectory($path);

        $files = array_filter(
            $paths,
            fn (string $element) => $this->file->isFile($element)
        );

        return $this->prepareFilesData($files);
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
        $file = new \SplFileObject($filePath, 'r');
        $file->seek(PHP_INT_MAX);

        return $file->key();
    }

    /**
     * Prepare files data
     *
     * @param array $fileNames
     *
     * @return array
     */
    private function prepareFilesData(array $fileNames): array
    {
        $output = [];
        foreach ($fileNames as $fileName) {
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            if ($extension !== 'log') {
                continue;
            }
            $pathParts = explode('/', $fileName);
            $name = $pathParts[count($pathParts) - 1];
            $output[$name] = [
                'id' => $name,
                'name' => $name,
                'size' => filesize($fileName)/pow(1024, 2) . ' MB',
                'edited' => date ("F d Y H:i:s.", filemtime($fileName))
            ];
        }

        return $output;
    }
}

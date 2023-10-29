<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Model\File;

use Jentry\LogsManagement\Api\FileProviderInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;

class FileProvider implements FileProviderInterface
{
    public function __construct(
        private readonly DirectoryList $directoryList,
        private readonly File $file,
        private readonly string $folderName = 'log'
    ) {
    }

    public function getFilePathByName(string $fileName): string
    {
        $path = $this->directoryList->getPath('var') . DIRECTORY_SEPARATOR . $this->folderName;

        return $path . DIRECTORY_SEPARATOR . $fileName;
    }

    public function getFilesList(): array
    {
        $path = $this->directoryList->getPath('var') . DIRECTORY_SEPARATOR . $this->folderName;
        $paths =  $this->file->readDirectory($path);

        $files = array_filter(
            $paths,
            fn (string $element) => $this->file->isFile($element)
        );

        return $this->prepareFilesData($files);
    }

    public function getFileLinesCount(string $fileName): int
    {
        $filePath = $this->getFilePathByName($fileName);
        $file = new \SplFileObject($filePath, 'r');
        $file->seek(PHP_INT_MAX);

        return $file->key();
    }

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

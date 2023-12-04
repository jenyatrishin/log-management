<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Test\Unit\Model\File;

use PHPUnit\Framework\TestCase;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Jentry\LogsManagement\Api\FileProviderInterface;
use Jentry\LogsManagement\Model\File\FileProvider;
use Magento\Framework\Exception\FileSystemException;
use Psr\Log\NullLogger;
use Magento\Framework\Filesystem\Io\File as FileDriver;
use Magento\Framework\File\Size;

class FileProviderTest extends TestCase
{
    /**
     * test log file content string
     */
    private const LINE_CONTENT = 'Test log string' . PHP_EOL;

    /**
     * @var File
     */
    private File $file;

    /**
     * @var FileProviderInterface
     */
    private FileProviderInterface $fileProvider;

    /**
     * @var string
     */
    private string $varFolderPath;

    /**
     * @return void
     * @throws FileSystemException
     */
    protected function setUp(): void
    {
        $customDirs = [
            DirectoryList::SYS_TMP => [
                DirectoryList::PATH => '/var/www/var',
                DirectoryList::URL_PATH => 'var'
            ]
        ];
        $directoryList = new DirectoryList('/var/www', $customDirs);
        $this->file = new File();

        $this->fileProvider = new FileProvider(
            $directoryList,
            (new NullLogger()),
            (new FileDriver()),
            (new Size()),
            'log_test',
            DirectoryList::SYS_TMP
        );
        $this->varFolderPath = $directoryList->getPath(DirectoryList::SYS_TMP);

        for ($i = 0; $i <= 110; $i++) {
            $this->file->filePutContents(
                $this->varFolderPath . DIRECTORY_SEPARATOR . 'log_test' . DIRECTORY_SEPARATOR . 'test1.log',
                self::LINE_CONTENT,
                FILE_APPEND
            );
        }
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    protected function tearDown(): void
    {
        $this->file->deleteFile(
            $this->varFolderPath . DIRECTORY_SEPARATOR . 'log_test' . DIRECTORY_SEPARATOR . 'test1.log'
        );
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    public function testGetFilePathByName(): void
    {
        $fileName = $this->fileProvider->getFilePathByName('test1.log');
        $this->assertInstanceOf(FileProviderInterface::class, $this->fileProvider);
        $this->assertEquals(
            $this->varFolderPath . DIRECTORY_SEPARATOR . 'log_test' . DIRECTORY_SEPARATOR . 'test1.log',
            $fileName
        );
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    public function testGetFilesList(): void
    {
        $files = $this->fileProvider->getFilesList();
        $this->assertCount(1, $files);
        $this->assertInstanceOf(FileProviderInterface::class, $this->fileProvider);
        $this->assertIsArray($files);
        $this->assertArrayHasKey('test1.log', $files);
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    public function testGetFileLinesCount(): void
    {
        $this->assertInstanceOf(FileProviderInterface::class, $this->fileProvider);
        $count = $this->fileProvider->getFileLinesCount('test1.log');
        $this->assertEquals(111, $count);
    }
}

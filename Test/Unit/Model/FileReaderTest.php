<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Magento\Framework\Filesystem\Driver\File;
use Jentry\LogsManagement\Api\FileReaderInterface;
use Jentry\LogsManagement\Model\FileReader;
use Magento\Framework\Exception\FileSystemException;

class FileReaderTest extends TestCase
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
     * @var FileReaderInterface
     */
    private FileReaderInterface $fileReader;

    /**
     * @var string
     */
    private string $path;

    /**
     * @return void
     * @throws FileSystemException
     */
    protected function setUp(): void
    {
        $this->fileReader = new FileReader();
        $this->file = new File();

        $this->path = '/var/www/var' . DIRECTORY_SEPARATOR . 'log_test' . DIRECTORY_SEPARATOR . 'test.log';

        for ($i = 0; $i <= 110; $i++) {
            $this->file->filePutContents($this->path, self::LINE_CONTENT, FILE_APPEND);
        }
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    protected function tearDown(): void
    {
        $this->file->deleteFile($this->path);
    }

    /**
     * @return void
     */
    public function testReadFileByName(): void
    {
        $res = $this->fileReader->readFileByName($this->path);

        $this->assertInstanceOf(FileReaderInterface::class, $this->fileReader);
        $this->assertIsIterable($res);
        $this->assertCount(100, $res);
        $this->assertEquals(self::LINE_CONTENT, $res[0]);
    }
}

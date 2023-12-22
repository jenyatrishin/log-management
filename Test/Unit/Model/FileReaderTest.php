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
use Iterator;
use Generator;

class FileReaderTest extends TestCase
{
    /**
     * test log file content string
     */
    private const LINE_CONTENT = 'Test log string' . PHP_EOL;
    private const SEARCH_LINE_CONTENT = 'log string';

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

        $this->path = realpath(__DIR__ . '/../../../../../../../var')
             . DIRECTORY_SEPARATOR . 'log_test' . DIRECTORY_SEPARATOR . 'test.log';

        for ($i = 0; $i <= 99; $i++) {
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
        /** @var iterable|Iterator $res */
        $res = $this->fileReader->readFileByName($this->path);
        $firstLine = $res->current();
        $this->assertInstanceOf(FileReaderInterface::class, $this->fileReader);
        $this->assertNotEmpty($res);
        $this->assertIsIterable($res);
        $this->assertCount(100, $res);
        $this->assertEquals(self::LINE_CONTENT, $firstLine);
    }

    /**
     * @return void
     */
    public function testSearch(): void
    {
        /** @var iterable|Generator $res */
        $res = $this->fileReader->search($this->path, self::SEARCH_LINE_CONTENT);
        $firstLine = $res->current();
        $this->assertIsIterable($res);
        $this->assertNotEmpty($res);
        $this->assertCount(100, iterator_to_array($res, false));
        $this->assertStringContainsString(self::SEARCH_LINE_CONTENT, $firstLine);
    }
}

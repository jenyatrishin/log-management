<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Test\Unit\Model;

use Magento\Framework\Filesystem\DirectoryList;
use PHPUnit\Framework\TestCase;
use Jentry\LogsManagement\Model\FileArchiver;
use Jentry\LogsManagement\Test\Unit\Model\StaticConfigProvider;
use Magento\Framework\Filesystem\Driver\File;
use Jentry\LogsManagement\Model\File\FileProvider;
use Magento\Framework\Archive;
use Jentry\LogsManagement\Model\File\ArchiveNameProvider;
use Magento\Framework\Exception\FileSystemException;

class FileArchiverTest extends TestCase
{
    /**
     * test log archive directory path
     */
    private const TEST_LOGS_DIRECTORY = '/var/www/var/log_archives_test';

    /**
     * @var FileArchiver
     */
    private FileArchiver $fileArchiver;

    /**
     * @return void
     * @throws FileSystemException
     */
    protected function setUp(): void
    {
        $config = new StaticConfigProvider([
            'enabled' => true,
            'archive_folder_path' => self::TEST_LOGS_DIRECTORY,
            'lines_count' => 100
        ]);
        $customDirs = [DirectoryList::SYS_TMP => [DirectoryList::PATH => '/var/www/var', DirectoryList::URL_PATH => 'var']];
        $directoryList = new DirectoryList('/var/www', $customDirs);
        $file = new File();
        $fileProvider = new FileProvider($directoryList, $file, 'log_test', DirectoryList::SYS_TMP);
        $archive = new Archive();
        $archiveNameProvider = new ArchiveNameProvider('tgz');

        $this->fileArchiver = new FileArchiver($config, $fileProvider, $archive, $file, $archiveNameProvider);
        $logFolderPath = $directoryList->getPath(DirectoryList::SYS_TMP);

        //create file to archive
        for ($i = 0; $i <= 110; $i++) {
            $file->filePutContents($logFolderPath . '/log_test/test2.log', "Test" . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    protected function tearDown(): void
    {
        $file = new File();
        $file->deleteDirectory(self::TEST_LOGS_DIRECTORY);
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    public function testArchiveLogFiles(): void
    {
        $today = (new \DateTime())->format('d-m-Y');
        $this->fileArchiver->archiveLogFiles();
        $this->assertDirectoryExists(self::TEST_LOGS_DIRECTORY);
        $this->assertFileExists(
            self::TEST_LOGS_DIRECTORY . DIRECTORY_SEPARATOR . 'test2.log-' . $today . '.tgz'
        );
    }
}

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
use Jentry\LogsManagement\Model\File\ArchiveNameProvider;
use Jentry\LogsManagement\Api\ArchiveNameProviderInterface;
use DateTime;

class ArchiveNameProviderTest extends TestCase
{
    /**
     * @var ArchiveNameProviderInterface
     */
    private ArchiveNameProviderInterface $archiveNameProvider;

    /**
     * @var ArchiveNameProviderInterface
     */
    private ArchiveNameProviderInterface $zipNameProvider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->archiveNameProvider = new ArchiveNameProvider('tgz');
        $this->zipNameProvider = new ArchiveNameProvider('zip');
    }

    /**
     * @return void
     */
    public function testBuildArchiveName(): void
    {
        $this->assertInstanceOf(ArchiveNameProviderInterface::class, $this->archiveNameProvider);
        $this->assertInstanceOf(ArchiveNameProviderInterface::class, $this->zipNameProvider);

        $name = $this->archiveNameProvider->buildArchiveName('own_test');
        $this->assertEquals('own_test-' . (new DateTime())->format('d-m-Y') . '.tgz', $name);

        $name = $this->zipNameProvider->buildArchiveName('own_test_zip');
        $this->assertEquals('own_test_zip-' . (new DateTime())->format('d-m-Y') . '.zip', $name);
    }
}

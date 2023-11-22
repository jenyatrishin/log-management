<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Model\File;

use Jentry\LogsManagement\Api\ArchiveNameProviderInterface;
use DateTime;

class ArchiveNameProvider implements ArchiveNameProviderInterface
{
    // @codingStandardsIgnoreStart
    /**
     * @param string $extension
     */
    public function __construct(
        private readonly string $extension
    ) {
    }
    // @codingStandardsIgnoreEnd

    /**
     * Create archive name for file
     *
     * @param string $fileName
     *
     * @return string
     */
    public function buildArchiveName(string $fileName): string
    {
        $today = (new DateTime())->format('d-m-Y');

        return $fileName . '-' . $today . '.' . $this->extension;
    }
}

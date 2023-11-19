<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

declare(strict_types=1);

namespace Jentry\LogsManagement\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Jentry\LogsManagement\Api\FileProviderInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Jentry\LogsManagement\Model\ConfigProvider;

class View extends Template
{
    /**
     * @param Template\Context $context
     * @param FileProviderInterface $fileProvider
     * @param ConfigProvider $configProvider
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        Template\Context $context,
        private readonly FileProviderInterface $fileProvider,
        private readonly ConfigProvider $configProvider,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    /**
     * Retrieve file name from request
     *
     * @return string|null
     */
    public function getFileName(): string|null
    {
        return $this->_request->getParam('id');
    }

    /**
     * Retrieve lines count to show per page
     *
     * @return int
     */
    public function getLinesToShow(): int
    {
        return $this->configProvider->getFileLinesCount();
    }

    /**
     * Retrieve file lines total count
     *
     * @return int
     */
    public function getFileTotalLines(): int
    {
        return $this->fileProvider->getFileLinesCount($this->getFileName());
    }
}

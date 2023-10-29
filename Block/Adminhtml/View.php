<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Jentry\LogsManagement\Api\FileProviderInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Jentry\LogsManagement\Model\ConfigProvider;

class View extends Template
{
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

    public function getFileName(): string|null
    {
        return $this->_request->getParam('id');
    }

    public function getLinesToShow(): int
    {
        return $this->configProvider->getFileLinesCount();
    }

    public function getFileTotalLines(): int
    {
        return $this->fileProvider->getFileLinesCount($this->getFileName());
    }
}

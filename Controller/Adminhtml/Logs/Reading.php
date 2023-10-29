<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Controller\Adminhtml\Logs;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\ResultFactory;
use Jentry\LogsManagement\Model\ConfigProvider;
use Jentry\LogsManagement\Api\FileReaderInterface;
use Jentry\LogsManagement\Api\FileProviderInterface;

class Reading extends Action implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        private readonly ConfigProvider $configProvider,
        private readonly FileReaderInterface $fileReader,
        private readonly FileProviderInterface $fileProvider
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $linesToShow = $this->configProvider->getFileLinesCount();
        $fileName = $this->_request->getParam('file');
        $page = $this->_request->getParam('page');

        $startLine = $linesToShow * ($page - 1);
        $endLine = $linesToShow * $page;


        $result->setData([
            'content' => $this->fileReader->readFileByName(
                $this->fileProvider->getFilePathByName($fileName), $startLine, $endLine
            )
        ]);

        return $result;
    }
}

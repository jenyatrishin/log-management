<?php

/**
 * @package   Jentry_LogsManagement
 * @author    Yevhenii Trishyn
 * @copyright Copyright (c) Yevhenii Trishyn (https://github.com/jenyatrishin)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */

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
use Magento\Framework\Exception\FileSystemException;
use Psr\Log\LoggerInterface;
use Magento\Framework\Escaper;

class Reading extends Action implements HttpPostActionInterface
{
    /**
     * @param Context $context
     * @param ConfigProvider $configProvider
     * @param FileReaderInterface $fileReader
     * @param FileProviderInterface $fileProvider
     * @param LoggerInterface $logger
     * @param Escaper $escaper
     */
    public function __construct(
        Context $context,
        private readonly ConfigProvider $configProvider,
        private readonly FileReaderInterface $fileReader,
        private readonly FileProviderInterface $fileProvider,
        private readonly LoggerInterface $logger,
        private readonly Escaper $escaper
    ) {
        parent::__construct($context);
    }

    /**
     * Read file segment and return as json response
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $linesToShow = $this->configProvider->getFileLinesCount();
        $fileName = $this->_request->getParam('file');
        $page = $this->_request->getParam('page');

        $startLine = $linesToShow * ($page - 1);
        $endLine = $linesToShow * $page;

        try {
            $output = [];
            foreach ($this->fileReader->readFileByName(
                $this->fileProvider->getFilePathByName($fileName),
                $startLine,
                $endLine
            ) as $row) {
                $output[] = $this->escaper->escapeHtml($row);
            }
            $result->setData(['content' => $output]);
        } catch (FileSystemException $e) {
            $this->logger->error($e->getMessage());
            $result->setData([
                'content' => __('File can not be read')
            ]);
        }

        return $result;
    }
}

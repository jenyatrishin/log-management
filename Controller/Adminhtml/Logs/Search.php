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
use Jentry\LogsManagement\Api\FileProviderInterface;
use Jentry\LogsManagement\Api\FileSearchInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Escaper;
use Throwable;

class Search extends Action implements HttpPostActionInterface
{
    /**
     * @param Context $context
     * @param FileSearchInterface $fileReader
     * @param FileProviderInterface $fileProvider
     * @param LoggerInterface $logger
     * @param Escaper $escaper
     */
    public function __construct(
        Context $context,
        private readonly FileSearchInterface $fileReader,
        private readonly FileProviderInterface $fileProvider,
        private readonly LoggerInterface $logger,
        private readonly Escaper $escaper
    ) {
        parent::__construct($context);
    }

    /**
     * Search text in file
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $fileName = $this->fileProvider->getFilePathByName($this->_request->getParam('file'));
            $search = $this->_request->getParam('search');
            $output = [];
            foreach ($this->fileReader->search($fileName, $search) as $fileContentRow) {
                $output[] = $this->escaper->escapeHtml($fileContentRow);
            }
            $result->setData(['content' => $output]);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            $result->setData([
                'content' => __('File can not be read')
            ]);
        }

        return $result;
    }
}

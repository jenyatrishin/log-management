<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Controller\Adminhtml\Logs;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\ResultFactory;

class View extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Jentry_LogsManagement::logs';

    public function execute(): ResultInterface
    {
        $name = $this->getRequest()->getParam('id');

        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->getConfig()->getTitle()->prepend(__('Logs Management - %1', $name));

        return $page;
    }
}

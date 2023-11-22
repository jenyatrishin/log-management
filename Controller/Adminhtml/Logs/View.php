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
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\ResultFactory;

class View extends Action implements HttpGetActionInterface
{
    /**
     * Configuration acl resource
     */
    public const ADMIN_RESOURCE = 'Jentry_LogsManagement::logs';

    /**
     * Render view page
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $name = $this->getRequest()->getParam('id');

        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->getConfig()->getTitle()->prepend(__('Logs Management - %1', $name));

        return $page;
    }
}

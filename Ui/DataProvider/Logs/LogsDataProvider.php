<?php

declare(strict_types=1);

namespace Jentry\LogsManagement\Ui\DataProvider\Logs;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Jentry\LogsManagement\Api\FileProviderInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class LogsDataProvider extends DataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        private readonly FileProviderInterface $provider,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $files = $this->provider->getFilesList();

        return [
            'items' => array_values($files),
            'total' => count($files),
            'totalRecords' => count($files)
        ];
    }
}

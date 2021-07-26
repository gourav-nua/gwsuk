<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-related
 * @version   1.0.17
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Related\Ui\Block\Listing;

use Magento\Directory\Model\Currency;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\RequestInterface;
use Mirasvit\Related\Service\AnalyticsService;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    private $analyticsService;

    private $currency;

    public function __construct(
        AnalyticsService $analyticsService,
        Currency $currency,
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->analyticsService = $analyticsService;
        $this->currency         = $currency;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $reporting, $searchCriteriaBuilder, $request, $filterBuilder, $meta, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function searchResultToOutput(SearchResultInterface $searchResult)
    {
        $arrItems          = [];
        $arrItems['items'] = [];

        /** @var \Mirasvit\Related\Model\Block $item */
        foreach ($searchResult->getItems() as $item) {
            $itemData = $item->getData();

            $itemData['analytics'] = $this->analyticsHtml($item->getId());

            $arrItems['items'][] = $itemData;
        }
        $arrItems['totalRecords'] = $searchResult->getTotalCount();

        return $arrItems;
    }

    private function analyticsHtml($blockId)
    {
        $impression = round($this->analyticsService->getImpression($blockId));
        $clicks     = round($this->analyticsService->getClicks($blockId));
        $orders     = round($this->analyticsService->getOrders($blockId));


        $ctr = $impression > 0 ? round($clicks / $impression * 100, 1) : 0;


        $html = sprintf(
            '
            <div class="mst_related__analytics-html">
                <div><p>Impression <span>%s</span></p></div>
                <div><p>Clicks <span>%s<i>%s%%</i></span></p></div>
                <div><p>Orders <span>%s</span></p></div>
                <div><p>Revenue <span>%s</span></p></div>
            </div>',
            $impression,
            $clicks,
            $ctr,
            $orders,
            $this->currency->format($this->analyticsService->getRevenue($blockId))
        );

        return $html;
    }
}

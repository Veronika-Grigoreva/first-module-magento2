<?php

namespace First\Module\Controller\Search;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use First\Module\Model\SearchSku;

class Search extends Action
{
    /**
     * @var SearchSku
     */
    private $searchSku;

    /**
     * Search constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param SearchSku $searchSku
     */
    public function __construct(
        Context $context,
        SearchSku $searchSku
    ) {
        parent::__construct($context);
        $this->searchSku = $searchSku;
    }

    /**
     * @return bool|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        try {
            $sku = $this->getRequest()->getParam('skuInput');
            $sku    = $this->searchSku->getSkuCollection($sku);
            $result = $this->getResponse()->representJson(json_encode($sku->toArray()));
            return $result;
        } catch (NoSuchEntityException $e) {
            return false;
        }

}
}
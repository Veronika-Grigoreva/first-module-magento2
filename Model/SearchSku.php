<?php

namespace First\Module\Model;

use Magento\Catalog\Ui\DataProvider\Product\ProductCollectionFactory;
//use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\Action\Context;

class SearchSku
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var ProductCollectionFactory
     */
    private $collectionFactory;

    /**
     * SearchSku constructor.
     * @param Context $context
     * @param ProductCollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        ProductCollectionFactory $collectionFactory
    )
    {
        $this->context = $context;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param $sku
     * @return \Magento\Catalog\Ui\DataProvider\Product\ProductCollection
     */
    public function getSkuCollection($sku)
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect('SKU');
        $collection->getSelect()->reset('columns')->columns('sku');
        $collection->addAttributeToFilter('SKU', ["like" => $sku . "%"])->setPageSize(5);

        return $collection;
    }
}
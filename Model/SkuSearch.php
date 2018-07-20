<?php

namespace First\Module\Model;

use First\Module\Api\SkuSearchInterface;

class SkuSearch implements SkuSearchInterface
{
    /**
     * @var \First\Module\Model\SearchSku
     */
    private $searchSku;

    public function __construct(
        SearchSku $searchSku
    ) {
        $this->searchSku = $searchSku;
    }

    /**
     * {@inheritdoc}
     */
    public function skuSearch($sku)
    {
        $skuInput = $this->searchSku->getSkuCollection($sku);
        if ($skuInput) {
            return $skuInput->toArray();
        } else {
            return false;
        }
    }
}
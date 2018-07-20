<?php

namespace First\Module\Api;

interface SkuSearchInterface
{
    /**
     * @param string $sku
     * @return array
     * @throws \Exception
     */
    public function skuSearch($sku);

}
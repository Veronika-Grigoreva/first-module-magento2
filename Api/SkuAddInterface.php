<?php

namespace First\Module\Api;

interface SkuAddInterface
{
    const SKU = 0;
    const QUANTITY = 1;

    /**
     * @param string $skuInput
     * @return boolean
     * @throws \Exception
     */
    public function addSku($skuInput);
}
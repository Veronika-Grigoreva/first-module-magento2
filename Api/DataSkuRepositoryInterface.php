<?php

namespace First\Module\Api;


interface DataSkuRepositoryInterface
{
    /**
     * @param string $sku
     * @return boolean
     * @throws \Exception
     */
    public function delete($sku);

    /**
     * @param string $sku
     * @param int $qut
     * @return boolean
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Exception
     */
    public function update($sku, $qut);

}
<?php

namespace First\Module\Api\Data;


interface GridInterface
{
    const ENTITY_ID = 'entity_id';
    const SKU = 'sku';
    const QUANTITY = 'quantity';

    /**
     * Get EntityId.
     * @return int
     * @throws \Exception
     */
    public function getEntityId();

    /**
     * Set EntityId.
     * @param string $entityId
     * @return \First\Module\Api\Data\GridInterface
     */
    public function setEntityId($entityId);

    /**
     * Get Sku.
     * @return string
     * @throws \Exception
     */
    public function getSku();

    /**
     * Set Sku.
     * @param string $sku
     * @return \First\Module\Api\Data\GridInterface
     */
    public function setSku($sku);

    /**
     * Get Quantity.
     * @return string
     * @throws \Exception
     */
    public function getQuantity();

    /**
     * Set Quantity.
     * @param string $quantity
     * @return \First\Module\Api\Data\GridInterface
     */
    public function setQuantity($quantity);
}
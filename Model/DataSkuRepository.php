<?php

namespace First\Module\Model;

use First\Module\Api\DataSkuRepositoryInterface;
use First\Module\Model\ResourceModel\Grid as DataResource;

class DataSkuRepository implements DataSkuRepositoryInterface
{
    /**
     * @var Grid
     */
    private $resource;
    /**
     * @var GridFactory
     */
    private $gridFactory;


    /**
     * DataSkuRepository constructor.
     * @param DataResource $resource
     * @param GridFactory $gridFactory
     */
    public function __construct(
        DataResource $resource,
        GridFactory $gridFactory
    ) {
        $this->resource = $resource;
        $this->gridFactory = $gridFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($sku)
    {
        /** @var \First\Module\Model\Grid  $data */
        $data = $this->gridFactory->create();
        $this->resource->load($data, $sku, 'sku')
            ->delete($data);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function update($sku, $qut)
    {
        /** @var \First\Module\Model\Grid $data */
        $data = $this->gridFactory->create();

        $this->resource->load($data, $sku, 'sku');

        if ($data->getSku()) {
            $data->setQuantity($data->getQuantity() + $qut);
        } else {
            $data->setSku($sku)->setQuantity($qut);

        }
        $this->resource->save($data);
    }

}
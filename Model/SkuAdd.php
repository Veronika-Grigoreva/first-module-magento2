<?php

namespace First\Module\Model;

use First\Module\Api\SkuAddInterface;
use Magento\Framework\App\Action\AbstractAction;
use Magento\Checkout\Model\Cart as CustomerCart;

class SkuAdd implements SkuAddInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CustomerCart
     */
    private $cart;

    /**
     * @var AbstractAction
     */
    private $action;

    /**
     * @var \First\Module\Model\AddToCart
     */
    private $toCart;

    /**
     * SkuAdd constructor.
     * @param \First\Module\Model\AddToCart $toCart
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param CustomerCart $cart
     * @param AbstractAction $action
     */
    public function __construct(
        AddToCart $toCart,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        CustomerCart $cart,
        AbstractAction $action
    ) {
        $this->storeManager = $storeManager;
        $this->cart = $cart;
        $this->action = $action;
        $this->toCart = $toCart;
    }

    /**
     * {@inheritdoc}
     */
    public function addSku($skuInput)
    {
        $csvInput = $this->toCart->getInfo();

        if (!empty($skuInput)) {
            $product = $this->toCart->getByProdSku($skuInput);

            if (!$product) {
                $this->toCart->errorMessage();

                return false;
            }
            $this->cart->addProduct($product,1);
            $this->toCart->adminPageProduct($skuInput);
        }

        $this->cart->save();

        if (empty($skuInput) && empty($csvInput)) {
            $this->toCart->errorMessage();
        } else {
            $this->toCart->successMessage();
        }

        return true;
    }
}
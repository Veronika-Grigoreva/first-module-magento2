<?php

namespace First\Module\Controller\Cart;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Checkout\Model\Cart as CustomerCart;
use First\Module\Model\AddToCart;

class Add extends Action
{
    /**
     * @var AddToCart
     */
    private $toCart;
    /**
     * @var CustomerCart
     */
    private $cart;

    /**
     * Add constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param CustomerCart $cart
     * @param AddToCart $toCart
     */
    public function __construct(
        Context $context,
        CustomerCart $cart,
        AddToCart $toCart
    ) {
        parent::__construct($context);
        $this->toCart = $toCart;
        $this->cart = $cart;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        try {
            $sku  = $this->getRequest()->getParam('skuInput');

            if (!empty($sku)) {
                $product = $this->toCart->getByProdSku($sku);

                if (!$product) {
                    $this->toCart->errorMessage();
                    $this->_redirect($this->_redirect->getRefererUrl());
                }

                $this->cart->addProduct($product, 1);
                $this->toCart->adminPageProduct($sku);
            }

            $csv = $this->toCart->getInfo();
            $this->cart->save();

            if (empty($sku) && empty($csv)) {
                $this->toCart->errorMessage();
            } else {
                $this->toCart->successMessage();
                $this->_redirect($this->_redirect->getRefererUrl());
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $e->getMessage();
        }
    }
}
<?php

namespace First\Module\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use \Magento\Framework\Data\Form\FormKey;

class CartAddPlugin
{
    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    private $formKey;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * CartAddPlugin constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param FormKey $formKey
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        FormKey $formKey
    ) {
        $this->productRepository = $productRepository;
        $this->formKey = $formKey;
    }

    /**
     * @param \Magento\Checkout\Controller\Cart\Add $add
     * @return null
     */
    public function beforeExecute (
        \Magento\Checkout\Controller\Cart\Add $add

    )
    {
        $params = $add->getRequest()->getParams();

        if (isset($params['skuInput']) && $params) {

            try {
                $product = $this->productRepository->get($params['skuInput']);
            } catch (NoSuchEntityException $e) {
                return null;
            }

            $params['product']  = $product->getId();
            $params['form_key'] = $this->formKey->getFormKey();
            $add->getRequest()->setParams($params);
        }
    }
}

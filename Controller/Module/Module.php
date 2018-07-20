<?php

namespace First\Module\Controller\Module;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Module extends Action
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $page;
    }
}
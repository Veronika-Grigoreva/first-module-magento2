<?php

namespace First\Module\Controller\Adminhtml\Grid;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use First\Module\Model\ResourceModel\Grid\CollectionFactory;
use First\Module\Api\DataSkuRepositoryInterface;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Massactions filter.​_
     * @var Filter
     */
    protected $_filter;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;
    /**
     * @var DataSkuRepositoryInterface
     */
    private $repository;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param DataSkuRepositoryInterface $repository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DataSkuRepositoryInterface $repository
    ) {

        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
        $this->repository = $repository;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Exception
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $recordDeleted = 0;
        foreach ($collection->getItems() as $record) {
            $this->repository->delete($record->getSku());
            $recordDeleted++;
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $recordDeleted));

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }

    /**
     * Check Category Map recode delete Permission.
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('First_Module::row_data_delete');
    }
}
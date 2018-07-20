<?php

namespace First\Module\Model;

use First\Module\Api\DataSkuRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\App\Filesystem\DirectoryList;

class AddToCart
{
    const SKU = 0;
    const QUANTITY = 1;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CustomerCart
     */
    private $cart;

    /**
     * @var \Magento\Framework\File\Csv
     */
    private $csv;

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $file;

    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    private $formKey;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product;

    /**
     * @var GridFactory
     */
    private $modelfactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;
    /**
     * @var DataSkuRepositoryInterface
     */
    private $dataSkuRepository;

    /**
     * Add constructor.
     * @param CustomerCart $cart
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\File\Csv $csv
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param \Magento\Catalog\Model\Product $product
     * @param GridFactory $modelfactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param DataSkuRepositoryInterface $dataSkuRepository
     */
    public function __construct(
        CustomerCart $cart,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Catalog\Model\Product $product,
        \First\Module\Model\GridFactory $modelfactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        DataSkuRepositoryInterface $dataSkuRepository
    ) {
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->csv = $csv;
        $this->filesystem = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->file = $file;
        $this->formKey = $formKey;
        $this->product = $product;
        $this->modelfactory = $modelfactory;
        $this->messageManager = $messageManager;
        $this->dataSkuRepository = $dataSkuRepository;
    }

    public function errorMessage()
    {
        $this->messageManager->addErrorMessage(__('Error. Choose product!'));
    }

    public function successMessage()
    {
        $this->messageManager->addSuccessMessage(__('Success. You added to your shopping cart.'));
    }

    /**
     * @return array|null
     */
    public function getCsvFile()
    {
        try {
            $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'csvInput']);
            $uploaderFactory->setAllowedExtensions(['csv']);
            $uploaderFactory->setAllowRenameFiles(true);
            $uploaderFactory->setFilesDispersion(true);
            $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $destinationPath = $mediaDirectory->getAbsolutePath('custom_file');
            $result = $uploaderFactory->save($destinationPath);

            if (!$result) {
                throw new \Magento\Framework\Exception\LocalizedException(__("File cannot be saved to path: $destinationPath"));
            }

            $filepath = $result['path'] . $result['file'];
            $csvData = $this->csv->getData($filepath);
            $this->file->deleteFile($filepath);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            //$this->messageManager->addError($e->getMessage());
            return null;
        } catch (\Exception $e) {
            return null;
        }

        return $csvData;
    }

    /**
     * @param string $sku
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface
     * @throws NoSuchEntityException
     */
    public function getByProdSku($sku)
    {
        if ($sku) {
            $storeId = $this->storeManager->getStore()->getId();
            try {
                return $this->productRepository->get($sku, false, $storeId);
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * @param string $sku
     * @param int $qut
     * @throws \Exception
     */
    public function adminPageProduct($sku, $qut = 1)
    {
        $this->dataSkuRepository->update($sku, $qut);
    }

    /**
     * @return bool
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function getInfo()
    {
        $csvInput = $this->getCsvFile();

        if (!empty($csvInput)) {
            foreach ($csvInput as $row => $data) {
                if ($row > 0) {
                    $product = $this->getByProdSku($data[self::SKU]);
                    $this->cart->addProduct($product, $data[self::QUANTITY]);
                    $this->adminPageProduct($data[self::SKU], $data[self::QUANTITY]);
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
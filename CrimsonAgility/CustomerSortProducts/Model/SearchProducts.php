<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Model;

use CrimsonAgility\CustomerSortProducts\Api\SearchProductsInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validation\ValidationException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;

/**
 * Returns products in range.
 */
class SearchProducts implements SearchProductsInterface
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $productCollectionFactory;

    /**
     * @var RequestValidator
     */
    private RequestValidator $requestValidator;

    /**
     * @var StoreManagerInterface 
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var StockItemRepository 
     */
    private StockItemRepository $stockItemRepository;

    /**
     * Ajax Call that returns product collection
     *
     * @param CollectionFactory $productCollectionFactory
     * @param RequestValidator $requestValidator
     * @param StoreManagerInterface $storeManager
     * @param StockItemRepository $stockItemRepository
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        RequestValidator $requestValidator,
        StoreManagerInterface $storeManager,
        StockItemRepository $stockItemRepository
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->requestValidator = $requestValidator;
        $this->storeManager = $storeManager;
        $this->stockItemRepository = $stockItemRepository;
    }

    /**
     * Returns product collection if validation passes
     *
     * @param float $lowerRange
     * @param float $higherRange
     * @param string $sorting
     * @return array
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function getProductCollection(float $lowerRange, float $higherRange, string $sorting): array
    {
        $validationResult = $this->requestValidator->validate($lowerRange, $higherRange);
        
        if (!$validationResult->isValid()) {
            throw new ValidationException(__('Validation Failed.'), null, 0, $validationResult);
        }
        
        $products = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter( 
                'price', 
                ['from' => $lowerRange, 'to' => $higherRange]
            )
            ->addAttributeToFilter(
                'visibility',
                ['in' => Visibility::VISIBILITY_BOTH]
            )
            ->setOrder('price', $sorting)
            ->setPageSize(10);
        
        $productsResult = [];
        /** @var ProductInterface $product */
        foreach ($products->getItems() as $product) {
            $productsResult[] = [
                'thumbnail' => $this->storeManager->getStore()->getBaseUrl()
                    . 'media/catalog/product' . 
                    $product->getThumbnail(),
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'qty' => $this->stockItemRepository->get($product->getId())->getQty(),
                'price' => $product->getPrice(),
                'url' => $product->getProductUrl(),
            ];
        }
        return $productsResult;
    }
}

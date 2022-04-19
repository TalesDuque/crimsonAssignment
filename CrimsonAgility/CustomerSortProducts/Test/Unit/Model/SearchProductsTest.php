<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Test\Unit\Model;

use CrimsonAgility\CustomerSortProducts\Model\RequestValidator;
use CrimsonAgility\CustomerSortProducts\Model\SearchProducts;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\Validation\ValidationException;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;

class SearchProductsTest extends TestCase
{
    /**
     * @var SearchProducts
     */
    private SearchProducts $instance;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $productCollectionFactoryMock;

    /**
     * @var Collection 
     */
    private Collection $productCollectionMock;

    /**
     * @var ProductInterface 
     */
    private ProductInterface $productMock;

    /**
     * @var RequestValidator 
     */
    private RequestValidator $requestValidatorMock;

    /**
     * @var StoreManagerInterface 
     */
    private StoreManagerInterface $storeManagerMock;

    /**
     * @var StoreInterface 
     */
    private StoreInterface $storeMock;

    /**
     * @var StockItemRepository 
     */
    private StockItemRepository $stockItemRepositoryMock;

    /**
     * @var StockItemInterface
     */
    private StockItemInterface $stockItemMock;

    /**
     * @var ValidationResult
     */
    private ValidationResult $validationResultMock;

    /**
     * Setup for the test
     *
     * @return void
     */
    public function setup(): void
    {
        $this->setMocks();
        $this->setInstance();
    }

    /**
     * Set necessary mocks
     *
     * @return void
     */
    public function setMocks(): void
    {
        $this->validationResultMock = $this->createMock(ValidationResult::class);
        $this->requestValidatorMock = $this->createMock(RequestValidator::class);
        $this->productCollectionFactoryMock = $this->createMock(CollectionFactory::class);
        $this->productCollectionMock = $this->createMock(Collection::class);
        $this->productMock = $this->createMock(ProductInterface::class);
        $this->storeManagerMock = $this->createMock(StoreManagerInterface::class);
        $this->storeMock = $this->createMock(Store::class);
        $this->stockItemMock = $this->createMock(StockItemInterface::class);
        $this->stockItemRepositoryMock = $this->createMock(StockItemRepository::class);
        $this->productMock = $this->getMockBuilder(ProductInterface::class)
            ->disableOriginalConstructor()
            ->addMethods([
                'getThumbnail',
                'getProductUrl'
            ])
            ->getMockForAbstractClass();
    }

    /**
     * Setup test instance
     *
     * @return void
     */
    public function setInstance(): void
    {
        $this->instance = new SearchProducts(
            $this->productCollectionFactoryMock,
            $this->requestValidatorMock,
            $this->storeManagerMock,
            $this->stockItemRepositoryMock
        );
    }

    /**
     * @test
     */
    public function testGetProductCollectionInvalidEntries(): void
    {
        $lower = 5.0;
        $higher = 4.0;
        
        $this->requestValidatorMock
            ->expects($this->once())
            ->method('validate')
            ->with($lower, $higher)
            ->willReturn($this->validationResultMock);
        
        $this->validationResultMock
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);
        
        $this->expectException(ValidationException::class);
        $this->instance->getProductCollection($lower, $higher, 'asc');
    }

    /**
     * @test
     */
    public function testGetProductCollectionReturningProduct(): void
    {
        $lower = 5.0;
        $higher = 20.0;
        $sorting = 'asc';
        
        $expected = [
            [
                'thumbnail' => 'www.baseurl.com/media/catalog/product/thumbnail/path',
                'sku' => 'SKU-TEST',
                'name' => 'Swimsuit',
                'qty' => 100,
                'price' => 25.0,
                'url' => 'www.baseurl.com/product'
            ]
        ];

        $this->requestValidatorMock
            ->expects($this->once())
            ->method('validate')
            ->with($lower, $higher)
            ->willReturn($this->validationResultMock);

        $this->validationResultMock
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->productCollectionFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->productCollectionMock);
        
        $this->productCollectionMock
            ->expects($this->once())
            ->method('addAttributeToSelect')
            ->with('*')
            ->willReturn($this->productCollectionMock);

        $this->productCollectionMock
            ->expects($this->once())
            ->method('addFieldToFilter')
            ->with('price', ['from' => $lower, 'to' => $higher])
            ->willReturn($this->productCollectionMock);

        $this->productCollectionMock
            ->expects($this->once())
            ->method('addAttributeToFilter')
            ->with('visibility', ['in' => Visibility::VISIBILITY_BOTH])
            ->willReturn($this->productCollectionMock);

        $this->productCollectionMock
            ->expects($this->once())
            ->method('setOrder')
            ->with('price', $sorting)
            ->willReturn($this->productCollectionMock);

        $this->productCollectionMock
            ->expects($this->once())
            ->method('setPageSize')
            ->with(10)
            ->willReturn($this->productCollectionMock);

        $this->productCollectionMock
            ->expects($this->once())
            ->method('getItems')
            ->willReturn([$this->productMock]);
        
        $this->storeManagerMock
            ->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);
        
        $this->storeMock
            ->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn('www.baseurl.com/');
        
        $this->productMock
            ->expects($this->once())
            ->method('getThumbnail')
            ->willReturn('/thumbnail/path');

        $this->productMock
            ->expects($this->once())
            ->method('getSku')
            ->willReturn('SKU-TEST');

        $this->productMock
            ->expects($this->once())
            ->method('getName')
            ->willReturn('Swimsuit');

        $this->productMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn(1);
        
        $this->stockItemRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->with(1)
            ->willReturn($this->stockItemMock);

        $this->stockItemMock
            ->expects($this->once())
            ->method('getQty')
            ->willReturn(100);

        $this->productMock
            ->expects($this->once())
            ->method('getPrice')
            ->willReturn(25.0);

        $this->productMock
            ->expects($this->once())
            ->method('getProductUrl')
            ->willReturn('www.baseurl.com/product');
        
        $result = $this->instance->getProductCollection($lower, $higher, 'asc');
        $this->assertEquals($expected, $result);
    }
}

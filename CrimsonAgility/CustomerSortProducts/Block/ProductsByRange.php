<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Block;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class ProductsByRange extends Template
{
    /**
     * @var Context 
     */
    private Context $context;

    /**
     * @var StoreManagerInterface 
     */
    private StoreManagerInterface $storeManager;

    /**
     * ProductsByRange block constructor
     * 
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Returns current currency
     * 
     * @return string
     * @throws NoSuchEntityException
     */
    public function getCurrency(): string
    {
        return $this->storeManager->getStore()->getCurrentCurrencyCode();
    }
}

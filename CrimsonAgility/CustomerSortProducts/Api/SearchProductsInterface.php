<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Api;

/**
 * API Call for the product collection.
 */
interface SearchProductsInterface
{
    /**
     * @param float $lowerRange
     * @param float $higherRange
     * @param string $sorting
     * @return array
     */
    public function getProductCollection(
        float $lowerRange,
        float $higherRange,
        string $sorting
    ): array;
}

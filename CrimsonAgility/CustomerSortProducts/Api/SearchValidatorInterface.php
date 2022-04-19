<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Api;

use Magento\Framework\Validation\ValidationResult;

/**
 * Validator interface for customer product search
 */
interface SearchValidatorInterface
{
    /**
     * Validate rules for Product Search
     * 
     * @param float $lowerRange
     * @param float $higherRange
     * @return ValidationResult
     */
    public function validate(
        float $lowerRange,
        float $higherRange
    ): ValidationResult;
}

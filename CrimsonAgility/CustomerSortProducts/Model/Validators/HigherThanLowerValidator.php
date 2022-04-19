<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Model\Validators;

use CrimsonAgility\CustomerSortProducts\Api\SearchValidatorInterface;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;

/**
 * Class that validates if higher range is higher than lower range.
 */
class HigherThanLowerValidator implements SearchValidatorInterface
{
    /**
     * @var ValidationResultFactory
     */
    private ValidationResultFactory $validationResultFactory;

    /**
     * Higher Than Lower validator constructor
     *
     * @param ValidationResultFactory $validationResultFactory
     */
    public function __construct(
        ValidationResultFactory $validationResultFactory
    ) {
        $this->validationResultFactory = $validationResultFactory;
    }

    /**
     * @inheritDoc
     */
    public function validate(float $lowerRange, float $higherRange): ValidationResult
    {
        $errors = [];
        if ($lowerRange >= $higherRange) {
            $errors[] = __('The higher range value must be higher than the lower range value.');
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}

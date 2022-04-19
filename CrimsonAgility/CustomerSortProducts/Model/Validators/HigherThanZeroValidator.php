<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Model\Validators;

use CrimsonAgility\CustomerSortProducts\Api\SearchValidatorInterface;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;

/**
 * Class that validates if inserted values are higher than zero.
 */
class HigherThanZeroValidator implements SearchValidatorInterface
{
    /**
     * @var ValidationResultFactory
     */
    private ValidationResultFactory $validationResultFactory;

    /**
     * Higher Than Zero validator constructor
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
        if ($lowerRange < 0 || $higherRange <= 0) {
            $errors[] = __('The range values must be higher than zero.');
        }
        
        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}

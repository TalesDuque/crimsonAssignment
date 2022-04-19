<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Model\Validators;

use CrimsonAgility\CustomerSortProducts\Api\SearchValidatorInterface;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;

/**
 * Class that validates if higher range is not five times higher than the lower range.
 */
class FiveTimesValidator implements SearchValidatorInterface
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
        if (($higherRange > 5*$lowerRange && $lowerRange !== 0.0) || ($lowerRange === 0.0 && $higherRange > 5)) {
            $errors[] = __('The higher range value must not be five times higher than the lower range value.');
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}


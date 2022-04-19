<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Model;

use CrimsonAgility\CustomerSortProducts\Api\SearchValidatorInterface;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;

/**
 * Composite validator that groups all other rules
 */
class RequestValidator implements SearchValidatorInterface
{
    /**
     * @var ValidationResultFactory 
     */
    private ValidationResultFactory $validationResultFactory;
    
    /**
     * @var SearchValidatorInterface[]
     */
    private array $validators;
    
    /**
     * Request validator constructor
     * 
     * @param ValidationResultFactory $validationResultFactory
     * @param SearchValidatorInterface[] $validators
     */
    public function __construct(
        ValidationResultFactory $validationResultFactory,
        array $validators = []
    ) {
        $this->validationResultFactory = $validationResultFactory;
        $this->validators = $validators;
    }

    /**
     * @inheritDoc
     */
    public function validate(float $lowerRange, float $higherRange): ValidationResult
    {
        $errors = [];
        
        foreach ($this->validators as $validator) {
            $validationResult = $validator->validate($lowerRange, $higherRange);
            if (!$validationResult->isValid()) {
                $errors[] = $validationResult->getErrors();
            }
        }
        
        $errorsMerged = array_merge([], ...$errors);
        
        return $this->validationResultFactory->create(['errors' => $errorsMerged]);
    }
}

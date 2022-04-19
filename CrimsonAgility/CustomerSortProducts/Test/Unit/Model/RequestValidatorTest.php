<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Test\Unit\Model;

use CrimsonAgility\CustomerSortProducts\Api\SearchValidatorInterface;
use CrimsonAgility\CustomerSortProducts\Model\RequestValidator;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use PHPUnit\Framework\TestCase;

class RequestValidatorTest extends TestCase
{
    /**
     * @var RequestValidator 
     */
    private RequestValidator $instance;
    
    /**
     * @var ValidationResultFactory 
     */
    private ValidationResultFactory $validationResultFactoryMock;

    /**
     * @var ValidationResult 
     */
    private ValidationResult $validationResultMock;

    /**
     * @var SearchValidatorInterface 
     */
    private SearchValidatorInterface $validatorMock;

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
        $this->validationResultFactoryMock = $this->createMock(ValidationResultFactory::class);
        $this->validationResultMock = $this->createMock(ValidationResult::class);
        $this->validatorMock = $this->createMock(SearchValidatorInterface::class);
    }

    /**
     * Setup test instance
     * 
     * @return void
     */
    public function setInstance(): void
    {
        $this->instance = new RequestValidator($this->validationResultFactoryMock, [$this->validatorMock]);
    }

    /**
     * @test
     */
    public function testValidateInvalidEntries(): void
    {
        $lower = 5;
        $higher = 10;
        $errorMsg = 'Error message';
        $errorsMerged = [$errorMsg];
        
        $this->validatorMock
            ->expects($this->once())
            ->method('validate')
            ->with($lower, $higher)
            ->willReturn($this->validationResultMock);
        
        $this->validationResultMock
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->validationResultMock
            ->expects($this->once())
            ->method('getErrors')
            ->willReturn([$errorMsg]);
        
        $this->validationResultFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with(['errors' => $errorsMerged])
            ->willReturn($this->validationResultMock);
        
        $result = $this->instance->validate($lower, $higher);
        $this->assertEquals($this->validationResultMock, $result);
    }
}

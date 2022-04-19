<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Test\Unit\Model\Validators;

use CrimsonAgility\CustomerSortProducts\Model\Validators\HigherThanLowerValidator;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use PHPUnit\Framework\TestCase;

class HigherThanLowerValidatorTest extends TestCase
{
    /**
     * @var HigherThanLowerValidator
     */
    private HigherThanLowerValidator $instance;

    /**
     * @var ValidationResultFactory
     */
    private ValidationResultFactory $validationResultFactoryMock;

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
        $this->validationResultFactoryMock = $this->createMock(ValidationResultFactory::class);
        $this->validationResultMock = $this->createMock(ValidationResult::class);
    }

    /**
     * Setup test instance
     *
     * @return void
     */
    public function setInstance(): void
    {
        $this->instance = new HigherThanLowerValidator($this->validationResultFactoryMock);
    }

    /**
     * @test
     */
    public function testValidateInvalidHigherLowerThanLowerRange(): void
    {
        $lower = 5;
        $higher = 4;
        $errorMsg = __('The higher range value must be higher than the lower range value.');
        $errorsMerged = [$errorMsg];

        $this->validationResultFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with(['errors' => $errorsMerged])
            ->willReturn($this->validationResultMock);

        $result = $this->instance->validate($lower, $higher);
        $this->assertEquals($this->validationResultMock, $result);
    }

    /**
     * @test
     */
    public function testValidateValidHigherHigherThanLowerRange(): void
    {
        $lower = 5;
        $higher = 6;

        $this->validationResultFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with(['errors' => []])
            ->willReturn($this->validationResultMock);

        $result = $this->instance->validate($lower, $higher);
        $this->assertEquals($this->validationResultMock, $result);
    }

    /**
     * @test
     */
    public function testValidateInvalidHigherEqualToLower(): void
    {
        $lower = 6;
        $higher = 6;
        $errorMsg = __('The higher range value must be higher than the lower range value.');
        $errorsMerged = [$errorMsg];

        $this->validationResultFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with(['errors' => $errorsMerged])
            ->willReturn($this->validationResultMock);

        $result = $this->instance->validate($lower, $higher);
        $this->assertEquals($this->validationResultMock, $result);
    }
}

<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Test\Unit\Model\Validators;

use CrimsonAgility\CustomerSortProducts\Model\Validators\HigherThanZeroValidator;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use PHPUnit\Framework\TestCase;

class HigherThanZeroValidatorTest extends TestCase
{
    /**
     * @var HigherThanZeroValidator
     */
    private HigherThanZeroValidator $instance;

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
        $this->instance = new HigherThanZeroValidator($this->validationResultFactoryMock);
    }

    /**
     * @test
     */
    public function testValidateInvalidLowerBelowZero(): void
    {
        $lower = -1;
        $higher = 4;
        $errorMsg = __('The range values must be higher than zero.');
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
    public function testValidateInvalidHigherBelowZero(): void
    {
        $lower = 5;
        $higher = -1;
        $errorMsg = __('The range values must be higher than zero.');
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
    public function testValidateInvalidBothBelowZero(): void
    {
        $lower = -1;
        $higher = -2;
        $errorMsg = __('The range values must be higher than zero.');
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
    public function testValidateValidBothOverZero(): void
    {
        $lower = 2;
        $higher = 3;

        $this->validationResultFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with(['errors' => []])
            ->willReturn($this->validationResultMock);

        $result = $this->instance->validate($lower, $higher);
        $this->assertEquals($this->validationResultMock, $result);
    }
}

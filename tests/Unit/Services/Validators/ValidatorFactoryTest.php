<?php
declare(strict_types=1);

namespace Tests\Unit\Services\Validators;

use Parking\Service\Validators\ExitedAtValidator;
use Parking\Service\Validators\PriceValidator;
use Parking\Service\Validators\ValidatorFactory;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Service\Validators\ValidatorFactory */
final class ValidatorFactoryTest extends TestCase {

    /**
     * @testdox Given a field it returns the correct class
     * @dataProvider provideFieldsAndClasses
     */
    public function testReturnsCorrectClass(string $field, string $expectedClass) {
        $class = (new ValidatorFactory)->getValidatorFromFieldName($field);

        $this->assertInstanceOf($expectedClass, $class);
    }

    public function provideFieldsAndClasses(): array {
        return [
            ['exited_at', ExitedAtValidator::class],
            ['price', PriceValidator::class],
        ];
    }

    /**
     * @testdox Given a wrong field it throws the correct exception
     *@expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testThrowsException() {
        (new ValidatorFactory)->getValidatorFromFieldName('wrong field');
    }
}
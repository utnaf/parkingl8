<?php
declare(strict_types=1);

namespace Tests\Unit\Services\Validators;

use Illuminate\Support\Collection;
use Parking\Service\Validators\ValidatorFactory;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Service\Validators\ValidatorFactory */
final class ValidatorFactoryTest extends TestCase {

    /**
     * @testdox Given a field it returns the correct class
     * @dataProvider provideFieldsAndClasses
     */
    public function testReturnsCorrectClass(string $field) {
        $class = (new ValidatorFactory)->getValidatorFromFieldName($field);

        $this->assertInstanceOf(Collection::class, $class);
    }

    public function provideFieldsAndClasses(): array {
        return [
            ['exited_at'],
            ['price'],
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
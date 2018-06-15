<?php
declare(strict_types=1);

namespace Parking\Service\Validators;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ValidatorFactory {

    /** @throws BadRequestHttpException */
    public function getValidatorFromFieldName(string $field): Validator {
        switch ($field) {
            case 'price':
                return new PriceValidator;
            case 'exited_at':
                return new ExitedAtValidator;
            default:
                throw new BadRequestHttpException(
                    sprintf('You can\'t update %s field', $field)
                );
        }
    }

}
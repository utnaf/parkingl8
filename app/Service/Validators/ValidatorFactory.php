<?php
declare(strict_types=1);

namespace Parking\Service\Validators;

use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ValidatorFactory {

    /**
     * @throws BadRequestHttpException
     * @return Validator[]
     */
    public function getValidatorFromFieldName(string $field): Collection {
        switch ($field) {
            case 'price':
                return new Collection([
                    new PriceValidator,
                    new CanPayValidator
                ]);
            case 'exited_at':
                return new Collection([
                    new ExitedAtValidator,
                    new PayedValidator
                ]);
            default:
                throw new BadRequestHttpException(
                    sprintf('You can\'t update %s field', $field)
                );
        }
    }

}
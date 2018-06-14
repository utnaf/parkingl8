<?php
declare(strict_types=1);

namespace Parking\Service\Validators;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PriceValidator implements Validator {

    public function pass(array $data): bool {
        return !validator($data, [
            'price' => 'numeric'
        ])->fails() && $data['price'] > 0;
    }

    public function getException(): HttpException {
        return new BadRequestHttpException(
            sprintf('Given price is wrong')
        );
    }
}
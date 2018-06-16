<?php
declare(strict_types=1);

namespace Parking\Service\Validators;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PayingValidator extends ValidatorAbstract {

    public function pass(array $data): bool {
        return $this->entry->payed_at === null
            && $this->entry->exited_at === null;
    }

    public function getException(): HttpException {
        return new BadRequestHttpException(
            __('errors.e_already_payed')
        );
    }
}
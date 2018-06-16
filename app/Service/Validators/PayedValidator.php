<?php

namespace Parking\Service\Validators;

use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

final class PayedValidator extends ValidatorAbstract {

    public function pass(array $data): bool {
        return $this->entry->payed_at instanceof Carbon;
    }

    public function getException(): HttpException {
        return new NotAcceptableHttpException(
            __('errors.e_not_payed')
        );
    }
}
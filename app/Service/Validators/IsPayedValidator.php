<?php

namespace Parking\Service\Validators;

use Illuminate\Support\Carbon;
use Parking\Issue;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

final class IsPayedValidator extends ValidatorAbstract {

    public function pass(array $data): bool {
        return $this->entry->payed_at instanceof Carbon;
    }

    public function getException(): HttpException {
        $this->issueRepository->addForEntry($this->entry, Issue::TYPE_NOT_PAYED);
        return new NotAcceptableHttpException(
            __('errors.e_not_payed')
        );
    }
}
<?php
declare(strict_types=1);

namespace Parking\Service\Validators;

use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

final class ExitedAtValidator extends ValidatorAbstract {

    public function pass(array $data): bool {
        return $data['exited_at'] instanceof \DateTime
            && Carbon::now()
                ->addMinutes($this->entry->parkingLot()->first()->threshold_minutes)
                ->diffInMinutes($data['exited_at'], false) <= 0
            && $this->entry->payed_at !== null;
    }

    public function getException(): HttpException {
        return new NotAcceptableHttpException(
            'You can\t exit'
        );
    }
}
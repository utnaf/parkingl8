<?php
declare(strict_types=1);

namespace Parking\Service\Validators;

use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

final class ExitedAtValidator extends ValidatorAbstract {

    public function pass(array $data): bool {
        try {
            $carbonData = Carbon::createFromTimeString($data['exited_at']);
        }
        catch (\InvalidArgumentException $e) {
            return false;
        }

        return $carbonData instanceof Carbon
            && Carbon::now()
                ->addMinutes($this->entry->parkingLot()->first()->threshold_minutes)
                ->diffInMinutes($carbonData, false) <= 0;
    }

    public function getException(): HttpException {
        return new NotAcceptableHttpException(
            __('errors.e_exit_at_too_late')
        );
    }
}
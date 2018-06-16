<?php
declare(strict_types=1);

namespace Parking\Service;

use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Parking\Entry;
use Parking\ParkingLot;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PriceCalculatorService {

    /** @throws HttpException */
    public function calculateForEntry(Entry $entry): float {
        if ($entry->price !== null) {
            throw new HttpException(Response::HTTP_NOT_MODIFIED, '');
        }

        if (
            !$entry->arrived_at instanceof Carbon
            || $entry->arrived_at > Carbon::now()
        ) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Arrive time is not valid'
            );
        }

        if (!($lot = $entry->parkingLot()->first()) instanceof ParkingLot) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'This entry doesn\'t belong to any parking lot'
            );
        }

        return $this->calculate($entry->arrived_at, (float) $lot->hourly_fare);
    }

    public function calculate(Carbon $arrivedAt, float $hourlyFare): float {
        $minutes    = $arrivedAt->diffInMinutes();
        $minuteFare = $hourlyFare / 60;

        return round($minutes * $minuteFare, 2);
    }

}
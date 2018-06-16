<?php
declare(strict_types=1);

namespace Parking\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Parking\ParkingLot;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ParkingLotRepository {

    /** @return ParkingLot[] */
    public function getAll(): Collection {
        return ParkingLot::withCount([
            'entries as taken_spots' => function(Builder $query) {
                return $query->whereNull('exited_at');
            }
        ])->get();
    }

    /** @throws NotFoundHttpException */
    public function getById(int $id): ParkingLot {
        $lot = ParkingLot::withCount([
            'entries as taken_spots' => function(Builder $query) {
                return $query->whereNull('exited_at');
            }
        ])->find($id);

        if ($lot instanceof ParkingLot) {
            return $lot;
        }

        throw new NotFoundHttpException(
            sprintf('Can\'t find a ParkingLot with id %d', $id)
        );
    }

    /** @throws NotFoundHttpException */
    public function update(ParkingLot $lot, array $newData) {
        $lot->name = $newData['name'];
        $lot->hourly_fare = (float) $newData['hourly_fare'];
        $lot->capacity = (int) $newData['capacity'];
        $lot->threshold_minutes = (int) $newData['threshold_minutes'];

        $lot->save();

        return $lot;
    }
}
<?php
declare(strict_types=1);

namespace Parking\Repositories;

use Illuminate\Support\Collection;
use Parking\ParkingLot;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ParkingLotRepository {

    /** @return ParkingLot[] */
    public function getAll(): Collection {
        return ParkingLot::withCount('entries')->get();
    }

    /** @throws NotFoundHttpException */
    public function getById(int $id): ParkingLot {
        $lot = ParkingLot::withCount('entries')->find($id);

        if ($lot instanceof ParkingLot) {
            return $lot;
        }

        throw new NotFoundHttpException(
            sprintf("Can't find a ParkingLot with id of %d", $id)
        );
    }
}
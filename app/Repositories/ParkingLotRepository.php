<?php

namespace Parking\Repositories;

use Illuminate\Support\Collection;
use Parking\ParkingLot;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

final class ParkingLotRepository {

    public function getAll(): Collection {
        return ParkingLot::all();
    }

    /** @throws ResourceNotFoundException */
    public function getById(int $id): ParkingLot {
        $lot = ParkingLot::find($id);

        if ($lot instanceof ParkingLot) {
            return $lot;
        }

        throw new ResourceNotFoundException(
            sprintf("Can't find ParkingLot with id of %d", $id)
        );
    }

}
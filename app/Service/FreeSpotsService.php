<?php
declare(strict_types=1);

namespace Parking\Service;

use Parking\ParkingLot;

class FreeSpotsService {

    public function areThereFreeSpots(ParkingLot $parkingLot): bool {
        return $parkingLot->entries()->whereNull('exited_at')->count() < $parkingLot->capacity;
    }

}
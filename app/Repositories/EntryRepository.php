<?php
declare(strict_types=1);

namespace Parking\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Parking\Entry;
use Parking\Service\FreeSpotsService;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntryRepository {

    /** @var ParkingLotRepository */
    private $parkingLotRepository;

    /** @var FreeSpotsService */
    private $freeSpotsService;

    public function __construct(
        ParkingLotRepository $parkingLotRepository,
        FreeSpotsService $freeSpotsService
    ) {
        $this->parkingLotRepository = $parkingLotRepository;
        $this->freeSpotsService = $freeSpotsService;
    }

    /**
     * @return Entry[]
     * @throws NotFoundHttpException
     */
    public function getByParkingLotId(int $parkingLotId): Collection {
        return $this->parkingLotRepository->getById($parkingLotId)->entries()->get();
    }

    /** @throws NotFoundHttpException */
    public function getById(int $id): Entry {
        $entry = Entry::find($id);

        if ($entry instanceof Entry) {
            return $entry;
        }

        throw new NotFoundHttpException(
            sprintf("Can't find a ParkingLot with id of %d", $id)
        );
    }

    /**
     * @throws NotFoundHttpException
     * @throws NotAcceptableHttpException
     * @return Entry
     */
    public function addToParkingLot(int $parkingLotId): Model {
        $parkingLot = $this->parkingLotRepository->getById($parkingLotId);

        //notice: we can probably go into some race condition here, consider locking tables
        if(!$this->freeSpotsService->areThereFreeSpots($parkingLot)) {
            throw new NotAcceptableHttpException(
                sprintf("ParkingLot %d does not have any free spot", $parkingLotId)
            );
        }

        return $parkingLot->entries()->create();
    }
}
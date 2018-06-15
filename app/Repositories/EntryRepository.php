<?php
declare(strict_types=1);

namespace Parking\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Parking\Entry;
use Parking\Service\FreeSpotsService;
use Parking\Service\Validators\ValidatorFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntryRepository {

    /** @var ParkingLotRepository */
    private $parkingLotRepository;

    /** @var FreeSpotsService */
    private $freeSpotsService;

    /** @var ValidatorFactory */
    private $validatorFactory;

    public function __construct(
        ParkingLotRepository $parkingLotRepository,
        FreeSpotsService $freeSpotsService,
        ValidatorFactory $validatorFactory
    ) {
        $this->parkingLotRepository = $parkingLotRepository;
        $this->freeSpotsService     = $freeSpotsService;
        $this->validatorFactory     = $validatorFactory;
    }

    /**
     * @return Entry[]
     * @throws NotFoundHttpException
     */
    public function getByParkingLotId(int $parkingLotId): Collection {
        return $this->parkingLotRepository->getById($parkingLotId)->entries()
            ->orderBy('exited_at', 'asc')
            ->orderBy('payed_at', 'asc')
            ->orderBy('arrived_at', 'asc')
            ->get();
    }

    /** @throws NotFoundHttpException */
    public function getById(int $id): Entry {
        $entry = Entry::find($id);

        if ($entry instanceof Entry) {
            return $entry;
        }

        throw new NotFoundHttpException(
            sprintf('Can\'t find a ParkingLot with id of %d', $id)
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
        if (!$this->freeSpotsService->areThereFreeSpots($parkingLot)) {
            throw new NotAcceptableHttpException(
                sprintf('ParkingLot %d does not have any free spot', $parkingLotId)
            );
        }

        return $parkingLot->entries()->create();
    }

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function updateFields(int $id, array $data): Entry {
        $entry = Entry::find($id);

        if (!$entry instanceof Entry) {
            throw new NotFoundHttpException(
                sprintf('Can\'t find an entry with id %d', $id)
            );
        }

        $field     = key($data);
        $validator = $this->validatorFactory->getValidatorFromFieldName($field);
        if (!$validator->forEntry($entry)->pass($data)) {
            throw $validator->getException();
        }

        $value = current($data);
        $entry->setAttribute($field, $value);

        return $this->save($entry);
    }

    /** @throws HttpException */
    private function save(Entry $entry): Entry {
        if (!$entry->save()) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Something bad Happened'
            );
        }

        return $entry;
    }
}
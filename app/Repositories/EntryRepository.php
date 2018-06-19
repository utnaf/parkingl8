<?php
declare(strict_types=1);

namespace Parking\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Parking\Entry;
use Parking\Issue;
use Parking\Service\FreeSpotsService;
use Parking\Service\Validators\ValidatorFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntryRepository {

    const LIMIT = 1000;

    /** @var ParkingLotRepository */
    private $parkingLotRepository;

    /** @var FreeSpotsService */
    private $freeSpotsService;

    /** @var ValidatorFactory */
    private $validatorFactory;

    /** @var IssueRepository */
    private $issueRepository;

    public function __construct(
        ParkingLotRepository $parkingLotRepository,
        FreeSpotsService $freeSpotsService,
        ValidatorFactory $validatorFactory,
        IssueRepository $issueRepository
    ) {
        $this->parkingLotRepository = $parkingLotRepository;
        $this->freeSpotsService     = $freeSpotsService;
        $this->validatorFactory     = $validatorFactory;
        $this->issueRepository      = $issueRepository;
    }

    /**
     * @return Entry[]
     * @throws NotFoundHttpException
     */
    public function getByParkingLotId(int $parkingLotId) {
        return $this->parkingLotRepository->getById($parkingLotId)->entries()
            ->orderBy('exited_at', 'asc')
            ->orderBy('payed_at', 'asc')
            ->orderBy('arrived_at', 'asc')
            ->limit(static::LIMIT)
            ->get();
    }

    /** @throws NotFoundHttpException */
    public function getById(int $id): Entry {
        $entry = Entry::find($id);

        if ($entry instanceof Entry) {
            return $entry;
        }

        throw new NotFoundHttpException(
            sprintf('Can\'t find a Entry with id of %d', $id)
        );
    }

    /**
     * @throws NotFoundHttpException
     * @throws NotAcceptableHttpException
     * @return Entry
     */
    public function addToParkingLot(int $parkingLotId): Model {
        $parkingLot = $this->parkingLotRepository->getById($parkingLotId);

        // notice: we can probably go into some race condition here, consider locking tables
        if (!$this->freeSpotsService->areThereFreeSpots($parkingLot)) {
            throw new NotAcceptableHttpException(
                sprintf('ParkingLot %d does not have any free spot', $parkingLotId)
            );
        }

        return $parkingLot->entries()->create([
            'created_at' => Carbon::now()
        ]);
    }

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function updateFields($id, array $data): Entry {
        $entry = $this->getById($id);

        return $this->updateFieldsForEntry($entry, $data);
    }

    /**
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function updateFieldsForEntry(Entry $entry, array $data, $force = false) {
        $field      = key($data);
        $validators = $this->validatorFactory->getValidatorFromFieldName($field);

        if(!$force) {
            foreach ($validators as $validator) {
                if (!$validator->forEntry($entry)->pass($data)) {
                    throw $validator->getException();
                }
            }
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
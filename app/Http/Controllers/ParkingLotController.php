<?php
declare(strict_types=1);

namespace Parking\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Parking\ParkingLot;
use Parking\Repositories\EntryRepository;
use Parking\Repositories\ParkingLotRepository;
use Parking\Service\Validators\ValidationTrait;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ParkingLotController extends Controller {
    use ValidationTrait;

    /**
     * @api {get} /api/lots Request parking lots list
     * @apiName GetParkingLots
     * @apiGroup ParkingLots
     * @apiVersion 1.0.0
     * @apiParam (Query) {Int} id The ParkingLot id
     *
     * @apiSuccess (200) {ParkingLot[]} lots List of parking lots
     * @apiSuccessExample {json} Success Response
     *      {
     *        "lots": [
     *          {
     *            "id": 1,
     *            "name": "Paucek Inc",
     *            "hourly_fare": 0.85,
     *            "capacity": 626,
     *            "taken_spots": 46
     *          }
     *        ]
     *      }
     */
    public function index(ParkingLotRepository $parkingLotRepository): JsonResponse {
        $lots = $parkingLotRepository->getAll();

        return new JsonResponse(
            [
                'lots' => $lots,
            ]
        );
    }

    /**
     * @api {get} /api/lots/:id Request a single parking lot informations
     * @apiName GetParkingLot
     * @apiGroup ParkingLots
     * @apiVersion 1.0.0
     * @apiParam (Query) {Int} id The ParkingLot id
     *
     * @apiSuccess (200) {ParkingLot} lot The requested parking lot
     * @apiSuccessExample {json} Success Response
     *      {
     *        "lot": {
     *          "id": 1,
     *          "name": "Paucek Inc",
     *          "hourly_fare": 0.85,
     *          "capacity": 626,
     *          "taken_spots": 46
     *        }
     *      }
     * @apiError (404) {Int} status Status of the request
     * @apiError (404) {String} message String containing the error
     */
    public function show(string $id, ParkingLotRepository $parkingLotRepository): JsonResponse {
        try {
            $lot = $parkingLotRepository->getById((int) $id);
        }
        catch (HttpException $e) {
            return $this->handleException($e);
        }

        return new JsonResponse(
            [
                'lot' => $lot,
            ]
        );
    }

    /**
     * @api {get} /api/lots/:id/entries Request all the entries of a lot
     * @apiName GetParkingLotEntries
     * @apiGroup ParkingLots
     * @apiVersion 1.0.0
     * @apiParam (Query) {Int} id The ParkingLot id
     *
     * @apiSuccess (200) {Entries[]} entries The list of the entries for a parking log
     * @apiSuccessExample {json} Success Response
     *      {
     *        "entries": [
     *          {
     *            "id": 578,
     *            "parking_lot_id": 1,
     *            "arrived_at": "2018-06-02 03:05:47",
     *            "payed_at": "2018-06-02 09:57:37",
     *            "exited_at": "2018-06-02 10:03:49",
     *            "price": 280.43
     *          }
     *        ]
     *      }
     * @apiError (404) {Int} status Status of the request
     * @apiError (404) {String} message String containing the error
     */
    public function entries(string $parkingLotId, EntryRepository $entryRepository): JsonResponse {
        try {
            $entries = $entryRepository->getByParkingLotId((int) $parkingLotId);
        }
        catch (HttpException $e) {
            return $this->handleException($e);
        }

        return new JsonResponse(
            [
                'entries' => $entries,
            ]
        );
    }

    /**
     * @api {post} /api/lots/:id/entries Create a new entry in the specified lot
     * @apiName AddEntryToParkingLot
     * @apiGroup ParkingLots
     * @apiVersion 1.0.0
     * @apiParam (Query) {Int} id The ParkingLot id
     *
     * @apiSuccess (201) {Entry} entry The created entry
     * @apiSuccessExample {json} Success Response
     *      {
     *        "entry": {
     *          "id": 578,
     *          "parking_lot_id": 1,
     *          "arrived_at": "2018-06-02 03:05:47",
     *          "payed_at": "2018-06-02 09:57:37",
     *          "exited_at": "2018-06-02 10:03:49",
     *          "price": 280.43
     *        }
     *      }
     * @apiError (404) {Int} status Status of the request
     * @apiError (404) {String} message String containing the error
     * @apiError (406) {Int} status Status of the request
     * @apiError (406) {String} message String containing the error
     */
    public function addEntry(string $parkingLotId, EntryRepository $entryRepository): JsonResponse {
        try {
            $entry = $entryRepository->addToParkingLot((int) $parkingLotId);
            $entry->refresh();
        }
        catch (HttpException $e) {
            return $this->handleException($e);
        }

        return new JsonResponse(
            [
                'entry' => $entry,
            ]
            , Response::HTTP_CREATED
        );
    }

    public function edit(string $parkingLotId, ParkingLotRepository $parkingLotRepository) {
        try {
            $lot = $parkingLotRepository->getById((int) $parkingLotId);
        } catch (NotFoundHttpException $e) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return view('lots.edit', [
            'lot' => $lot
        ]);
    }

    public function save(Request $request, string $parkingLotId, ParkingLotRepository $parkingLotRepository) {
        try {
            $lot = $parkingLotRepository->getById((int) $parkingLotId);

            $validatedData = $request->validate(ParkingLot::VALIDATION_RULES);

            $parkingLotRepository->update($lot, $validatedData);
        } catch (NotFoundHttpException $e) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('lots.edit', [
            'lot' => $lot,
            'success' => true
        ]);
    }
}

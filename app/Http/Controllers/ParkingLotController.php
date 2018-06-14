<?php
declare(strict_types=1);

namespace Parking\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Parking\Repositories\ParkingLotRepository;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ParkingLotController extends Controller {
    /**
     * @api {get} /api/lots Request parking lots list
     * @apiName GetParkingLots
     * @apiGroup ParkingLots
     * @apiVersion 1.0.0
     *
     * @apiSuccess (200) {ParkingLot[]} lots List of parking lots
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
     *
     * @apiSuccess (200) {ParkingLot} lot The requested parking lot
     * @apiError (404) {Int} status Status of the request
     * @apiError (404) {String} message String containing the error
     */
    public function item(string $id, ParkingLotRepository $parkingLotRepository): JsonResponse {
        try {
            $lot = $parkingLotRepository->getById((int) $id);
        }
        catch (ResourceNotFoundException $e) {
            return new JsonResponse(
                [
                    'status'  => Response::HTTP_NOT_FOUND,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            [
                'lot' => $lot,
            ]
        );
    }
}

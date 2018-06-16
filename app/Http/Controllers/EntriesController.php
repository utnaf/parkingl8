<?php
declare(strict_types=1);

namespace Parking\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Parking\Repositories\EntryRepository;
use Parking\Service\PriceCalculatorService;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EntriesController extends Controller {
    /**
     * @api {get} /api/entries/:id Request a single entry informations
     * @apiName GetEntry
     * @apiGroup Entries
     * @apiVersion 1.0.0
     * @apiParam (Query) {Int} id The Entry id
     *
     * @apiSuccess (200) {Entry} entry The requested entry
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
     */
    public function show(string $id, EntryRepository $entryRepository): JsonResponse {
        try {
            $entry = $entryRepository->getById((int) $id);
        }
        catch (HttpException $e) {
            return $this->handleException($e);
        }

        return new JsonResponse(
            [
                'entry' => $entry,
            ]
        );
    }

    /**
     * @api {patch} /api/entries/:id Update an entry both for payment or for exit
     * @apiName UpdateEntry
     * @apiGroup Entries
     * @apiVersion 1.0.0
     * @apiParam (Query) {Int} id The entry id
     * @apiParam (BodyForPayment) {Float} price The price that the entry is going to pay
     * @apiParam (BodyForExit) {DateTime} exited_at The time when the entry requested to exit
     *
     * @apiSuccess (200) {Entry} entry The updated entry
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
     * @apiError (400) {Int} status Status of the request
     * @apiError (400) {String} message String containing the error
     * @apiError (404) {Int} status Status of the request
     * @apiError (404) {String} message String containing the error
     * @apiError (406) {Int} status Status of the request
     * @apiError (406) {String} message String containing the error
     */
    public function update(Request $request, string $id, EntryRepository $entryRepository): JsonResponse {
        try {
            $entry = $entryRepository->updateFields((int) $id, $request->all());
        }
        catch (HttpException $e) {
            return $this->handleException($e);
        }

        return new JsonResponse([
            'entry' => $entry
        ]);
    }

    /**
     * @api {patch} /api/entries/:id/price Calculate the price for the requested entry
     * @apiName GetEntryPrice
     * @apiGroup Entries
     * @apiVersion 1.0.0
     * @apiParam (Query) {Int} id The entry id
     *
     * @apiSuccess (200) {Float} price The entry price
     * @apiSuccessExample {json} Success Response
     *      {
     *        "price": 22.3
     *      }
     * @apiSuccess (304) {None} empty The response body is empty
     * @apiError (404) {Int} status Status of the request
     * @apiError (404) {String} message String containing the error
     */
    public function price(string $id, EntryRepository $entryRepository): JsonResponse {
        try {
            $entry = $entryRepository->getById((int) $id);
            $price = (new PriceCalculatorService)->calculateForEntry($entry);
        }
        catch (HttpException $e) {
            return $this->handleException($e);
        }

        return new JsonResponse([
            'price' => $price
        ]);
    }
}
<?php
declare(strict_types=1);

namespace Parking\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Parking\Repositories\EntryRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EntriesController extends Controller {
    /**
     * @api {get} /api/entries/:id Request a single entry informations
     * @apiName GetEntry
     * @apiGroup Entries
     * @apiVersion 1.0.0
     *
     * @apiSuccess (200) {Entry} entry The requested entry
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
}
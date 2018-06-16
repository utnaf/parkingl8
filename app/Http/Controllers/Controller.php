<?php
declare(strict_types=1);

namespace Parking\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpKernel\Exception\HttpException;

/** @codeCoverageIgnore */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @todo i should find a better way to handle it, pretty sure we can do it just with the exception handler
     */
    protected function handleException(HttpException $e) {
        return new JsonResponse(
            [
                'status'  => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ],
            $e->getStatusCode()
        );
    }
}

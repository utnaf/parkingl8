<?php

namespace Parking\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @todo i should find a better way to handle it, pretty sure we can do it just with the exception handler
     */
    protected function handleException(NotFoundHttpException $e) {
        return new JsonResponse(
            [
                'status'  => $e->getCode(),
                'message' => $e->getMessage(),
            ],
            $e->getCode()
        );
    }
}

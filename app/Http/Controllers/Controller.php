<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function success($data = null,  $status = Response::HTTP_OK)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status);
    }

    /**
     * @param $data
     * @param int $status
     * @return JsonResponse
     */
    protected function error($data = null,  $status = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'success' => false,
            'data' => $data,
        ], $status);
    }

    /**
     * @param null $data
     * @return JsonResponse
     */
    protected function created($data = null)
    {
        return $this->success($data, Response::HTTP_CREATED);
    }
}

<?php

namespace App\Traits;

trait ApiResponser
{
    /**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data, int $code = 200, string $message = null)
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }
 
    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = null, int $code = 422, $data = null)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], $code);
    }

    /*protected  function showAll(Collection $collection, $code = 200)
    {
        return $this->successResponse([]);
    }*/
}

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
    protected function errorResponse(string $message = null, int $code, $data = null)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /*protected  function showAll(Collection $collection, $code = 200)
    {
        return $this->successResponse([]);
    }*/
}

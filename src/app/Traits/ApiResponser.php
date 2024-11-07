<?php

namespace App\Traits;

use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Spatie\Fractal\Facades\Fractal;

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
            'data' => $data['data'] ?? $data
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

    //En este caso se el parametro recibido es una coleccion
    protected function showAll(Collection $collection, $code = 200)
    {
        //Log::info('showAll. model = '.json_encode($collection));
        if($collection->isEmpty()){
            return $this->successResponse($collection, $code);
        }

        $instance = $collection->first()->transformer;

        $collection = $this->filterData($collection, $instance); //filtramos la peticion segund los parametros que se envien

        if(request()->has('sort_by')) $collection = $this->sortData($collection, request()->sort_by, $instance);//ordenamos la peticion

        $data = $this->transformData($collection ,$instance);//

        return $this->successResponse($data, $code);
    }

    protected function showOne(Model $model, $code = 200)
    {
        Log::info('showOne. model = '.json_encode($model->transformer));
        $data = $this->transformData($model ,$model->transformer);
        return $this->successResponse($data, $code);
    }

    /*En este metodo vamos a filtrar los datos en el caso de que se manden varios parametros de filtrado*/
    protected function filterData(Collection $collection, $transformer)
    {
        Log::info('FilterData query paramters = '.json_encode(request()->query()));
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalAttribute($query);//obtenemos el atributo original

            if(isset($attribute, $value)){
                $collection = $collection->where($attribute, $value);//si existe, hacemos una busqueda con where en la coleccion
            }
        }

        return $collection;        
    }

    protected function sortData(Collection $collection, $attribute, $transformer)
    {
        //comprobamos si en la peticion se ha enviado una solicitud con el valor de ordenacion
        $attribute = $transformer::originalAttribute(request()->sort_by);
        $sorted = $collection->sortBy($attribute);
        return $sorted->values()->all();
    }

    protected function transformData($data, $transformer)
    {
        return fractal($data, new $transformer)->toArray();
    }
}

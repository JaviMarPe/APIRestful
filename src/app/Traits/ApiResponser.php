<?php

namespace App\Traits;

use App\Transformers\UserTransformer;
//use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
        //Log::info('successResponse data = '.json_encode($data));
        $response = collect([
            'status' => 'Success',
            'message' => $message]
        );
        $allResponse = $response->merge($data);
        $allResponse->all();
        return response()->json($allResponse);
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
        if (!($collection instanceof Collection)) {
            throw new \InvalidArgumentException('Argument must be an instance of Illuminate\Support\Collection or Illuminate\Database\Eloquent\Collection');
        }

        if($collection->isEmpty()){
            return $this->successResponse($collection, $code);
        }

        $transformer = $collection->first()->transformer ?? null;

        if (!$transformer) {
            throw new \RuntimeException('Transformer not found for collection items');
        }

         //filtramos la peticion segund los parametros que se envien
        $collection = $this->filterData($collection, $transformer);

        //ordenamos la peticion
        if(request()->has('sort_by')) $collection = $this->sortData($collection, request()->sort_by, $transformer);

        //Paginamos el resultado
        $collection = $this->pagination($collection);

        //transformamos el resultado para que devuelva claves que no corresponden a las originales de la base de datos
        $collection = $this->transformData($collection ,$transformer);

        //cacheamos la respuesta
        $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection, $code);
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
        Log::info('sortData query paramters = '.json_encode(request()->query()));
        $attribute = $transformer::originalAttribute(request()->sort_by);
        $sorted = $collection->sortBy($attribute);
        return $sorted->values()->all();
    }

    //paginamos la respuesta de la peticion
    protected function pagination(Collection $collection)
    {
        Log::info('pagination collection = '.json_encode($collection));
        $validator = Validator::make(request()->all(), [
            'per_page' => ['integer', 'min:2', 'max:50'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 420);
        }

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        if(request()->has('per_page')) $perPage = (int) request()->per_page;
        
        $results = $collection->forPage($page, $perPage);//devuelve una nueva colección que contiene los artículos que estarían presentes en un número de página dado. El método acepta el número de página como su primer argumento y el número de elementos a mostrar por página como segundo argumento:
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),//esta opcion es la ruta que se utilizara para determinar el recurso actual y la pagina donde estamos en el momento
            'query' => request()->query(),//Para que tengo en cuenta tambien los parametroa de ordenacion
        ]);
        
        return $paginated;
    }   

    protected function transformData($data, $transformer)
    {
        return fractal($data, new $transformer)->toArray();
    }

    protected function cacheResponse($data)
    {
        $url = url()->current();
        Log::info('cacheResponse url = '.$url);
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = $url."?".$queryString;

        //la url la usamos como parametro unico, que es la url en cuestion
        return Cache::remember($fullUrl, 60, function() use($data){
            return $data;
        });
    }
}

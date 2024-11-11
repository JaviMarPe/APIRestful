<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /* Con este metodo transformamos los parametros de respuesta y llamada en las peticiones POST y PATCH para que no se manejen los atributos por defecto, ya que son sensibles a cualquier problema de seguridad */
    public function handle(Request $request, Closure $next, $transformer): Response
    {
        /*$transformedInput = [];

        foreach ($request->request->all() as $key => $value) {
            $transformedInput[$transformer::originalAttribute($key)] = $value;
        }
            
        $request->replace($transformedInput);
        */

        $transformedInput = Collection::make($request->all())
            ->mapWithKeys(function ($value, $key) use ($transformer) {
                return [$transformer::originalAttribute($key) ?? $key => $value];
            });

        $request->replace($transformedInput->all());

        $response = $next($request);

        Log::info("Transform Input handle response = ".json_encode($response));

        if ($response instanceof Response && $response->getStatusCode() === 420) {
            $data = json_decode($response->getContent(), true);

            if (isset($data['original'])) {
                $transformedErrors = Collection::make($data['original'])
                    ->mapWithKeys(function ($messages, $field) use ($transformer) {
                        $transformedField = $transformer::transformedAttribute($field);
                        return [$transformedField => array_map(function ($message) use ($field, $transformedField) {
                            return str_replace($field, $transformedField, $message);
                        }, $messages)];
                    });

                $data['errors'] = $transformedErrors->all();
                $response->setContent(json_encode($data));
            }
        }

        return $response;
    }
}

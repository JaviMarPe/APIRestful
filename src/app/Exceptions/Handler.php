<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /*protected $exceptionMap = [
        ValidationException::class => [
            'method' => 'convertValidationExceptionToResponse',
            'code' => 422
        ],
        ModelNotFoundException::class => [
            'code' => 402
        ],
        AuthenticationException::class => [
            'method' => 'unauthenticated',
            'code' => 401
        ],
        AuthorizationException::class => [
            'code' => 403
        ],
        NotFoundHttpException::class => [
            'code' => 404
        ],
        MethodNotAllowedException::class => [
            'code' => 405
        ],
        QueryException::class => [
            'code' => 406
        ],
        HttpException::class => [
            'code' => 415
        ]
    ];*/

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->stopIgnoring(HttpException::class);

        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, Request $request) {

            //return $this->handleException($e, $request);

            if ($e instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse($e, $request);
            }

            if ($e instanceof ModelNotFoundException) {
                return $this->errorResponse($e->getMessage(), 402);
            }

            if ($e instanceof AuthenticationException) {
                return $this->unauthenticated($request, $e);
            }

            if ($e instanceof AuthorizationException) {
                return $this->errorResponse($e->getMessage(), 403);
            }

            if ($e instanceof NotFoundHttpException) {
                return $this->errorResponse($e->getMessage(), 404);
            }

            if ($e instanceof MethodNotAllowedException) {
                return $this->errorResponse($e->getMessage(), 405);
            }

            if ($e instanceof HttpException) {
                return $this->errorResponse($e->getMessage(), 406);
            }

            if($e instanceof QueryException){
                return $this->errorResponse($e->getMessage(), 407);
            }

            // Para cualquier otra excepción no manejada
            if(config('app.debug')){
                return $this->errorResponse($e->getMessage(), 500);
            }
            return $this->errorResponse('Unexpected exception. Try later', 500);
        });
    }

    protected function handleException(Throwable $e, Request $request)
    {
        foreach ($this->exceptionMap as $exceptionType => $handler) {
            if($e instanceof $exceptionType){
                return $this->handleMappedException($e, $request, $handler);
            }
        }

        // Para cualquier otra excepción no manejada
        return $this->errorResponse('Unexpected exception. Try later', 500);
    }

    protected function handleMappedException(Throwable $e, Request $request, $handler)
    {
        if(isset($handler['method'])){
            return $this->{$handler['method']}($e, $request);
        }

        $message = $handler['message'] ?? $e->getMessage();
        $code = $handler['code'] ?? 500;

        return $this->errorResponse($message, $code);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('No autenticado', 401);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        /*if ($e->response) {
            return $e->response;
        }*/

        $errors = $e->validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);

        /*return $this->shouldReturnJson($request, $e)
                    ? $this->invalidJson($request, $e)
                    : $this->invalid($request, $e);*/
    }
}

<?php

namespace App\Exceptions;

use App\Utils\ErrorType;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Passport\Exceptions\MissingScopeException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    // public function register()
    // {
    //     $this->reportable(function (Throwable $e) {
    //         //
    //     });
    // }

    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return jsend_error(
                str_replace('App\\', '', $exception->getModel()) . ' not found',
                $exception->getCode(),
                ErrorType::RESOURCE_NOT_FOUND_ERROR,
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof MissingScopeException && $request->wantsJson()) {
            return jsend_fail(['message' => "Unauthenticated."], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return parent::render($request, $exception);
    }
}

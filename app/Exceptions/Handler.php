<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $e
     * @return void
     * @throws
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $e
     * @return JsonResponse
     */
    public function render($request, Exception $e)
    {
        // set default error code
        $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
        $error = $e->getMessage();

        // handle API exceptions
        if ($request->expectsJson()) {
            // expired / has no valid token
            if ($e instanceof AuthenticationException) {
                $code = 401;
            }

            // expired / has no valid token
            if ($e instanceof ValidationException) {
                $error = $e->errors();
                $code = 422;
            }

            return response()->json(
                [
                    'code' => $code,
                    'error' => $error,
                ],
                $code
            );
        }

        return parent::render($request, $e);
    }
}

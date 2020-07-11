<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof UnauthorizedException) {
            return redirect()->back();
        }

        if ($request->expectsJson()) {
            if($exception instanceof ModelNotFoundException){
                return response()->json([
                    'success' => false,
                    'message' => "Data is Empty",
                ], Response::HTTP_NOT_FOUND);
            }
        }

        if($exception instanceof NotFoundHttpException){
            return response()->json([
                'success' => false,
                'message' => "Route not found",
            ], Response::HTTP_NOT_FOUND);
        }

        if($exception instanceof QueryException){
            return response()->json([
                'success' => false,
                'message' => "Check your query",
            ], Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $exception);
    }
}

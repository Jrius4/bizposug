<?php

namespace App\Exceptions;

use Exception;
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
     *
     * @throws \Exception
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception)) {
            $code = $exception->getStatusCode();

            if ($code ==  '404') {
                return response()->view('error-pages.404');
            }
        }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            $error = 'The session has expired due to inactivity. Please try again';
            return response()
                ->view('error-pages.419', compact('error'));
            // ->withInput($request->except(['password', 'password_confirmation']))
            // ->with();
        }
        return parent::render($request, $exception);
    }
}

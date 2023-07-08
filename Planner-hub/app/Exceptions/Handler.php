<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
    public function register() : void
    {
    
        // Fill this with your switch of API responses to exceptions you wish to handle.
        $this->renderable(function (Throwable $e, $request) {

            $response = null;

            if ($request->is('api/*'))
            {
    
                switch (get_class($e))
                {
                    case MethodNotAllowedHttpException::class:
                        $response = $this->error('Error', config('constant.STATUS_CODE.methodNotAllowed'), 'The specified method for the request is invalid');
                        break;
                    
    
                    case NotFoundHttpException::class:
                        $response = $this->error('Error', config('constant.STATUS_CODE.statusNotFound'), 'The specified URL cannot be found');
                        break;

                    case HttpException::class:
                    case ModelNotFoundException::class: 
                    case AuthenticationException::class:
                        $response = $this->error('Error', config('constant.STATUS_CODE.internalServerError'), $e->getMessage());
                        break;
                    
                    default:
                        $response = $this->error('Error', config('constant.STATUS_CODE.internalServerError'), 'Unexpected Exception. Try later');
                        break;

                }
    
            }

            return $response;
    
        });
    }


    private function error($value, $code, $detail = null)
    {
        $errors = [];
        $errors['message'] = $value;
        if ($detail) {
            $errors['detail'] = $detail;
        }

        return  response(
            [
            "status" => $code,
            "data" => null,
            "errors" => $errors
            ], $code
        );
    }

}

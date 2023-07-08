<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Exception;
class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        // always make request "AJAX" when in the apiresponse middle ware
        // this will stop Laravel validators from redirecting to the "previous" page
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        // run this middleware api response after request
        // do this by firing the private api method after the pipeline is handled
        $response  =  $next($request);
        return  $this->api($request, $response);
    }

    //is called from handle and passed the closure and request and checks if it is not a 500
    private function api($request, $response)
    {
        // Checks if there is an exception and handles it appropriately
        if (isset($response->exception) && $response->exception) {

            if (get_parent_class($response->exception) == 'App\Exceptions\ApiException') {
                return $this->error($response->exception->getMessage(), $response->exception->getStatusCode(), $response->exception->getErrors());
            }

            if (method_exists($response->exception, 'getMessage') && method_exists($response->exception, 'getStatusCode')) {
                return $this->error($response->exception->getMessage(), $response->exception->getStatusCode());
            }

            if (method_exists($response->exception, 'getStatusCode')) {
                return $this->error($response->exception->getMessage(), $response->exception->getStatusCode());
            }

            if (method_exists($response->exception, 'getMessage')) {
                return $this->error($response->exception->getMessage(), $response->getStatusCode());
            }

            return $this->response($response->content(), $response->getStatusCode());
        } elseif (get_class($response) == "Illuminate\\Http\\JsonResponse") {
            $code = $response->getStatusCode();
            $data = $response->getData();

            
        } elseif (is_object($response->getOriginalContent()) && get_class($response->getOriginalContent()) == "Illuminate\\View\\View") {
            $code = $response->getStatusCode();
            $data = $response->getContent();
        } else {
            // tests to see what http method was used and respond with the appropriate code
            $method = strtolower($request->method());
            $code = 401;
          //  $code = $response->getOriginalContent() ? \Config::get("httpresponse.$method.success") : \Config::get("httpresponse.$method.failure");
            $data = $response->getOriginalContent();
        }

        return $this->response($data, $code);
    }

    private function response($value, $code)
    {
        //is called in all HTTP request methods to handle the structure of data returned.
        return response(
            [
            "status" => $code,
            "data" => $value
            ], $code
        );
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
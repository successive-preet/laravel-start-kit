<?php 

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('genericResponse')) {
  function genericResponse($result,$status, $message = '')
  {
    if ($message != '') {
      $msgText = $message;
    } else {
      switch ($status) {
        case config('constant.STATUS_CODE.statusOk'):
          $msgText = 'Ok';
          break;
        case config('constant.STATUS_CODE.statusCreated'):
          $msgText = 'Created';
          break;
        case config('constant.STATUS_CODE.statusUnauthorized'):
          $msgText = 'Unauthorized';
          break;
        case config('constant.STATUS_CODE.statusForbidden'):
          $msgText = 'Forbidden';
          break;
        case config('constant.STATUS_CODE.statusNotFound'):
          $msgText = 'Not Found';
          break;
        case config('constant.STATUS_CODE.statusUnprocessable'):
          $msgText = 'Unprocessable Entity';
          break;
        case config('constant.STATUS_CODE.alreadyExist'):
          $msgText = 'Already Exist';
          break;
        case config('constant.STATUS_CODE.methodNotAllowed'):
          $msgText = 'Method Not Allowed';
          break;
      }
    }
    return response()->json(['data' => $result, 'message' => $msgText, 'status' => $status], $status);
  }
}


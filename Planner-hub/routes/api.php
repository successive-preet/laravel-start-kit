<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\{AuthController, UserController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'apiResponseMiddleware'], function(){

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    /* ------------------------ For Password Grant Token ------------------------ */
    Route::post('refresh-token',   [AuthController::class, 'refreshToken']);

});

Route::group(['middleware' => ['auth:api', 'apiResponseMiddleware']], function(){
    Route::post('user-details', [UserController::class, 'userDetails']);
    Route::post('logout', [AuthController::class, 'logout']);
});


Route::get('unauthorized', function () {
    return response()->json([
        'error' => 'Unauthorized.'
    ], 401);
})->name('unauthorized');
    

<?php

use App\Http\Controllers\API\CityController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\Provider\AuthController;
use App\Http\Controllers\API\Provider\BookingController;
use App\Http\Controllers\API\Provider\DepartmentController;
use App\Http\Controllers\API\Provider\DocumentController;
use App\Http\Controllers\API\Provider\UserController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\RatingController;
use App\Http\Controllers\API\SuggestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([

    'prefix' => 'auth'

], function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::post('reset_password', [AuthController::class, 'reset']);
    Route::get('cities',[CityController::class, 'index' ]);
    Route::get('departments',[DepartmentController::class, 'index' ]);

});


Route::group([

    'middleware' => 'auth:api',
    'prefix' => 'auth'

], function () {

    Route::get('logout',  [AuthController::class, 'logout']);
    Route::post('refresh',  [AuthController::class, 'refresh']);

    Route::get('me' ,  [UserController::class, 'me']);
    Route::post('user' ,  [UserController::class, 'update']);

    Route::get('document/destroy/{id}' ,  [DocumentController::class, 'destroy']);

    Route::post('rate',[RatingController::class, 'store' ]);

    Route::get('booking',[BookingController::class, 'index' ]);
    Route::get('booking/{id}',[BookingController::class, 'show' ]);

    Route::get('notifications',[NotificationController::class, 'index' ]);

    Route::get('questions',[QuestionController::class, 'index' ]);

    Route::post('suggestion',[SuggestionController::class, 'store' ]);

});

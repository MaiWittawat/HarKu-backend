<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProfileImageController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', [UserController::class, 'test']);
Route::post('/register', [UserController::class, 'registeration']);

Route::post('/getAllUser', [UserController::class, 'getAllUser']);

Route::post('/getUser', [UserController::class, 'getUser']);
Route::get('/getUserForMatch/{email}', [UserController::class, 'getUserForMatch']);
Route::get('/getInterests', [UserController::class, 'getInterests']);
Route::post('/changePassword', [UserController::class, 'changePassword']);
Route::post('/editProfile', [UserController::class, 'editProfile']);
Route::post('/like', [UserController::class, 'like']);
Route::post('isMatch', [UserController::class, 'isMatch']);
Route::post('/myMatch', [UserController::class, 'myMatch']);
Route::post('/myLike', [UserController::class, 'myLike']);
Route::post('/likeMe', [UserController::class, 'likeMe']);
Route::post('/updateStatus', [UserController::class, 'updateStatus']);

Route::post('/message', [MessageController::class, 'storeMessage']);
Route::post('/chat', [MessageController::class, 'getMessage']);
Route::get('/lastMessageIndex', [MessageController::class, 'lastMessageIndex']);

Route::get('/getChatList/{email}', [MessageController::class, 'getChatList']);

Route::post('/upload-image', [ProfileImageController::class, 'uploadFile']);
Route::get('/getProfileImages/{email}', [ProfileImageController::class, 'getProfileImages']);
Route::get('/getImage/{email}',[ProfileImageController::class, 'getImage']);


Route::post('/addReport', [ReportController::class, 'addReport']);
Route::get('/getAllReport', [ReportController::class, 'getAllReport']);


// Match Controller
Route::get('/getMatch/{email}', [MatchController::class, 'getMatch']);
Route::get('/getMatchesBy/{email}', [MatchController::class, 'getMatchesBy']);
Route::get('/getMatchesTo/{email}', [MatchController::class, 'getMatchesTo']);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});

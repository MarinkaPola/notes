<?php

use App\Http\Controllers\ApiControllers\AuthController;
use App\Http\Controllers\ApiControllers\HomeIndexController;
use App\Http\Controllers\ApiControllers\NoteController;
use App\Http\Controllers\ApiControllers\Notes\AttachmentController;
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


// public routes
Route::get('/notes/{uuid}', [NoteController::class, 'show']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::get('/', [HomeIndexController::class, 'index']);

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('/notes/{uuid}/attachments/{id}/download', [AttachmentController::class, 'download']);

    Route::resource('notes.attachments', AttachmentController::class,[
        'parameters' => [
            'notes' => 'uuid',
            'attachments' => 'id'
        ],
    ]);

    Route::patch('/notes/{uuid}/detach', [NoteController::class, 'detachFile']);
    Route::post('/notes/{uuid}/share', [NoteController::class, 'share']);
    Route::get('/notes-shared-you', [NoteController::class, 'notesSharedYou']);


    Route::resource('/notes', NoteController::class, [
        'parameters' => [
            'notes' => 'uuid'
        ],
    ])->except(['show']);
});


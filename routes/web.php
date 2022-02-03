<?php

use App\Http\Controllers\HomeIndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [HomeIndexController::class, 'index']);

Route:: get('/redirect/{provider}', [LoginController::class, 'redirectToProvider']);
Route:: get('/callback/{provider}', [LoginController::class, 'handleProviderCallback']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::patch('/notes/{uuid}/detach', [NoteController::class, 'detachFile']);
    Route::post('/notes/{uuid}/share', [NoteController::class, 'share']);
    Route::get('/notes-shared-you', [NoteController::class, 'notesSharedYou']);


    Route::resource('/notes', NoteController::class, [
        'parameters' => [
                'notes' => 'uuid'
            ],
    ])->except(['show']);
});

Route::get('/notes/{uuid}', [NoteController::class, 'show'])->name('notes.show');
Route::get('/notes/{uuid}/download', [NoteController::class, 'download']);

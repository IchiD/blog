<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PagesController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PagesController::class, 'index']);
Route::get('/topic/detail/{id}', [PagesController::class, 'detail'])->name('topic.detail');

// Route::middleware(['auth', 'verified'])->group(function () {
Route::post('/', [PagesController::class, 'save']);
Route::get('/topic/edit/{id}', [PagesController::class, 'edit'])->name('topic.edit');
Route::put('/topic/edit/update/{id}', [PagesController::class, 'update'])->name('topic.update');
Route::delete('/topic/delete/{id}', [PagesController::class, 'destroy'])->name('topic.delete');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// いいね機能
Route::post('topic/like/{topic}', [PagesController::class, 'like'])->name('post.like');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
// });

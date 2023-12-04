<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
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

Route::get('/', function () {
    return view('webpage.login');
});

Route::get('/newsfeed', function () {
    return view('webpage.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
Route::get('/profile', [AuthController::class, 'profileView']);
Route::get('/profile/edit', [AuthController::class, 'showEditProfilePage']);
Route::post('/editUser', [AuthController::class, 'editUser']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/singlePost/{id}', [PostController::class, 'viewSinglePost']);
Route::get('/newsfeed', [PostController::class, 'viewAllPost']);
Route::get('/deletePost/{id}', [PostController::class, 'deletePost']);
Route::get('/editPost/{id}', [PostController::class, 'showUpdatePost']);
Route::post('/updatePost/{id}', [PostController::class, 'updatePost']);
Route::post('/createPost', [PostController::class, 'createPost']);
Route::post('/createComment/{uuid}', [CommentController::class, 'createComment'])->name('createComment');

require __DIR__.'/auth.php';

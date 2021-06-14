<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', function () {
    return redirect()->route('hunting.index');
})->name('home');

// ProfileController
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

// ChangeDistrictController
Route::get('/district/change/{district_id}', [App\Http\Controllers\ChangeDistrictController::class, 'changeCurrent'])
    ->name('district.change');

// AuthorizationController
Route::get('/authorizations', [App\Http\Controllers\AuthorizationController::class, 'index'])->name('authorization.index');
Route::get('/authorization/create', [App\Http\Controllers\AuthorizationController::class, 'create'])->name('authorization.create');
Route::post('/authorization', [App\Http\Controllers\AuthorizationController::class, 'store'])->name('authorization.store');

// HuntingBookController
Route::get('/hunting-book', [App\Http\Controllers\HuntingBookController::class, 'index'])->name('hunting.index');
Route::get('/hunting/create', [App\Http\Controllers\HuntingBookController::class, 'create'])->name('hunting.create');
Route::post('/hunting', [App\Http\Controllers\HuntingBookController::class, 'store'])->name('hunting.store');
Route::get('/hunting/edit/{id}', [App\Http\Controllers\HuntingBookController::class, 'edit'])->name('hunting.edit');
Route::patch('/hunting/{id}', [App\Http\Controllers\HuntingBookController::class, 'update'])->name('hunting.update');

Route::patch('/hunting/cancel/{id}', [App\Http\Controllers\HuntingBookController::class, 'cancel'])->name('hunting.cancel');
Route::patch('/hunting/finish/{id}', [App\Http\Controllers\HuntingBookController::class, 'finish'])->name('hunting.finish');

Route::middleware('can:admin-only')->group(function () {
    // UserController
    Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('user.index');
    Route::get('/admin/user/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('user.create');
    Route::post('/admin/user/create', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('user.store');
    Route::get('/admin/user/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('user.edit');
    Route::patch('/admin/user/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('user.update');
    Route::patch('/admin/user/block/{id}', [App\Http\Controllers\Admin\UserController::class, 'block'])->name('user.block');

    // DistrictController
    Route::get('/admin/districts', [App\Http\Controllers\Admin\DistrictController::class, 'index'])->name('district.index');
    Route::get('/admin/district/create', [App\Http\Controllers\Admin\DistrictController::class, 'create'])->name('district.create');
    Route::post('/admin/district', [App\Http\Controllers\Admin\DistrictController::class, 'store'])->name('district.store');
    Route::get('/admin/district/edit/{id}', [App\Http\Controllers\Admin\DistrictController::class, 'edit'])->name('district.edit');
    Route::patch('/admin/district/{id}', [App\Http\Controllers\Admin\DistrictController::class, 'update'])->name('district.update');

    // HuntingGroundController
    Route::get('/admin/district/{district_id}/hunting-grounds', [App\Http\Controllers\Admin\HuntingGroundController::class, 'index'])
        ->name('hunting-ground.index');
    Route::get('/admin/district/{district_id}/hunting-ground/create', [App\Http\Controllers\Admin\HuntingGroundController::class, 'create'])
        ->name('hunting-ground.create');
    Route::post('/admin/district/{district_id}/hunting-ground', [App\Http\Controllers\Admin\HuntingGroundController::class, 'store'])
        ->name('hunting-ground.store');
    Route::get('/admin/district/{district_id}/hunting-ground/edit/{id}', [App\Http\Controllers\Admin\HuntingGroundController::class, 'edit'])
        ->name('hunting-ground.edit');
    Route::patch('/admin/district/{district_id}/hunting-ground/{id}', [App\Http\Controllers\Admin\HuntingGroundController::class, 'update'])
        ->name('hunting-ground.update');
});

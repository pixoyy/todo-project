<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:global', 'guest'])->group(function () {
    Route::prefix('/login')->group(function () {
        Route::get('/', [AuthController::class, 'loginView'])->name('login');
        Route::post('/', [AuthController::class, 'login'])->name('login_post');
    });


    // Route::prefix('/forgot-password')->group(function () {
    //     Route::get('/', [AuthController::class, 'forgotPasswordView'])->name('forgot-password');
    //     Route::post('/', [AuthController::class, 'forgotPassword'])->name('forgot-password_post');
    // });

    // Route::prefix('/reset-password')->group(function () {
    //     Route::get('/', [AuthController::class, 'resetPasswordView'])->name('reset-password');
    //     Route::post('/', [AuthController::class, 'resetPassword'])->name('reset-password_post');
    // });
});

Route::middleware(['throttle:global', 'auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('access:read');

    Route::prefix('/projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('projects')->middleware('access:read');
        Route::get('/data', [ProjectController::class, 'data'])->name('projects_data')->middleware('access:read');
        Route::get('/add', [ProjectController::class, 'add'])->name('projects_add')->middleware('access:create');
        Route::post('/add', [ProjectController::class, 'create'])->name('projects_create')->middleware('access:create');
        Route::get('/{id}', [ProjectController::class, 'detail'])->name('projects_detail')->middleware('access:read');
        Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('projects_edit')->middleware('access:update');
        Route::patch('/edit/{id}', [ProjectController::class, 'update'])->name('projects_update')->middleware('access:update');
        Route::delete('/{id}', [ProjectController::class, 'delete'])->name('projects_delete')->middleware('access:delete');
    });

    Route::prefix('/categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories')->middleware('access:read');
        Route::get('/data', [CategoryController::class, 'data'])->name('categories_data')->middleware('access:read');
        Route::get('/add', [CategoryController::class, 'add'])->name('categories_add')->middleware('access:create');
        Route::post('/add', [CategoryController::class, 'create'])->name('categories_create')->middleware('access:create');
        Route::get('/{id}', [CategoryController::class, 'detail'])->name('categories_detail')->middleware('access:read');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('categories_edit')->middleware('access:update');
        Route::patch('/edit/{id}', [CategoryController::class, 'update'])->name('categories_update')->middleware('access:update');
        Route::delete('/{id}', [CategoryController::class, 'delete'])->name('categories_delete')->middleware('access:delete');
    });

    Route::prefix('/tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks')->middleware('access:read');
        Route::get('/data', [TaskController::class, 'data'])->name('tasks_data')->middleware('access:read');
        Route::get('/add', [TaskController::class, 'add'])->name('tasks_add')->middleware('access:create');
        Route::post('/add', [TaskController::class, 'create'])->name('tasks_create')->middleware('access:create');
        Route::get('/{id}', [TaskController::class, 'detail'])->name('tasks_detail')->middleware('access:read');
        Route::get('/edit/{id}', [TaskController::class, 'edit'])->name('tasks_edit')->middleware('access:update');
        Route::patch('/edit/{id}', [TaskController::class, 'update'])->name('tasks_update')->middleware('access:update');
        Route::delete('/{id}', [TaskController::class, 'delete'])->name('tasks_delete')->middleware('access:delete');
    });
    Route::prefix('/roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles')->middleware('access:read');
    });
    Route::prefix('/users')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('users')->middleware('access:read');
    });
    Route::prefix('/authorization')->group(function () {
        Route::get('/', [AuthorizationController::class, 'index'])->name('authorization')->middleware('access:read');
    });
});

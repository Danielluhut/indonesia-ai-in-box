<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\MaintenanceDashboardController;
use App\Http\Controllers\UserContainerController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DockerController;
use App\Http\Controllers\AdminPackageController;
use App\Http\Controllers\AdminMachineController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/services/action', [AdminDashboardController::class, 'serviceAction'])->name('admin.service.action');
    Route::post('/admin/containers/action', [AdminDashboardController::class, 'containerAction'])->name('admin.container.action');

    Route::post('/machines', [AdminDashboardController::class, 'storeMachine'])->name('admin.machines.store');
    Route::post('/machines/remove', [AdminDashboardController::class, 'removeMachine'])->name('admin.machines.remove');
    Route::post('/packages/install', [AdminDashboardController::class, 'installPackage'])->name('admin.packages.install');

    // Optional: jika masih ingin melihat logs per container
    Route::get('/admin-dashboard/logs/{id}', [AdminDashboardController::class, 'logs'])->name('admin.containers.logs');


});

// User routes
Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::get('/user-dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/user/containers', [UserContainerController::class, 'store'])->name('user.containers.store');
    Route::get('/user-dashboard/install-package', [UserDashboardController::class, 'showInstallForm'])->name('user.package.form');
    Route::post('/user-dashboard/install-package', [UserDashboardController::class, 'installPackage'])->name('user.package.install');
    Route::post('/user/containers/{id}/start', [UserDashboardController::class, 'start'])->name('user.containers.start');
    Route::post('/user/containers/{id}/stop', [UserDashboardController::class, 'stop'])->name('user.containers.stop');
    Route::delete('/user/containers/{id}', [UserDashboardController::class, 'delete'])->name('user.containers.delete');
    Route::post('/user/containers', [UserContainerController::class, 'store'])->name('user.containers.store');
    Route::post('/user/service-action', [UserDashboardController::class, 'serviceAction'])->name('user.service.action');

});

// Maintenance routes
Route::middleware(['auth', 'role:maintenance'])->group(function () {
    Route::get('maintenance-dashboard', [MaintenanceDashboardController::class, 'index'])->name('maintenance.dashboard');
    Route::post('maintenance-dashboard/restart', [MaintenanceDashboardController::class, 'restart'])->name('maintenance.container.restart');
    Route::get('maintenance-dashboard/log/{id}', [MaintenanceDashboardController::class, 'log'])->name('maintenance.container.log');
    Route::post('maintenance-dashboard/restart-all', [MaintenanceDashboardController::class, 'restartAll'])->name('maintenance.container.restartAll');
});

Route::middleware('redirect.role')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
});

Route::get('/admin/logs/{id}', [AdminDashboardController::class, 'logs']);


Route::get('/docker/search', [DockerController::class, 'search']);

require __DIR__ . '/auth.php';
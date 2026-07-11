<?php

use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminAuthController;
use App\Models\Project;

// Home
Route::get('/', function () {
    $projects = Project::orderBy('order')->get();
    return view('home', compact('projects'));
});

// Contact
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin Auth (tidak perlu login dulu)
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Panel (perlu login)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::resource('projects', ProjectController::class);
});

// Admin Panel (protected)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/messages', [AdminDashboardController::class, 'messages'])->name('messages');
    Route::patch('/messages/{message}/read', [AdminDashboardController::class, 'markRead'])->name('messages.read');
    Route::delete('/messages/{message}', [AdminDashboardController::class, 'destroyMessage'])->name('messages.destroy');
    Route::resource('projects', ProjectController::class);
});


Route::view('/personal', 'personal');
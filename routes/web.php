<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PantiController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/panti/{id}', [PantiController::class, 'show'])->name('panti.detail');

Route::middleware('auth')->group(function () {
    Route::get('/', function (){
        $user = Auth::user();
        
        Log::info('Currently User logged in:', ['user' => $user]);

        if($user->role == 'donor'){
            return view('page.home.home-donor');
        }else if($user->role== 'panti'){
            return view('page.home.home-panti', ['user' => $user]);
        }else if($user->role == 'admin'){
            return view('page.home.home-admin', ['user' => $user]);
        }
    })->name('home');

    Route::get('/catalog', function () {
        return view('page.catalog');
    })->name('catalog');
    
    Route::get('/panti', function () {
        return view('page.panti');
    })->name('panti');
    
    Route::get('/about', function () {
        return view('page.about');
    })->name('about');

    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
    
    Route::get('/panti/{id}', [PantiController::class, 'show'])->name('panti.detail');

    Route::get('/profile', function () {
        $user = Auth::user(); // Get the authenticated user
        return view('page.profile', ['user' => $user]);
    })->name('profile');

    Route::post('/logout', [AuthController::class, 'logout']);
});
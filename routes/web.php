<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/', function (){
        $user = Auth::user();

        if($user->role == 'donor'){
            return view('page.home.home-donor', ['user' => $user]);
        }else if($user->role== 'panti'){
            return view('page.home.home-panti', ['user' => $user]);
        }else if($user->role == 'admin'){
            return view('page.home.home-admin', ['user' => $user]);
        }
    });

    Route::get('/catalog', function () {
        return view('page.catalog');
    });

    Route::get('/panti', function () {
        return view('page.panti');
    });

    Route::get('/about-us', function () {
        return view('page.about-us');
    });

    Route::get('/profile', function () {
        $user = Auth::user(); // Get the authenticated user
        return view('page.profile', ['user' => $user]);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

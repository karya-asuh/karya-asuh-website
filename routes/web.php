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

Route::post('/midtrans/notification', [ProductController::class, 'handleMidtransNotification']);

Route::middleware('auth')->group(function () {
    $user = Auth::user();
    Route::get('/', function (){
        $user = Auth::user();
        
        Log::info('Currently User logged in:', ['user' => $user]);

        if($user->role == 'donor'){
            $productController = new ProductController;
            $products = $productController->showAll()->getData();

            $pantiController = new PantiController;
            $pantis = $pantiController->showAll()->getData();

            return view('page.home.home-donor', [
                'creations' => $products,
                'pantis' => $pantis
            ]);
        }else if($user->role== 'panti'){
            $productController = new ProductController;
            $products = $productController->showOwnProducts()->getData();

            $pantiController = new AuthController;
            $panti = $pantiController->showUserDetail()->getData();

            return view('page.home.home-panti', [
                'creations' => $products,
                'panti' => $panti
            ]);
        }else if($user->role == 'admin'){
            $pantiController = new PantiController;
            $withdraws = $pantiController->withdrawAll()->getData();
            return view('page.home.home-admin', ['withdraws' => $withdraws]);
        }
    })->name('home');

    Route::get('/add-creation', function(){
        return view('page.add-creation');
    })->name('add-creation');

    Route::post('/add-creation', [ProductController::class, 'store'])->name('add-creation');

    Route::post('/generate-snap-token/{id}', [ProductController::class, 'generateSnapToken'])->name('generate-snap-token');

    Route::get('/catalog', [ProductController::class, 'showSearch'])->name('catalog');
    
    Route::get('/panti', [PantiController::class, 'showSearch'])->name('panti');
    
    Route::get('/about', function () {
        return view('page.about');
    })->name('about');

    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
    
    Route::get('/panti/{id}', [PantiController::class, 'show'])->name('panti.detail');

    Route::get('/profile', function () {
        $user = Auth::user(); // Get the authenticated user
        if($user->role == 'donor'){
            $productController = new ProductController;
            $products = $productController->showSuccessCreation()->getData();
            return view('page.profile.profile-donor', ['creations' => $products]);
        }else if($user->role== 'panti'){
            $authController = new AuthController;
            $panti_details = $authController->showUserDetail()->getData();
            return view('page.profile.profile-panti', ['panti_details' => $panti_details]);
        }else if($user->role == 'admin'){
            return view('page.profile.profile-admin');
        }
    })->name('profile');

    Route::post('/withdraw', [PantiController::class, 'withdrawFund']);

    Route::post('/accept-withdraw/{id}', [PantiController::class, 'acceptWithdraw'])->name('accept-withdraw');

    Route::post('/logout', [AuthController::class, 'logout']);
});
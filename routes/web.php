<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\LendingsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BorrowingsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\ApplicationSettingsController;

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

// Authentication Routes...
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Web Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware(['can:index dashboard']);

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', [SettingsController::class, 'edit'])->name('edit');
        Route::patch('/', [SettingsController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function () {
        Route::get('/', [TransactionsController::class, 'index'])->name('index')->middleware(['can:index transactions']);
        Route::post('/', [TransactionsController::class, 'store'])->name('store')->middleware(['can:create transactions']);
        Route::delete('/', [TransactionsController::class, 'destroy'])->name('destroy')->middleware(['can:delete transactions']);
    });

    Route::group(['prefix' => 'lendings', 'as' => 'lendings.'], function () {
        Route::get('/', [LendingsController::class, 'index'])->name('index')->middleware(['can:index dues']);
        Route::patch('/', [LendingsController::class, 'update'])->name('update')->middleware(['can:update dues']);
        Route::post('/', [LendingsController::class, 'store'])->name('store')->middleware(['can:create dues']);
        Route::delete('/', [LendingsController::class, 'destroy'])->name('destroy')->middleware(['can:delete dues']);
    });

    Route::group(['prefix' => 'borrowings', 'as' => 'borrowings.'], function () {
        Route::get('/', [BorrowingsController::class, 'index'])->name('index')->middleware(['can:index dues']);
        Route::patch('/', [BorrowingsController::class, 'update'])->name('update')->middleware(['can:update dues']);
        Route::post('/', [BorrowingsController::class, 'store'])->name('store')->middleware(['can:create dues']);
        Route::delete('/', [BorrowingsController::class, 'destroy'])->name('destroy')->middleware(['can:delete dues']);
    });

    Route::group(['prefix' => 'cash', 'as' => 'cash.'], function () {
        Route::get('/', [CashController::class, 'index'])->name('index')->middleware(['can:index cash']);
        Route::post('/', [CashController::class, 'store'])->name('store')->middleware(['can:create cash']);
        Route::delete('/', [CashController::class, 'update'])->name('update')->middleware(['can:update cash']);
    });

    Route::group(['prefix' => 'people', 'as' => 'people.'], function () {
        Route::get('/', [PersonController::class, 'index'])->name('index')->middleware(['can:index people']);
        Route::post('/', [PersonController::class, 'store'])->name('store')->middleware(['can:create people']);
        Route::delete('/', [PersonController::class, 'destroy'])->name('destroy')->middleware(['can:delete people']);
        Route::get('/{person}', [PersonController::class, 'show'])->name('show')->middleware(['can:show people']);
    });

    Route::group(['prefix' => 'accounts', 'as' => 'accounts.'], function () {
        Route::get('/', [AccountController::class, 'index'])->name('index')->middleware(['can:index accounts']);
        Route::post('/', [AccountController::class, 'store'])->name('store')->middleware(['can:create accounts']);
        Route::get('/{account}/edit', [AccountController::class, 'edit'])->name('edit')->middleware(['can:update accounts']);
        Route::patch('/{account}', [AccountController::class, 'update'])->name('update')->middleware(['can:update accounts']);
        Route::delete('/', [AccountController::class, 'destroy'])->name('destroy')->middleware(['can:delete accounts']);
    });

    Route::group(['middleware' => ['role:admin'], 'prefix' => 'application/settings', 'as' => 'application.settings.'], function () {
        Route::get('/', [ApplicationSettingsController::class, 'index'])->name('index');
        Route::patch('/', [ApplicationSettingsController::class, 'update'])->name('update');
    });

    Route::get('/process', [ProcessController::class, 'index'])->name('process');

    Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
        Route::post('/loginlog', [AjaxController::class, 'getLoginLog'])->name('get_login_log');

        Route::group(['middleware' => 'role:admin'], function () {
            Route::post('/setstartdate', [AjaxController::class, 'setStartDate'])->name('set_start_date');
            Route::post('/clearcache', [AjaxController::class, 'clearCache'])->name('clear_cache');
        });
    });


});

// Localization
Route::get('/js/lang.js', function () {
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');

        $files   = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name           = basename($file, '.php');
            $strings[$name] = require $file;
        }

        return $strings;
    });

    header('Content-Type: text/javascript');
    echo('window.i18n = ' . json_encode($strings) . ';');
    exit();
})->name('assets.lang');
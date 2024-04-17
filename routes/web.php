<?php
use resources\views;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use app\Http\Controllers\HomeController;


Route::get('/insert', [AuthController::class, 'insert'])->name('insert');

//TODOS
//registro
Route::get('/', function () {
    return view('register');
})->name('/');
Route::post('/añadir', [AuthController::class, 'register'])->name('añadir');

//login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

//reenviar codigo
Route::get('/reenviarCodigo', [AuthController::class, 'reenviarCodigo'])->name('reenviarCodigo');




//AMBOS
Route::group(['middleware' => 'adminuser'], function () {
    // Bienvenida
    Route::get('/bienvenida', function () {
        return view('welcome');
    })->name('bienvenida');
});




Route::get('/doblefactor', function () {
    return view('doblefactor');
})->name('doblefactor');

Route::post('/verificarDobleFactor', [AuthController::class, 'verificarDobleFactor'])->name('verificarDobleFactor');

//ADMINISTRADORES
Route::group(['middleware' => 'admin'], function () {
    //DobleFactor

});




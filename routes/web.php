<?php

use Illuminate\Support\Facades\Route;

// これを記載することでルーティングのシンプルにする
use App\Http\Controllers\HomeController;

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
    return view('welcome');
});

Auth::routes();

// ゲットはURLにアクセスしてページを見に行く方法
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// ポストはformから何かを送信
// 第一引数がURL 第2引数はコントローラーのパス 第3引数にhome controllerのstoreメソッド
Route::post('/store', [HomeController::class, 'store'])->name('store');

// editページのルーティング。URLに🆔の数字を入れられるようにする
Route::get('/edit/{id}', [HomeController::class, 'edit'])->name('edit');

// updateのルーティング
Route::post('/update', [HomeController::class, 'update'])->name('update');

// 削除はPOSTメソッドで。Laravelでは削除はDestroy
Route::post('/destroy', [HomeController::class, 'destroy'])->name('destroy');
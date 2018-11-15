<?php

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
use App\Book;
use Illuminate\Http\Request;

Auth::routes();
//Route::get('/home', 'HomeController@index')->name('home');
//↓自動生成ソースを変更 
Route::get('/home', 'BooksController@index')->name('home');
// memo "php artisan make:auth"、"php artisan migrete"を実行した後に、画面が動かなくなった原因
// memo 自動生成ソースが ? >の外に書かれていたため。s

// 登録画面-初期表示
Route::get('/', 'BooksController@index');

// 本を登録
Route::post('/books', 'BooksController@store');

// 更新画面-初期表示
Route::post('/booksedit/{books}', 'BooksController@edit');

// 更新処理
Route::post('/books/update', 'BooksController@update');

// 削除処理
Route::delete('/books/{book}', 'BooksController@destroy');
// TODO x[book] カッコが間違っていた。
?>



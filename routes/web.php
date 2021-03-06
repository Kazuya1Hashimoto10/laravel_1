<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth'], function() {
  Route::get('/', 'HomeController@index')->name('home');

  Route::get('/folders/create', 'FolderController@showCreateForm')->name('folders.create');
  Route::post('/folders/create', 'FolderController@create');

  Route::group(['middleware' => 'can:view,folder'], function(){
    // フォルダ系
    Route::get('/folders/{folder}/edit', 'FolderController@showEditForm')->name('folders.edit');
    Route::post('/folders/{folder}/edit', 'FolderController@edit');

    Route::delete('/folders/{folder}/delete', 'FolderController@destroy')->name('folders.delete');

    // タスク系
    Route::get('/folders/{folder}/tasks', 'TaskController@index')->name('tasks.index');
    
    Route::get('/folders/{folder}/tasks/create', 'TaskController@showCreateForm')->name('tasks.create');
    Route::post('/folders/{folder}/tasks/create', 'TaskController@create');

    Route::get('/folders/{folder}/tasks/{task}/edit', 'TaskController@showEditForm')->name('tasks.edit');
    Route::post('/folders/{folder}/tasks/{task}/edit', 'TaskController@edit');

    Route::delete('/folders/{folder}/tasks/{task}/delete', 'TaskController@destroy')->name('tasks.delete');
  });
});

Auth::routes();

Route::get('login/google', 'Auth\LoginController@redirectToGoogle');
Route::get('login/google/callback', 'Auth\LoginController@handleGoogleCallback');
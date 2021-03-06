<?php

use App\Project;
use Illuminate\Support\Facades\Auth;
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


Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/projects/create', "ProjectsController@create");
    Route::post('/projects', 'ProjectsController@store')->name('ProjectsStore');
    Route::get('/projects', 'ProjectsController@index')->name('ProjectsIndex');
    Route::get('/projects/{project}', 'ProjectsController@show');
    Route::get('/projects/{project}/edit', 'ProjectsController@edit');
    Route::patch('/projects/{project}', 'ProjectsController@update');
    Route::delete('/projects/{project}', 'ProjectsController@destroy');

    Route::patch('/projects/{project}/notes', 'ProjectsController@updateNotes');

    Route::post('/projects/{project}/tasks', 'ProjectTasksController@store');
    Route::patch('/projects/{project}/tasks/{task}', 'ProjectTasksController@update');
    Route::delete('/projects/{project}/tasks/{task}', 'ProjectTasksController@destroy');

    Route::post('/projects/{project}/invitation', 'ProjectsController@invite');

});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

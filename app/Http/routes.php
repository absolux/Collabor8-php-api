<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
 * Projects routes
 */
Route::group(['middleware' => 'auth'], function() {
    
    /*
     * Projects routes
     */
    Route::get('projects/all', ['as' => 'projects.all', 'uses' => 'ProjectsController@all']);
    Route::post('projects/{projects}/archive', ['as' => 'projects.archive', 'uses' => 'ProjectsController@archive']);
    Route::post('projects/{projects}/restore', ['as' => 'projects.restore', 'uses' => 'ProjectsController@restore']);
    Route::resource('projects', 'ProjectsController', ['except' => ['create', 'edit']]);

    /*
     * Project labels routes
     */
    Route::resource('projects.labels', 'LabelsController', ['only' => ['store', 'update', 'destroy']]);

    /*
     * Tasks routes
     */
    Route::resource('projects.tasks', 'TasksController', ['except' => ['create', 'edit']]);
    
    /*
     * Project team routes
     */
    Route::resource('projects.team', 'TeamsController', ['only' => ['index', 'store', 'update', 'destroy']]);
    
});

Route::get('/', function () {
    return view('welcome');
});
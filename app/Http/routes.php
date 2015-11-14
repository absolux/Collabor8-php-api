<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Response;

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
    Route::get('projects/{projects}/tasks/{tasks}/activity', ['as' => 'projects.tasks.activity', 'uses' => 'TasksController@activity']);
    Route::post('projects/{projects}/tasks/{tasks}/activity', ['as' => 'projects.tasks.comment', 'uses' => 'TasksController@comment']);
    Route::resource('projects.tasks', 'TasksController', ['except' => ['create', 'edit']]);
    
    /*
     * Project team routes
     */
    Route::resource('projects.team', 'TeamsController', ['only' => ['index', 'store', 'update', 'destroy']]);
    
    /**
     * Users routes
     */
    Route::resource('users', 'UsersController', ['only' => ['index']]);
});

Route::post('/authenticate', function() {
    try {
        $credentials = Request::only('email', 'password');
        $authed = auth()->attempt($credentials, true);
        
        if ( $authed && ($token = auth()->getToken()) ) {
            $header = config('jwt.header');
            
            return (new Response())->header($header, (string) $token);
        }
    } catch (Exception $exc) {
        // Do nothing
    }

    return response('Unauthorized.', 401);
});

Route::get('/', function () {
    return view('welcome');
});

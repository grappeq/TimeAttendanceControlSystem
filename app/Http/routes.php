<?php

use Illuminate\Support\Facades\Auth;

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

Route::get('/', function() {
    if (Auth::check()) {
        return redirect('/home');
    }
    return view('auth/login');
});

Route::get('/home', ['middleware' => 'auth', 'uses' => 'HomeController@index']);

Route::group(['prefix' => 'users'], function () {
    Route::get('list', 'UserController@showUserList');
    Route::get('new', 'UserController@showNewUserForm');
    Route::post('new', 'UserController@create');
});

Route::group(['prefix' => 'employees'], function () {
    Route::get('list', 'Employees\EmployeesListController@index');
    Route::get('new', 'Employees\EmployeeController@showNewEmployeeForm');
    Route::post('new', 'Employees\EmployeeController@create');
    Route::get('details/{employeeId}', 'Employees\EmployeeController@showDetails');
    Route::get('edit/{employeeId}', 'Employees\EmployeeController@showEditForm');
    Route::post('edit/{employeeId}', 'Employees\EmployeeController@edit');
    Route::get('ajax/search/{searchTerm}', 'Employees\EmployeeController@ajaxEmployeeSearch');
});

Route::group(['prefix' => 'jobs'], function () {
    Route::get('list', 'JobController@showList');
    Route::get('new', 'JobController@showNewForm');
    Route::post('new', 'JobController@create');
    Route::get('edit/{jobId}', 'JobController@showEditForm');
    Route::post('edit/{jobId}', 'JobController@edit');
});

Route::group(['prefix' => 'work_sessions'], function () {
    Route::get('list', 'WorkSessions\WorkSessionsListController@showList');
    Route::get('edit/{workSessionId}', 'WorkSessions\WorkSessionController@showEditForm');
    Route::post('edit/{workSessionId}', 'WorkSessions\WorkSessionController@edit');
    Route::get('new', 'WorkSessions\WorkSessionController@showNewForm');
    Route::post('new', 'WorkSessions\WorkSessionController@create');
    Route::get('delete/{workSessionId}', 'WorkSessions\WorkSessionController@delete');
});

Route::group(['prefix' => 'paychecks'], function () {
    Route::get('list', 'PaycheckController@showPaycheckList');
    Route::get('delete/{paycheckId}', 'PaycheckController@undo');

    Route::group(['prefix' => 'new'], function () {
        Route::get('range_choice', 'PaycheckController@showRangeChoiceForm');
        Route::post('range_choice', 'PaycheckController@validateRangeChoice');
        Route::get('summary/employeeId:{employeeId}/startPeriod:{startPeriod}/endPeriod:{endPeriod}', 'PaycheckController@showSummaryScreen');
        Route::post('summary/employeeId:{employeeId}/startPeriod:{startPeriod}/endPeriod:{endPeriod}', 'PaycheckController@create');
    });
});


/*
 * Raspberry Pi API interface
 */
Route::group(['prefix' => 'pi'], function () {
    Route::any('employee_login', 'PiInterfaceController@employeeLogin');
});


/*
 * Override standard registration and password reset paths to disable access
 */
Route::any('/register', function() {
    return redirect('/home');
});

Route::any('password/reset', function () {
    return redirect('/home');
});

Route::any('/auth/{something}', function () {
            return redirect('/home');
        })
        ->where('something', '.+');

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin/', 'middleware' => ['role:administrator']], function(){

    // Users Route
    Route::get('dashboard', 'AdminController@Dashboard')->name('adminDashboard');
    Route::get('manage/users/index', 'AdminController@UsersIndex')->name('usersIndex');
    Route::get('manage/users/create', 'AdminController@UsersCreate')->name('usersCreate');
    Route::post('manage/users/store', 'AdminController@UsersStore')->name('usersStore');
    Route::get('manage/users/show/{id}', 'AdminController@UsersShow')->name('usersShow');
    Route::get('manage/users/edit/{id}', 'AdminController@UsersEdit')->name('usersEdit');
    Route::post('manage/users/update/{id}', 'AdminController@UsersUpdate')->name('usersUpdate');

    // Permissions Route
    Route::get('manage/permissions/index', 'AdminController@permissionsIndex')->name('permissionsIndex');
    Route::get('manage/permissions/show/{id}', 'AdminController@permissionsShow')->name('permissionsShow');
    Route::get('manage/permissions/create', 'AdminController@permissionsCreate')->name('permissionsCreate');
    Route::post('manage/permissions/store', 'AdminController@permissionsStore')->name('permissionsStore');
    Route::get('manage/permissions/edit/{id}', 'AdminController@permissionsEdit')->name('permissionsEdit');
    Route::post('manage/permissions/update/{id}', 'AdminController@permissionsUpdate')->name('permissionsUpdate');

    // Roles Route
    Route::get('manage/roles/index', 'AdminController@rolesIndex')->name('rolesIndex');
    Route::get('manage/roles/create', 'AdminController@rolesCreate')->name('rolesCreate');
    Route::post('manage/roles/store', 'AdminController@rolesStore')->name('rolesStore');
    Route::get('manage/roles/show/{id}', 'AdminController@rolesShow')->name('rolesShow');
    Route::get('manage/roles/edit/{id}', 'AdminController@rolesEdit')->name('rolesEdit');
    Route::post('manage/roles/update/{id}', 'AdminController@rolesUpdate')->name('rolesUpdate');

    //Departments
    Route::get('manage/departments/index', 'AdminController@departmentsIndex')->name('departmentsIndex');
    Route::get('manage/departments/create', 'AdminController@departmentsCreate')->name('departmentsCreate');
    Route::post('manage/departments/store', 'AdminController@departmentsStore')->name('departmentsStore');
    Route::get('manage/departments/show/{id}', 'AdminController@departmentsShow')->name('departmentsShow');
    Route::get('manage/departments/edit/{id}', 'AdminController@departmentsEdit')->name('departmentsEdit');
    Route::post('manage/departments/update/{id}', 'AdminController@departmentsUpdate')->name('departmentsUpdate');
});

Route::group(['prefix' => 'user/', 'middleware' => ['role:director|manager|employee']], function(){
    Route::get('dashboard', 'UserController@Dashboard')->name('userDashboard');

    Route::get('manage/password', 'UserController@changePassword')->name('changePassword');
    Route::post('manage/password/update', 'UserController@updatePassword')->name('updatePassword');

    Route::get('task/index', 'TaskController@taskIndex')->name('taskIndex');
    Route::get('task/create', 'TaskController@taskCreate')->name('taskCreate');
    Route::post('task/store', 'TaskController@taskStore')->name('taskStore');
    Route::get('task/show/{id}', 'TaskController@taskShow')->name('taskShow');
    Route::get('task/edit/{id}', 'TaskController@taskEdit')->name('taskEdit');
    Route::post('task/update/{id}', 'TaskController@taskUpdate')->name('taskUpdate');
    Route::get('task/completed', 'TaskController@taskCompleted')->name('taskCompleted');
    Route::get('task/inbox', 'TaskController@taskInbox')->name('taskInbox');
    Route::post('task/performance/{id}', 'TaskController@taskPerformance')->name('taskPerformance');
});

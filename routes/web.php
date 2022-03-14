<?php

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\App;
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

Route::get('/', 'Auth\LoginController@showLoginForm')->name('welcome');

Auth::routes();

Route::get('dashboard', 'HomeController@index')->name('home');
Route::get('pricing', 'PageController@pricing')->name('page.pricing');
Route::get('lock', 'PageController@lock')->name('page.lock');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('category', 'CategoryController', ['except' => ['show']]);
    Route::resource('tag', 'TagController', ['except' => ['show']]);
    Route::resource('item', 'ItemController', ['except' => ['show']]);
    Route::resource('role', 'RoleController', ['except' => ['show', 'destroy']]);
    Route::resource('user', 'UserController', ['except' => ['show']]);

    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

    Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);

    Route::get('/calendar/calendar', 'EventsController@index')->name('calendar.index');
    Route::post('/calendar/calendarAjax', 'EventsController@ajax')->name('calendar.ajax');

    Route::get('/customers/list', 'Customercontroller@openList')->name('customersOpen');
    Route::get('/customers/file/{id}', 'Customercontroller@file')->name('customer.show');
    Route::post('/customers/store', 'Customercontroller@store')->name('customer.store');
    Route::post('/customers/update', 'Customercontroller@update')->name('customer.update');
    Route::post('/customers/activity/store', 'ActivitiesController@store')->name('customer.activity.store');
    Route::get('/customers/activity/delete/{activityId}/{customerId}', 'ActivitiesController@deleteActivity')->name('customer.activity.delete');
    Route::post('/customers/correction/store', 'CustomersCorrectionsController@storeCorrection')->name('customer.correction.store');
    Route::get('/customers/correction/delete/{correctionId}/{customerId}', 'CustomersCorrectionsController@deleteCorrection')->name('customer.correction.delete');
    Route::post('/customers/payment/store', 'CustomersPaymentsController@storePayment')->name('customer.payment.store');
    Route::get('/customers/payment/delete/{paymentId}/{customerId}', 'CustomersPaymentsController@deletePayment')->name('customer.payment.delete');

    Route::get('/customers/invoices/{customerId}/{customerLastName}/{customerFirstName}', 'Customercontroller@generatePDF')->name('customer.invoice');

    Route::get('/staff/list', 'StaffController@staffList')->name('staff.list');
    Route::get('/staff/file/{staffId}/{period}', 'StaffController@file')->name('staff.file');
    Route::post('/staff/payment/store', 'StaffPaymentsController@store')->name('staff.payment.store');
    Route::get('/staff/payment/delete/{paymentId}/{staffId}', 'StaffPaymentsController@deletePayment')->name('staff.payment.delete');
    Route::post('/staff/correction/store', 'StaffCorrectionsController@storeCorrection')->name('staff.correction.store');
    Route::get('/staff/correction/delete/{correctionId}/{staffId}', 'StaffCorrectionsController@deleteCorrection')->name('staff.correction.delete');
    Route::post('/staff/update/{staffId}/{period}', 'StaffController@update')->name('staff.update');

    Route::get('/settings/disciplines', 'DisciplinesController@index')->name('settings.disciplines');
    Route::post('/settings/newDiscipline', 'DisciplinesController@store')->name('settings.discipline.store');

    Route::get('/settings/discipline/{id}/{activityName}', 'DisciplinesController@show')->name('discipline.show');
    Route::post('/settings/newPackage', 'PackagesController@store')->name('settings.package.store');

    Route::get('/settings/packages/{id}/{packageName}', 'PackagesController@show')->name('package.show');
    Route::post('/settings/newOption', 'PackagesOptionsController@store')->name('settings.option.store');
    Route::get('/settings/deleteOption/{optionId}{packageId}/{packageName}', 'PackagesOptionsController@invisible')->name('settings.option.invisible');

    Route::get('/settings/staff', 'StaffController@index')->name('settings.staff.show');
    Route::post('/settings/staff/store', 'StaffController@store')->name('settings.staff.store');
});



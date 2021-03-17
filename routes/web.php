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

Route::get('/account', 'AccountController@index');
Route::post('/account/enter', 'Auth\EnterController@login');
Route::get('/account/out', 'Auth\OutController@logout');
Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/bank', 'BankController@index');
Route::get('/bank/create', 'BankController@create');
Route::get('/bank/edit/{id}', 'BankController@edit');
Route::get('/branch_office', 'BranchOfficeController@index');
Route::get('/cashier', 'CashierController@index');
Route::get('/cashier/{id}', 'CashierController@index');
Route::get('/collection', 'CollectionController@index');
Route::get('/collection/create', 'CollectionController@create');
Route::get('/contract/download/{id}', 'ContractController@download');
Route::get('/dte', 'DteController@index');
Route::get('/customer', 'CustomerController@index');
Route::get('/transaction', 'TransactionController@index');
Route::get('/user', 'UserController@index');
Route::get('/settlement/download/{id}', 'SettlementController@download');
Route::get('/transbankcollection', 'TransbankCollectionController@index');
Route::get('/tax/download/{id}', 'TaxController@download');
Route::get('/patent/download/{id}', 'PatentController@download');
Route::get('/trigger', 'TriggerController@index');
Route::get('/dte/download/{id}', 'DteController@download');
Route::get('/password', 'Auth\PasswordController@index');
Route::post('/password/update', 'Auth\PasswordController@update');
Route::get('/trigger/cc', 'TriggerController@cc');
Route::get('/trigger/mm', 'TriggerController@mm');
Route::get('/trigger/po', 'TriggerController@po');
Route::get('/trigger/qqu', 'TriggerController@qqu');
Route::get('/trigger/p', 'TriggerController@p');
Route::get('/trigger/e', 'TriggerController@e');

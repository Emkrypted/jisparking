<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
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
        Route::get('/transbankcollection', 'TransbankCollectionController@index');
        Route::get('/tax/download/{id}', 'TaxController@download');

        /////////////////

        Route::resource('/bank', 'api\BankController');
        Route::post('/bank/store', 'api\BankController@store');
        Route::middleware('auth:api')->resource('/bill_payment', 'api\BillPaymentController');
        Route::middleware('auth:api')->get('/bill_payment/detail/{rut}', 'api\BillPaymentController@detail');
        Route::middleware('auth:api')->post('/bill_payment/check', 'api\BillPaymentController@check');
        Route::middleware('auth:api')->post('/bill_payment/search/{supplier_id}', 'api\BillPaymentController@index');
        Route::middleware('auth:api')->resource('/branch_office', 'api\BranchOfficeController');
        Route::post('/branch_office/store', 'api\BranchOfficeController@store');
        Route::middleware('auth:api')->resource('/cashier', 'api\CashierController');
        Route::resource('/cashier/store', 'api\CashierController@store');
        Route::get('/cashier/multiple/{id}', 'api\CashierController@multiple');
        Route::resource('/capitulation', 'api\CapitulationController');
        Route::middleware('auth:api')->post('/capitulation/impute', 'api\CapitulationController@impute');
        Route::post('/capitulation/store', 'api\CapitulationController@store');
        Route::get('/capitulation/search/{supervisor_id}/{expense_type_id}/{since}/{until}', 'api\CapitulationController@index');
        Route::get('/capitulation/support/{capitulation_id}', 'api\CapitulationController@support');
        Route::get('/capitulation/pay/{rut}', 'api\CapitulationController@pay');
        Route::resource('/collection', 'api\CollectionController');
        Route::get('/collection/amount', 'api\CollectionController@amount');
        Route::get('/collection/amount/{branch_office_id}/{collection_date}', 'api\CollectionController@amount');
        Route::get('/collection/check/{id}', 'api\CollectionController@check');
        Route::get('/collection/search/{branch_office_id}/{created_at}', 'api\CollectionController@index');
        Route::get('/collection/find/{branch_office_id}/{cashier_id}', 'api\CollectionController@find');
        Route::get('/collection/ticket/{branch_office_id}/{cashier_id}', 'api\CollectionController@ticket');
        Route::middleware('auth:api')->post('/collection/store', 'api\CollectionController@store');
        Route::get('/collection/support/{collection_id}', 'api\CollectionController@support');
        Route::resource('/collection_accounting', 'api\CollectionAccountingController');
        Route::get('/collection_accounting/search/{branch_office_id}', 'api\CollectionAccountingController@index');
        Route::get('/collection_accounting/search/{branch_office_id}/{period}', 'api\CollectionAccountingController@index');
        Route::post('/collection_accounting/store', 'api\CollectionAccountingController@store');
        Route::middleware('auth:api')->resource('/contract', 'api\ContractController');
        Route::middleware('auth:api')->post('/contract/store', 'api\ContractController@store');
        Route::middleware('auth:api')->resource('/tax', 'api\TaxController');
        Route::middleware('auth:api')->post('/tax/store', 'api\TaxController@store');
        Route::middleware('auth:api')->post('/tax/download/{id}', 'api\TaxController@download');
        Route::resource('/transbank_collection_accounting', 'api\TransbankCollectionAccountingController');
        Route::get('/transbank_collection_accounting/search/{branch_office_id}', 'api\TransbankCollectionAccountingController@index');
        Route::get('/transbank_collection_accounting/search/{branch_office_id}/{period}', 'api\TransbankCollectionAccountingController@index');
        Route::post('/transbank_collection_accounting/store', 'api\TransbankCollectionAccountingController@store');
        Route::middleware('auth:api')->resource('/patent', 'api\PatentController');
        Route::middleware('auth:api')->post('/patent/store', 'api\PatentController@store');
        Route::middleware('auth:api')->post('/patent/download/{id}', 'api\PatentController@download');
        Route::resource('/commune', 'api\CommuneController');
        Route::resource('/customer', 'api\CustomerController');
        Route::middleware('auth:api')->resource('/deposit', 'api\DepositController');
        Route::get('/deposit/create/{collection_id}', 'api\DepositController@create');
        Route::get('/deposit/support/{deposit_id}', 'api\DepositController@support');
        Route::get('/deposit/amount/{branch_office_id}/{collection_date}', 'api\DepositController@amount');
        Route::middleware('auth:api')->post('/deposit/store', 'api\DepositController@store');
        Route::get('/deposit/search/{branch_office_id}/{status_id}/{since}/{until}', 'api\DepositController@index');
        Route::middleware('auth:api')->resource('/dte', 'api\DteController');
        Route::middleware('auth:api')->post('/dte/store', 'api\DteController@store');
        Route::middleware('auth:api')->post('/dte/generate', 'api\DteController@generate');
        Route::middleware('auth:api')->post('/dte/impute', 'api\DteController@impute');
        Route::middleware('auth:api')->post('/dte/refresh', 'api\DteController@refresh');
        Route::middleware('auth:api')->post('/dte/search/{branch_office_id}/{status_id}/{since}/{until}/{folio}', 'api\DteController@index');
        Route::middleware('auth:api')->resource('/dte_type', 'api\DteTypeController');
        Route::middleware('auth:api')->resource('/electronic_collection', 'api\ElectronicCollectionController');
        Route::middleware('auth:api')->get('/electronic_collection/amount/{branch_office_id}/{collection_date}', 'api\ElectronicCollectionController@amount');
        Route::middleware('auth:api')->get('/electronic_collection/search/{branch_office_id}/{created_at}', 'api\ElectronicCollectionController@index');
        Route::middleware('auth:api')->resource('/electronic_deposit', 'api\ElectronicDepositController');
        Route::middleware('auth:api')->get('/electronic_deposit/amount/{branch_office_id}/{collection_date}', 'api\ElectronicDepositController@amount');
        Route::middleware('auth:api')->post('/electronic_deposit/store', 'api\ElectronicDepositController@store');
        Route::middleware('auth:api')->get('/electronic_deposit/search/{branch_office_id}/{status_id}/{since}/{until}', 'api\ElectronicDepositController@index');
        Route::middleware('auth:api')->resource('/expense_type', 'api\ExpenseTypeController');
        Route::middleware('auth:api')->resource('/request', 'api\RequestController');
        Route::middleware('auth:api')->resource('/region', 'api\RegionController');
        Route::resource('/supervisor', 'api\SupervisorController');
        Route::middleware('auth:api')->resource('/supplier', 'api\SupplierController');
        Route::middleware('auth:api')->get('/supplier/show/{id}', 'api\SupplierController@show');
        Route::resource('/transaction', 'api\TransactionController');
        Route::middleware('auth:api')->resource('/transbank', 'api\TransbankController');
        Route::post('/transbank/store', 'api\TransbankController@store');
        Route::middleware('auth:api')->get('/transbank/amount/{branch_office_id}/{collection_date}', 'api\TransbankController@amount');
        Route::middleware('auth:api')->post('/transaction/store', 'api\TransactionController@store');
        Route::middleware('auth:api')->resource('/bill_payment', 'api\BillPaymentController');
        Route::resource('/user', 'api\UserController');
    ];
}

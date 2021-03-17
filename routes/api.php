<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('/bank', 'api\BankController');
Route::post('/bank/store', 'api\BankController@store');
Route::middleware('auth:api')->resource('/bill', 'api\BillController');
Route::middleware('auth:api')->post('/bill/search/{branch_office_id}/{rut}/{status_id}/{supervisor_id}', 'api\BillController@index');
Route::middleware('auth:api')->resource('/bill_payment', 'api\BillPaymentController');
Route::middleware('auth:api')->get('/bill_payment/detail/{rut}', 'api\BillPaymentController@detail');
Route::middleware('auth:api')->post('/bill_payment/check', 'api\BillPaymentController@check');
Route::middleware('auth:api')->post('/bill_payment/search/{supplier_id}/{folio}/{rut}', 'api\BillPaymentController@index');
Route::middleware('auth:api')->resource('/branch_office', 'api\BranchOfficeController');
Route::post('/branch_office/store', 'api\BranchOfficeController@store');
Route::middleware('auth:api')->resource('/cashier', 'api\CashierController');
Route::resource('/cashier/store', 'api\CashierController@store');
Route::get('/cashier/multiple/{id}', 'api\CashierController@multiple');
Route::resource('/capitulation', 'api\CapitulationController');
Route::middleware('auth:api')->post('/capitulation/impute', 'api\CapitulationController@impute');
Route::post('/capitulation/store', 'api\CapitulationController@store');
Route::get('/capitulation/search/{supervisor_id}/{expense_type_id}/{since}/{until}/{status_id}', 'api\CapitulationController@index');
Route::get('/capitulation/support/{capitulation_id}', 'api\CapitulationController@support');
Route::get('/capitulation/pay/{rut}', 'api\CapitulationController@pay');
Route::middleware('auth:api')->post('/capitulation/check', 'api\CapitulationController@check');
Route::resource('/collection', 'api\CollectionController');
Route::get('/collection/amount', 'api\CollectionController@amount');
Route::get('/collection/amount/{branch_office_id}/{collection_date}', 'api\CollectionController@amount');
Route::get('/collection/check/{id}', 'api\CollectionController@check');
Route::get('/collection/search/{branch_office_id}/{created_at}/{status_id}/{supervisor_id}', 'api\CollectionController@index');
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
Route::middleware('auth:api')->post('/creditnote/generate', 'api\CreditNoteController@generate');
Route::middleware('auth:api')->post('/contract/search/{branch_office_id}', 'api\ContractController@index');
Route::middleware('auth:api')->post('/honorary/requirement/{id}', 'api\HonoraryController@requirement');
Route::middleware('auth:api')->resource('/tax', 'api\TaxController');
Route::middleware('auth:api')->post('/tax/store', 'api\TaxController@store');
Route::middleware('auth:api')->post('/tax/download/{id}', 'api\TaxController@download');
Route::middleware('auth:api')->post('/tax/search/{date}', 'api\TaxController@index');
Route::resource('/ticket', 'api\TicketController');
Route::post('/ticket/search/{branch_office_id}/{rut}/{status_id}/{supervisor_id}', 'api\TicketController@index');
Route::resource('/transbank_collection_accounting', 'api\TransbankCollectionAccountingController');
Route::get('/transbank_collection_accounting/search/{branch_office_id}', 'api\TransbankCollectionAccountingController@index');
Route::get('/transbank_collection_accounting/search/{branch_office_id}/{period}', 'api\TransbankCollectionAccountingController@index');
Route::post('/transbank_collection_accounting/store', 'api\TransbankCollectionAccountingController@store');
Route::middleware('auth:api')->resource('/patent', 'api\PatentController');
Route::middleware('auth:api')->post('/patent/store', 'api\PatentController@store');
Route::middleware('auth:api')->post('/patent/download/{id}', 'api\PatentController@download');
Route::middleware('auth:api')->post('/patent/search/{branch_office_id}/{date}', 'api\PatentController@index');
Route::resource('/commune', 'api\CommuneController');
Route::get('/commune/search/region/{id}', 'api\CommuneController@region');
Route::resource('/customer', 'api\CustomerController');
Route::post('/customer/store', 'api\CustomerController@store');
Route::middleware('auth:api')->resource('/deposit', 'api\DepositController');
Route::get('/deposit/create/{collection_id}', 'api\DepositController@create');
Route::get('/deposit/support/{deposit_id}', 'api\DepositController@support');
Route::get('/deposit/amount/{branch_office_id}/{collection_date}', 'api\DepositController@amount');
Route::middleware('auth:api')->post('/deposit/store', 'api\DepositController@store');
Route::get('/deposit/search/{branch_office_id}/{status_id}/{since}/{until}', 'api\DepositController@index');
Route::middleware('auth:api')->resource('/dte', 'api\DteController');
Route::middleware('auth:api')->get('/dte/accept/{dte_id}', 'api\DteController@accept');
Route::middleware('auth:api')->post('/dte/store', 'api\DteController@store');
Route::middleware('auth:api')->post('/dte/update/{dte_id}', 'api\DteController@update');
Route::middleware('auth:api')->post('/dte/generate', 'api\DteController@generate');
Route::middleware('auth:api')->post('/dte/impute', 'api\DteController@impute');
Route::middleware('auth:api')->post('/dte/refresh', 'api\DteController@refresh');
Route::middleware('auth:api')->get('/dte/reject/{dte_id}', 'api\DteController@reject');
Route::middleware('auth:api')->post('/dte/search/{branch_office_id}/{status_id}/{since}/{until}/{folio}/{dte_version_id}/{rut}/{supervisor_id}', 'api\DteController@index');
Route::middleware('auth:api')->post('/dte/pay', 'api\DteController@pay');
Route::middleware('auth:api')->post('/dte/send', 'api\DteController@send');
Route::middleware('auth:api')->get('/dte/pay/{id}', 'api\DteController@pay');
Route::middleware('auth:api')->post('/dte/pay/{id}', 'api\DteController@pay');
Route::middleware('auth:api')->resource('/dte_type', 'api\DteTypeController');
Route::middleware('auth:api')->resource('/electronic_collection', 'api\ElectronicCollectionController');
Route::middleware('auth:api')->get('/electronic_collection/amount/{branch_office_id}/{collection_date}', 'api\ElectronicCollectionController@amount');
Route::middleware('auth:api')->get('/electronic_collection/search/{branch_office_id}/{created_at}', 'api\ElectronicCollectionController@index');
Route::middleware('auth:api')->post('/electronic_collection/update/{id}', 'api\ElectronicCollectionController@update');
Route::middleware('auth:api')->resource('/electronic_deposit', 'api\ElectronicDepositController');
Route::middleware('auth:api')->get('/electronic_deposit/amount/{branch_office_id}/{collection_date}', 'api\ElectronicDepositController@amount');
Route::middleware('auth:api')->post('/electronic_deposit/store', 'api\ElectronicDepositController@store');
Route::middleware('auth:api')->get('/electronic_deposit/search/{branch_office_id}/{status_id}/{since}/{until}', 'api\ElectronicDepositController@index');
Route::middleware('auth:api')->resource('/employee', 'api\EmployeeController');
Route::middleware('auth:api')->post('/employee/list/{id}', 'api\EmployeeController@list');
Route::middleware('auth:api')->resource('/expense_type', 'api\ExpenseTypeController');
Route::middleware('auth:api')->post('/expense_type/list', 'api\ExpenseTypeController@list');
Route::middleware('auth:api')->resource('/induction', 'api\InductionController');
Route::post('/induction/store', 'api\InductionController@store');
Route::middleware('auth:api')->resource('/requirement', 'api\RequirementController');
Route::middleware('auth:api')->post('/requirement/store', 'api\RequirementController@store');
Route::middleware('auth:api')->post('/requirement/update/{requirement_id}', 'api\RequirementController@update');
Route::middleware('auth:api')->resource('/region', 'api\RegionController');
Route::middleware('auth:api')->resource('/settlement', 'api\SettlementController');
Route::middleware('auth:api')->post('/settlement/search/{rut}/{father_lastname}/{branch_office_id}', 'api\SettlementController@index');
Route::resource('/supervisor', 'api\SupervisorController');
Route::middleware('auth:api')->resource('/supplier', 'api\SupplierController');
Route::middleware('auth:api')->get('/supplier/show/{id}', 'api\SupplierController@show');
Route::middleware('auth:api')->post('/publicity/requirement/{id}', 'api\PublicityController@requirement');
Route::post('/seat/refresh', 'api\SeatController@refresh');
Route::middleware('auth:api')->resource('/manual_seat', 'api\ManualSeatController');
Route::post('/manual_seat/store', 'api\ManualSeatController@store');
Route::middleware('auth:api')->post('/maintenance/requirement/{id}', 'api\MaintenanceController@requirement');
Route::post('/rut', 'api\RutController@index');
Route::resource('/transaction', 'api\TransactionController');
Route::middleware('auth:api')->resource('/transbank', 'api\TransbankController');
Route::post('/transbank/store', 'api\TransbankController@store');
Route::middleware('auth:api')->get('/transbank/amount/{branch_office_id}/{collection_date}', 'api\TransbankController@amount');
Route::middleware('auth:api')->post('/transaction/store', 'api\TransactionController@store');
Route::resource('/videotutorial', 'api\VideotutorialController');
Route::post('/videotutorial/store', 'api\VideotutorialController@store');
Route::middleware('auth:api')->resource('/bill_payment', 'api\BillPaymentController');
Route::resource('/user', 'api\UserController');
Route::post('/user/list', 'api\UserController@list');

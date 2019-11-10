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


Auth::routes();

Route::post('user/register', ['as' => 'user.register', 'uses' => 'UserController@register']);
Route::get('user/email/verify', 'UserController@verify')->name('email.verify');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/home', 'HomeController@index')->name('home');


	// SUSCRIPTION RESTRICTED ROUTES
	Route::group(['middleware' => 'suscription'], function () {
		// USERS
		Route::put('user/profile/{id}', ['as' => 'user.update', 'uses' => 'UserController@update']);
		Route::get('user/create', 'UserController@create')->name('user.create');
		Route::post('user/store', ['as' => 'user.store', 'uses' => 'UserController@store']);
	// USER
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('user/{id}', ['as' => 'user.show', 'uses' => 'UserController@show']);
	Route::put('user/password/{id}', ['as' => 'user.updatepass', 'uses' => 'UserController@updatePass'])->middleware('role:admin|superadmin');

		// CLIENTS
		Route::get('client/create', 'ClientController@create')->name('client.create');
		Route::post('client', 'ClientController@store')->name('client.store');
		Route::put('client', 'ClientController@update')->name('client.update')->middleware('role:admin|superadmin');
		Route::delete('client/{id}/delete', 'ClientController@delete')->name('client.delete')->middleware('role:admin|superadmin');

		// INVOICES
		Route::get('invoice/create', 'InvoiceController@create')->name('invoice.create');
		Route::put('invoice/{id}/status', 'InvoiceController@status')->name('invoice.status')->middleware('role:admin|superadmin');
		Route::post('invoice', 'InvoiceController@store')->name('invoice.store');
		Route::delete('invoice/{id}/delete', 'InvoiceController@delete')->name('invoice.delete')->middleware('role:admin|superadmin');
		Route::get('invoice/{id}/edit/{type}', 'InvoiceController@edit')->name('invoice.edit')->middleware('role:admin|superadmin');
		Route::put('invoice/{id}', 'InvoiceController@update')->name('invoice.update')->middleware('role:admin|superadmin');

		// INVOICES SERIE
		Route::get('billing/series/create', 'InvoiceSeriesController@create')->name('invoice.series.create')->middleware('role:admin|superadmin');
		Route::post('billing/series/store', 'InvoiceSeriesController@store')->name('invoice.series.store')->middleware('role:admin|superadmin');
		Route::delete('billing/serie/{id}/delete', 'InvoiceSeriesController@delete')->name('invoice.series.delete')->middleware('role:admin|superadmin');
		Route::put('billing/serie/{id}/activate/{status}', 'InvoiceSeriesController@activate')->name('invoice.series.activate')->middleware('role:admin|superadmin');

		// BUDGETS
		Route::get('budgets/create', 'BudgetController@create')->name('budgets.create');
		Route::post('budgets/store', 'BudgetController@store')->name('budgets.store');
		Route::get('budgets/{id}/edit', 'BudgetController@edit')->name('budgets.edit');
		Route::put('budgets/{id}/update', 'BudgetController@update')->name('budgets.update');
		Route::put('budgets/{budgetid}/status', 'BudgetController@status')->name('budget.status');
		Route::delete('budgets/{id}/delete', 'BudgetController@delete')->name('budgets.delete');

		// PRODUCTS
		Route::get('products/create', 'ProductController@create')->name('products.create');
		Route::post('products/store', 'ProductController@store')->name('products.store');
		Route::delete('product/{id}/delete', 'ProductController@delete')->name('products.delete');
		Route::get('product/{id}/edit', 'ProductController@edit')->name('products.edit');
		Route::put('product/{productID}/update', 'ProductController@update')->name('products.update');

		// SERVICES
		Route::get('services/create', 'ServiceController@create')->name('services.create');
		Route::post('services/store', 'ServiceController@store')->name('services.store');
		Route::delete('service/{id}/delete', 'ServiceController@delete')->name('services.delete');
		Route::get('service/{id}/edit', 'ServiceController@edit')->name('services.edit');
		Route::put('service/{id}/update', 'ServiceController@update')->name('services.update');

		// BRANDS
		Route::get('repairs/brands/create', 'PbrandController@create')->name('repairs.brands.create')->middleware('role:admin|superadmin');
		Route::post('repairs/brands/store', 'PbrandController@store')->name('repairs.brands.store')->middleware('role:admin|superadmin');
		Route::get('repairs/brands/{brandid}/edit', 'PbrandController@edit')->name('repairs.brands.edit')->middleware('role:admin|superadmin');
		Route::put('repairs/brands/{brandid}/update', 'PbrandController@update')->name('repairs.brands.update')->middleware('role:admin|superadmin');
		Route::delete('repairs/brands/{brandid}/delete', 'PbrandController@delete')->name('repairs.brands.delete')->middleware('role:admin|superadmin');

		// REPAIRS
		Route::get('repairs/create', 'RepairController@create')->name('repairs.create')->middleware('role:admin|superadmin');
		Route::post('repairs/store', 'RepairController@store')->name('repairs.store')->middleware('role:admin|superadmin');
		Route::get('repairs/{repairid}/edit', 'RepairController@edit')->name('repairs.edit')->middleware('role:admin|superadmin');
		Route::put('repairs/{repairid}/update', 'RepairController@update')->name('repairs.update')->middleware('role:admin|superadmin');
		Route::delete('repairs/{repairid}/delete', 'RepairController@delete')->name('repairs.delete')->middleware('role:admin|superadmin');
		Route::put('repairs/{repairid}/status/{status}', 'RepairController@status')->name('repairs.status')->middleware('role:admin|superadmin');
	});
	
	// USER SUBSCRIPTIONS LIST
	Route::get('subscriptions', 'SubscriptionController@index')->name('subscriptions');
	Route::post('subscription/activate', 'SubscriptionController@store')->name('subscription.activate');

	// PAYMENT METHODS INDEX
	Route::get('payment/methods', 'PaymentMethodController@index')->name('paymentMethods.index');
	Route::get('payment/method/add', 'PaymentMethodController@create')->name('paymentMethods.create');
	Route::post('payment/method/store/{stripePaymentMethod}', 'PaymentMethodController@store')->name('paymentMethods.store');

	Route::put('payment/method/default/{stripePaymentMethod}', 'DefaultPaymentMethodController@update')->name('defaultPaymentMethods.update');

	// CLIENTS
	Route::get('clients', 'ClientController@index')->name('clients.index');
	Route::get('client/{id}/show', 'ClientController@show')->name('client.show');
	Route::post('client/search', 'ClientController@search')->name('client.search');

	// CLIENTS LIKE API
	Route::get('clientdata', 'ClientController@apiClientData')->name('api.clientdata');
	
	Route::post('provider/search', 'ClientController@searchProvider')->name('provider.search');

	// INVOICES
	Route::get('invoices', 'InvoiceController@index')->name('invoices.index');
	Route::get('invoices/{type}', 'InvoiceController@index')->name('invoices.index')->middleware('suscription');
	Route::get('invoice/{id}', 'InvoiceController@show')->name('invoice.show');
	Route::get('invoice/{id}/viewpdf', 'InvoiceController@viewpdf')->name('invoice.viewpdf');

	// INVOICES SERIES
	Route::get('billing/series', 'InvoiceSeriesController@index')->name('invoice.series.index')->middleware('role:admin|superadmin');
	Route::get('billing/serie/{serie_id}/data', 'InvoiceSeriesController@getData')->name('invoice.series.data');

	// ORGANIZATIONS
	Route::get('organizations', 'OrganizationController@index')->name('organizations.index')->middleware('role:superadmin');
	Route::post('organization/store', 'OrganizationController@store')->name('organizations.store')->middleware('role:superadmin');
	Route::get('organization/create', 'OrganizationController@create')->name('organizations.create')->middleware('role:superadmin');
	Route::get('organization/{id}/show', 'OrganizationController@show')->name('organizations.show')->middleware('role:admin|superadmin');
	Route::put('organization/{id}/update', 'OrganizationController@update')->name('organizations.update')->middleware('role:admin|superadmin');
	Route::put('organization/{id}/update/status/{status}', 'OrganizationController@status')->name('organizations.update.status')->middleware('role:superadmin');
	Route::delete('organization/{id}/delete', 'OrganizationController@delete')->name('organizations.delete')->middleware('role:superadmin');
	Route::get('organization/payments', 'OrganizationController@payments')->name('organizations.payments')->middleware('role:admin');

	// BUDGETS
	Route::get('budgets', 'BudgetController@index')->name('budgets.index');
	Route::get('budget/{id}/show', 'BudgetController@show')->name('budgets.show');

	// PRODUCTS
	Route::get('products', 'ProductController@index')->name('products.index');

	// SERVICES
	Route::get('services', 'ServiceController@index')->name('services.index');

	Route::get('productservices', 'HomeController@productAndServicesAutocomplete')->name('productservices.autocomplete');

	// MEMBERSHIPS
	Route::get('memberships', 'MembershipController@index')->name('memberships.index')->middleware('role:superadmin');
	Route::get('memberships/create', 'MembershipController@create')->name('memberships.create')->middleware('role:superadmin');
	Route::get('memberships/{membership_id}/edit', 'MembershipController@edit')->name('memberships.edit')->middleware('role:superadmin');
	Route::put('memberships/{membership_id}/update', 'MembershipController@update')->name('memberships.update')->middleware('role:superadmin');
	Route::put('memberships/{membership_id}/status/{status}', 'MembershipController@status')->name('memberships.status')->middleware('role:superadmin');
	Route::post('memberships/store', 'MembershipController@store')->name('memberships.store')->middleware('role:superadmin');
	Route::post('/pay/{payment_id}', 'MembershipController@pay')->name('memberships.pay');
	Route::delete('memberships/{membership_id}/delete', 'MembershipController@delete')->name('memberships.delete')->middleware('role:superadmin');

	Route::get('payment/{payment_id}/show', 'MembershipController@paymentInvoice')->name('payment.invoice');

	// BRANDS
	Route::get('repairs/brands', 'PbrandController@index')->name('repairs.brands.index');

	// MODELS
	Route::get('repairs/models', 'PmodelController@index')->name('repairs.models.index');
	Route::get('repairs/models/create', 'PmodelController@create')->name('repairs.models.create');
	Route::post('repairs/models/store', 'PmodelController@store')->name('repairs.models.store');
	Route::get('repairs/models/{modelid}/edit', 'PmodelController@edit')->name('repairs.models.edit');
	Route::put('repairs/models/{modelid}/update', 'PmodelController@update')->name('repairs.models.update');
	Route::delete('repairs/models/{modelid}/delete', 'PmodelController@delete')->name('repairs.models.delete');
	// MODELS BY BRAND LIKE API
	Route::get('brand/{brandid}/models', 'PmodelController@byBrand')->name('models.bybrand');

	// REPAIRS
	Route::get('repairs', 'RepairController@index')->name('repairs.index');
	Route::get('repairs/{repairid}/show', 'RepairController@show')->name('repairs.show');

	// PROFILE (self owner))
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

	// ACCOUNTING
	Route::get('accounting', ['as' => 'accounting.index', 'uses' => 'AccountingController@index']);
	Route::get('accounting/reports', ['as' => 'accounting.reports', 'uses' => 'AccountingReportController@index']);
	Route::post('accounting/reports/exportExcel', ['as' => 'accounting.reports.exportExcel', 'uses' => 'AccountingReportController@exportExcel']);

	// EXPORTS
	Route::get('export/taxes', ['as' => 'export.taxes', 'uses' => 'TaxesExportController@index']);

});
// EXAMPLE PAGES
Route::get('table-list', function () {
	return view('pages.table_list');
})->name('table');

Route::get('typography', function () {
	return view('pages.typography');
})->name('typography');

Route::get('icons', function () {
	return view('pages.icons');
})->name('icons');

Route::get('map', function () {
	return view('pages.map');
})->name('map');

Route::get('notifications', function () {
	return view('pages.notifications');
})->name('notifications');

Route::get('rtl-support', function () {
	return view('pages.language');
})->name('language');

Route::get('upgrade', function () {
	return view('pages.upgrade');
})->name('upgrade');
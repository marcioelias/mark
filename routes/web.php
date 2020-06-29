<?php
  use App\Http\Controllers\LanguageController;

//Auth::routes(['verify' => true]);

/* Webhook routes */
Route::post('webhook/system/monetizze', 'WebhookMonetizzeController@receive');

/* Webhook user routes */
Route::post('webhook/{plataformConfig}', 'WebhookCallController@receiveUserWebhook')->name('webhook.url');

/* Administrative routes */
Route::prefix('admin')->group(function() {
	Route::middleware(['guest:admin'])->group(function() {
		Route::get('login', 'Auth\Admin\LoginController@showLoginForm')->name('admin.login');
		Route::post('login', 'Auth\Admin\LoginController@login')->name('admin.login');
		Route::post('password/email', 'Auth\Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
		Route::get('password/reset', 'Auth\Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
		Route::post('password/reset', 'Auth\Admin\ResetPasswordController@reset')->name('admin.password.update');
		Route::get('password/reset/{token}', 'Auth\Admin\ResetPasswordController@showResetForm')->name('admin.password.reset');
	});

	Route::middleware(['auth:admin'])->group(function() {
		Route::get('/', 'AdminHomeController@index')->name('admin.index');

		Route::post('logout', 'Auth\Admin\LoginController@logout')->name('admin.logout');
		Route::get('password/confirm', 'Auth\Admin\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
		Route::post('password/confirm', 'Auth\Admin\ConfirmPasswordController@confirm');

		Route::resource('plan', 'PlanController')->except('show');
		Route::resource('sms_buy', 'SmsBuyController')->except(['show', 'edit', 'update']);
		Route::resource('sms_package', 'SmsPackageController')->except('show');
		Route::resource('user', 'UserController')->except('show');

		/* routes that response only json, to use with vuejs */
		Route::get('dasboard/charts/faturamento', 'AdminHomeController@getMounthlyInvoicing');
		Route::get('dasboard/charts/clientes_plano', 'AdminHomeController@getCustomerByPlan');
	});
});

/* Userland routes */
Route::prefix('')->group(function() {
	Route::middleware(['guest:web'])->group(function() {
		Route::get('login', 'Auth\User\LoginController@showLoginForm')->name('login');
		Route::post('login', 'Auth\User\LoginController@login');
		Route::post('password/email', 'Auth\User\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
		Route::get('password/reset', 'Auth\User\ForgotPasswordController@showLinkRequestForm')->name('password.request');
		Route::post('password/reset', 'Auth\User\ResetPasswordController@reset')->name('password.update');
		Route::get('password/reset/{token}', 'Auth\User\ResetPasswordController@showResetForm')->name('password.reset');
	});

	Route::middleware(['auth:web', 'active'])->group(function() {
		Route::get('email/verify', 'Auth\User\VerificationController@show')->name('verification.notice');
		Route::get('email/resend', 'Auth\User\VerificationController@resend')->name('verification.resend');
	});

	Route::middleware(['auth:web', 'verified', 'active'])->group(function() {
		Route::get('/', 'HomeController@index')->name('index');

		Route::post('logout', 'Auth\User\LoginController@logout')->name('logout');
		Route::get('password/confirm', 'Auth\User\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
		Route::post('password/confirm', 'Auth\User\ConfirmPasswordController@confirm');
		Route::get('password/change', 'Auth\User\LoginController@showChangePasswordForm')->name('password.change');
		Route::post('password/change', 'Auth\User\LoginController@changePassword');

		Route::resource('tag', 'TagController')->except('show');
		Route::resource('product', 'ProductController')->except('show');
		Route::resource('plataform_config', 'PlataformConfigController')->except('show');
		Route::resource('funnel', 'FunnelController')->except('show');

		Route::get('plataform_config/get_url/{plataformConfig}', 'PlataformConfigController@getWebhookUrl');

		/* Routes to be comsumed by Vue (JSON Response) */
		Route::get('products/json', 'ProductController@getProductsJson');
		Route::get('tags/json', 'TagController@getTagsJson');
		Route::get('variables/json', 'VariableController@getVariablesJson');
	});

	Route::middleware(['auth:web', 'signed'])->group(function() {
		Route::get('email/verify/{id}/{hash}', 'Auth\User\VerificationController@verify')->name('verification.verify');
	});
});

/* // Route url
Route::get('/', 'DashboardController@dashboardAnalytics');

// Route Dashboards
Route::get('/dashboard-analytics', 'DashboardController@dashboardAnalytics');

// Route Components
Route::get('/sk-layout-2-columns', 'StaterkitController@columns_2');
Route::get('/sk-layout-fixed-navbar', 'StaterkitController@fixed_navbar');
Route::get('/sk-layout-floating-navbar', 'StaterkitController@floating_navbar');
Route::get('/sk-layout-fixed', 'StaterkitController@fixed_layout');

// acess controller
Route::get('/access-control', 'AccessController@index');
Route::get('/access-control/{roles}', 'AccessController@roles');
Route::get('/modern-admin', 'AccessController@home')->middleware('permissions:approve-post');

// Auth::routes();

// locale Route
Route::get('lang/{locale}',[LanguageController::class,'swap']);
 */

/* Auth::routes();

Route::get('/home', 'HomeController@index')->name('home'); */

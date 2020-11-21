<?php
  use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

//Auth::routes(['verify' => true]);

Route::get('teste', function() {
	return Http::post('http://marcos.startzap.online/sendText?token=MARCOS954E9137CD3C9EBA4FD2A5C87390B', ['to' => '554884691819@c.us', 'msg' => 'Teste de envio de whatsapp']);
});

/* Webhook routes */
Route::post('webhook/system/monetizze', 'WebhookMonetizzeController@receive');
Route::post('webhook/system/mercadopago', 'WebhookMercadoPagoController@receive');
Route::post('webhook/whatsapp/status', 'WhatsappInstanceController@setStatus');

/* Webhook whatsapp routes */
//Route::post('webhook/system/whatsapp', 'WhatsappIn');

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

		Route::get('profile', 'AdminController@profile')->name('admin.profile');
		Route::patch('profile/{admin}', 'AdminController@updateProfile')->name('admin.profile.update');

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

		Route::resource('product', 'ProductController')->except('show');
		Route::resource('plataform_config', 'PlataformConfigController')->except('show');
		Route::resource('funnel', 'FunnelController');
		Route::resource('lead', 'LeadController')->except('create');
		Route::resource('whatsapp_template', 'WhatsappTemplateController')->except('show');
		Route::resource('customer', 'CustomerController')->except('show');
		Route::resource('customer_status', 'CustomerStatusController')->except('show');
		Route::resource('whatsapp_instance', 'WhatsappInstanceController')->except('show');
		Route::resource('marketing_action', 'MarketingActionController')->except('edit');

		Route::get('sent_message', 'SentMessageController@index')->name('sent_message.index');
		Route::get('sent_message/{sent_message}', 'SentMessageController@show')->name('sent_message.show');

		Route::get('customers/import', 'CustomerController@import')->name('customers.import');
		Route::post('customers/upload', 'CustomerController@upload')->name('customers.upload');
		Route::post('customers/import', 'CustomerController@processImport')->name('customers.process.import');
		Route::get('postback', 'PostbackController@index')->name('postback.index');
		Route::get('postback/{postback}', 'PostbackController@show')->name('postback.show');
		Route::get('user/profile', 'UserController@profile')->name('user.profile');
		Route::patch('user/profile/{user}', 'UserController@updateProfile')->name('user.profile.update');

		Route::get('sms/buy', 'SmsPackageController@buyPackage')->name('sms.buy');
		Route::get('sms/buy/{response}', 'SmsPackageController@buyPackageResponse')->name('sms.buy.response');

		Route::get('plataform_config/get_url/{plataformConfig}', 'PlataformConfigController@getWebhookUrl');
		Route::get('whatsapp_instance/{whatsappInstance}/qrcode', 'WhatsappInstanceController@getQrCode')->name('whatsapp_instance.qrcode');
		Route::post('whatsapp_instance/{whatsappInstance}/disconnect', 'WhatsappInstanceController@disconnect')->name('whatsapp_instance.disconnect');

		/* Routes to be comsumed by Vue (JSON Response) */
		Route::get('products/json', 'ProductController@getProductsJson');
		Route::get('funnels/remarketing/json', 'FunnelController@getRemarketingFunnelsJson');
		//Route::get('lead_statuses/json', 'LeadStatusController@getLeadStatusesJson');
		Route::get('postback_event_types/json', 'PostbackEventTypeController@getPostbackEventTypeJson');
		Route::get('variables/json', 'VariableController@getVariablesJson');
		Route::get('action_types/json', 'ActionTypeController@getActionTypesJson');
		Route::get('funnel/{funnel}/json', 'FunnelController@getFunnelJson');
		Route::get('message/{funnelStepAction}/{lead}', 'FunnelStepActionController@getActionMessage')->name('action.message');
		Route::get('schedules/{funnelStep}/{lead', 'ScheduleController@getSchedulesByStepLead')->name('step.lead.schedules');
		Route::get('funnel/{funnel}/show/json', 'FunnelController@showJson');
		Route::get('customer/statuses/json', 'CustomerStatusController@getCustomerStatusJson');
		Route::get('payment_types/json', 'PaymentTypeController@getPaymentTypesJson');
		Route::post('customers/json', 'CustomerController@getCustomersJson');


		/* mail template routes */
		Route::post('mailtemplate', 'MailTemplateController@store');
		Route::post('mailtemplate/exists', 'MailTemplateController@templateExists');
		Route::get('mailtemplate/list', 'MailTemplateController@listTemplates');
		Route::get('mailtemplate/{mailTemplate}', 'MailTemplateController@loadTemplate');
	});

	Route::middleware(['auth:web', 'signed'])->group(function() {
		Route::get('email/verify/{id}/{hash}', 'Auth\User\VerificationController@verify')->name('verification.verify');
	});
});

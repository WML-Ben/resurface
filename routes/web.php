<?php

// triggered when unknown url (when using \App::abort(404), Exception/Handler.php is executed
Route::fallback('ErrorsController@error404')->name('fallback');

// Authentication Routes...
Route::get('login',    'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login');

Route::post('logout',                   'Auth\LoginController@logout')->name('logout');
Route::match(['get', 'post'], 'logout', 'Auth\LoginController@logout')->name('logout_en');

// Password Reset Routes...
Route::get('password/reset',  'Auth\ForgotPasswordController@showLinkRequestForm')->name('forgot_password_email_form');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgot_password_send_reset_link');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('forgot_password_reset_link');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('forgot_password_reset');

// Registration Routes... Avoid auto-registering. Redirect to login:
Route::match(['get', 'post'], 'register', function(){
    \App::abort(403); // forbidden
});



/**  Temp routes to "Under Development" sections */

Route::group(['prefix' => 'tmp'], function () {
    Route::get('/', 'TmpController@index')->name('tmp_example');
});


/***********************************  Session Timeout  ***********************************/

Route::match(['get', 'post'], 'lockout', 'Auth\IdleController@lockOut')->name('lockout');
Route::post('unlock', 'Auth\IdleController@unlock')->name('unlock')->middleware('GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:2,1');
/** END Session Timeout */


/***********************************  Ajax  ***********************************/

Route::post('settimezone', 'AjaxController@setTimezone')->name('ajax_set_timezone');
Route::post('uploadtinymceimage', 'AjaxController@uploadTinyMceImage')->name('upload_tinymce_image');
Route::post('uploadinsertedimage', 'AjaxController@uploadInsertedImage')->name('uploadinsertedimage');
Route::post('ajaxfetchstates', 'AjaxController@fetchStates')->name('ajax_state_fetch');
Route::post('ajaxfetchcompanyusers', 'AjaxController@fetchCompanyUsers')->name('ajax_company_users_fetch');
Route::post('ajaxfetchcompanydetails', 'AjaxController@fetchCompanyDetails')->name('ajax_company_details_fetch');
Route::post('ajaxfetchpropertydetails', 'AjaxController@fetchPropertyDetails')->name('ajax_property_details_fetch');
Route::post('ajaxfetchusremailandphone', 'AjaxController@fetchUserEmailAndPhone')->name('ajax_user_email_and_phone_fetch');
Route::post('ajaxfetchservices', 'AjaxController@fetchServices')->name('ajax_services_fetch');
Route::post('ajaxfetchsubcontractor', 'AjaxController@fetchSubContractor')->name('ajax_sub_contractor_fetch');
Route::post('ajax_get_information_property', 'AjaxController@ajaxGetInformationProperty')->name('ajax_get_information_property');
Route::post('ajax_choose_lead_email_template', 'AjaxController@getLeadEmailTemplateByID')->name('ajax_choose_lead_email_template');
Route::post('upload_files_attach_lead', 'AjaxController@uploadFilesAttacheLead')->name('upload_files_attach_lead');
Route::post('ajax_delete_file_lead', 'AjaxController@ajaxDeleteFileLead')->name('ajax_delete_file_lead');

/** END Ajax */


/***********************************  Main  ***********************************/

Route::get('/', 'MainController@dashboard')->name('dashboard');
Route::match(['get', 'post'], 'globalsearch', 'MainController@search')->name('global_search');
/** END Main */


/***********************************  Employees  ***********************************/

Route::group(['prefix' => 'employees'], function () {
    Route::post('/ajaxUploadImage', 'EmployeesController@ajaxUploadImage')->name('employee_ajax_upload_image');
    Route::post('/ajaxDeleteImage', 'EmployeesController@ajaxDeleteImage')->name('employee_ajax_delete_image');
    Route::post('/ajaxDeleteSignature', 'EmployeesController@ajaxDeleteSignature')->name('employee_ajax_delete_signature');

    Route::post('/ajaxfetchrolesprivileges', 'EmployeesController@ajaxFetchRolesPrivileges')->name('employee_fetch_roles_privileges');
    Route::get('/', 'EmployeesController@index')->name('employee_list');
    Route::match(['get', 'post'], '/search', 'EmployeesController@search')->name('employee_search');
    Route::get('/create', 'EmployeesController@create')->name('employee_create');
    Route::get('/{employee}', 'EmployeesController@show')->name('employee_show');
    Route::post('/', 'EmployeesController@store')->name('employee_store');
    Route::get('/{employee}/edit', 'EmployeesController@edit')->name('employee_edit');
    Route::get('/{employee}/togglestatus', 'EmployeesController@toggleStatus')->name('employee_toggle_status');
    Route::post('/inlineupdate', 'EmployeesController@inlineUpdate')->name('employee_inline_update');
    Route::patch('/{employee}', 'EmployeesController@update')->name('employee_update');
    Route::delete('/', 'EmployeesController@destroy')->name('employee_delete');
});
/** END Employees */



/***********************************  Contacts  ***********************************/

Route::group(['prefix' => 'contacts'], function () {
    Route::post('/ajaxUploadImage', 'ContactsController@ajaxUploadImage')->name('contact_ajax_upload_image');
    Route::post('/ajaxDeleteImage', 'ContactsController@ajaxDeleteImage')->name('contact_ajax_delete_image');
    Route::post('/ajaxDeleteSignature', 'ContactsController@ajaxDeleteSignature')->name('contact_ajax_delete_signature');
    Route::get('/', 'ContactsController@index')->name('contact_list');
    Route::match(['get', 'post'], '/search', 'ContactsController@search')->name('contact_search');
    Route::get('profile', 'ContactsController@profile')->name('contact_profile');
    Route::patch('/profile', 'ContactsController@updateProfile')->name('contact_update_profile');
    Route::get('/create', 'ContactsController@create')->name('contact_create');
    Route::get('/{contact}', 'ContactsController@show')->name('contact_show');
    Route::post('/', 'ContactsController@store')->name('contact_store');
    Route::post('/ajaxstore', 'ContactsController@ajaxStore')->name('ajax_contact_store');
    Route::get('/{contact}/edit', 'ContactsController@edit')->name('contact_edit');
    Route::get('/{contact}/togglestatus', 'ContactsController@toggleStatus')->name('contact_toggle_status');
    Route::post('/inlineupdate', 'ContactsController@inlineUpdate')->name('contact_inline_update');
    Route::patch('/{contact}', 'ContactsController@update')->name('contact_update');
    Route::delete('/', 'ContactsController@destroy')->name('contact_delete');
});
/** END Contacts */


/***********************************  Clients  ***********************************/

Route::group(['prefix' => 'clients'], function () {
    Route::post('/ajaxUploadImage', 'ClientsController@ajaxUploadImage')->name('ajax_upload_image');
    Route::post('/ajaxDeleteImage', 'ClientsController@ajaxDeleteImage')->name('ajax_delete_image');
    Route::post('/ajaxfetchrolesprivileges', 'ClientsController@ajaxFetchRolesPrivileges')->name('fetch_roles_privileges');
    Route::get('/', 'ClientsController@index')->name('list');
    Route::match(['get', 'post'], '/search', 'ClientsController@search')->name('search');
    Route::get('profile', 'ClientsController@profile')->name('profile');
    Route::patch('/profile', 'ClientsController@updateProfile')->name('update_profile');
    Route::get('/create', 'ClientsController@create')->name('create');
    Route::get('/{id}', 'ClientsController@show')->name('show');
    Route::post('/', 'ClientsController@store')->name('store');
    Route::get('/{id}/edit', 'ClientsController@edit')->name('edit');
    Route::get('/{id}/togglestatus', 'ClientsController@toggleStatus')->name('toggle_status');
    Route::post('/inlineupdate', 'ClientsController@inlineUpdate')->name('inline_update');
    Route::patch('/{id}', 'ClientsController@update')->name('update');
    Route::delete('/', 'ClientsController@destroy')->name('delete');
});
/** END Clients */


/*********************************** Roles   ***********************************/

Route::group(['prefix' => 'roles'], function () {
    Route::post('/ajaxgetroleprivileges', 'RolesController@ajaxGetRolePrivileges')->name('ajax_get_role_privileges');
    Route::post('/ajaxupdateroleprivileges', 'RolesController@ajaxUpdateRolePrivileges')->name('ajax_update_role_privileges');
    Route::post('/ajaxupdateroletree', 'RolesController@ajaxUpdateRoleTree')->name('ajax_update_role_tree');
    Route::post('/ajaxupdaterolename', 'RolesController@ajaxUpdateRoleName')->name('ajax_update_role_name');
    Route::post('/ajaxremoverole', 'RolesController@ajaxRemoveRole')->name('ajax_remove_role');
    Route::get('/', 'RolesController@index')->name('role_list');
    Route::post('/', 'RolesController@store')->name('role_store');
});
/** END Roles */


/***********************************  Privileges  ***********************************/

Route::group(['prefix' => 'privileges'], function () {
    Route::get('/', 'PrivilegesController@index')->name('privilege_list');
    Route::match(['get', 'post'], '/search', 'PrivilegesController@search')->name('privilege_search');
    Route::get('/create', 'PrivilegesController@create')->name('privilege_create');
    Route::post('/', 'PrivilegesController@store')->name('privilege_store');
    Route::get('/{id}/togglestatus', 'PrivilegesController@toggleStatus')->name('privilege_toggle_status');
    Route::post('/inlineupdate', 'PrivilegesController@inlineUpdate')->name('privilege_inline_update');
    Route::delete('/', 'PrivilegesController@destroy')->name('privilege_delete');
});
/** END Privileges */


/***********************************  Config  ***********************************/

Route::group(['prefix' => 'config'], function () {
    Route::get('/', 'ConfigController@index')->name('config_list');
    Route::match(['get', 'post'], '/search', 'ConfigController@search')->name('config_search');
    Route::get('/create', 'ConfigController@create')->name('config_create');
    Route::get('/{config}', 'ConfigController@show')->name('config_show');
    Route::post('/', 'ConfigController@store')->name('config_store');
    Route::post('/inlineupdate', 'ConfigController@inlineUpdate')->name('config_inline_update');
    Route::get('/{id}/togglestatus', 'ConfigController@toggleStatus')->name('config_toggle_status');
    Route::delete('/', 'ConfigController@destroy')->name('config_delete');
});
/** END Config */


///  NOT YET WORKED ON

/***********************************  Tasks  ***********************************/

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', 'TasksController@index')->name('task_list');
    Route::match(['get', 'post'], '/search', 'TasksController@search')->name('task_search');
    Route::get('/create', 'TasksController@create')->name('task_create');
    Route::post('/', 'TasksController@store')->name('task_store');
    Route::get('/{id}/togglestatus', 'TasksController@toggleStatus')->name('task_toggle_status');
    Route::post('/inlineupdate', 'TasksController@inlineUpdate')->name('task_inline_update');
    Route::delete('/', 'TasksController@destroy')->name('task_delete');
});
/** END Tasks */


///  NOT YET WORKED ON

/***********************************  Messages  ***********************************/

Route::group(['prefix' => 'messages'], function () {
    Route::get('/inbox', 'MessagesController@receivedList')->name('message_received_list');
    Route::get('/sent', 'MessagesController@sentList')->name('message_sent_list');
    Route::match(['get', 'post'], '/inbox/search', 'MessagesController@receivedSearch')->name('message_received_search');
    Route::match(['get', 'post'], '/sent/search', 'MessagesController@sentSearch')->name('message_sent_search');
    Route::get('/compose', 'MessagesController@create')->name('message_create');
    Route::get('/inbox/reply/{id}', 'MessagesController@receivedReply')->name('message_received_reply');
    Route::get('/inbox/forward/{id}', 'MessagesController@receivedForward')->name('message_received_forward');
    Route::get('/sent/forward/{id}', 'MessagesController@sentForward')->name('message_sent_forward');
    Route::post('/send', 'MessagesController@send')->name('message_send');
    Route::get('/inbox/show/{id}', 'MessagesController@receivedShow')->name('message_received_show');
    Route::get('/sent/show/{id}', 'MessagesController@sentShow')->name('message_sent_show');
    Route::get('/inbox/togglestatus/{id}', 'MessagesController@toggleReceivedStatus')->name('message_received_toggle_status');
    Route::delete('/inbox', 'MessagesController@receivedDestroy')->name('message_received_delete');
    Route::delete('/sent', 'MessagesController@sentDestroy')->name('message_sent_delete');
});
/** END Messages */


/***********************************  Proposals  ***********************************/
Route::group(['prefix' => 'proposals'], function () {
    Route::post('/ajaxUploadImage', 'ProposalsController@ajaxUploadImage')->name('proposal_ajax_upload_image');
    Route::post('/ajaxDeleteImage', 'ProposalsController@ajaxDeleteImage')->name('proposal_ajax_delete_image');

    Route::post('/ajaxclientupdatemanagers', 'ProposalsController@ajaxClientUpdateManagers')->name('ajax_proposal_client_update_managers');
    Route::post('/ajaxclientupdatecontactinfo', 'ProposalsController@ajaxClientUpdateContactInfo')->name('ajax_proposal_client_update_contact_info');
    Route::post('/ajaxclientupdatejoblocation', 'ProposalsController@ajaxClientUpdateJobLocation')->name('ajax_proposal_client_job_location');
    Route::post('/ajaxclientaddnote', 'ProposalsController@ajaxClientAddNote')->name('ajax_proposal_client_add_note');

    Route::get('/', 'ProposalsController@index')->name('proposal_list');
    Route::match(['get', 'post'], '/search', 'ProposalsController@search')->name('proposal_search');

    // proposal_draft_list proposal_pending_list

    Route::get('/draft', 'ProposalsController@draft')->name('proposal_draft_list');
    Route::match(['get', 'post'], '/draft/search', 'ProposalsController@draftSearch')->name('proposal_draft_search');
    Route::get('/pending', 'ProposalsController@pending')->name('proposal_pending_list');
    Route::match(['get', 'post'], '/pending/search', 'ProposalsController@pendingSearch')->name('proposal_pending_search');


    Route::get('/create', 'ProposalsController@create')->name('proposal_create');
    Route::get('/{id}/details/client', 'ProposalsController@detailsClient')->name('proposal_details_client');
    Route::get('/{id}/details/services', 'ProposalsController@detailsServices')->name('proposal_details_services');
    Route::get('/{id}/details/media', 'ProposalsController@detailsMedia')->name('proposal_details_media');
    Route::get('/{id}/details/preview', 'ProposalsController@detailsPreview')->name('proposal_details_preview');
    Route::get('/{id}/details/email', 'ProposalsController@detailsEmail')->name('proposal_details_email');
    Route::get('/{id}/details/followup', 'ProposalsController@detailsFollowUp')->name('proposal_details_follow_up');
    Route::get('/{id}/details/status', 'ProposalsController@detailsStatus')->name('proposal_details_status');

    Route::post('ajaxuploadmedia', 'ProposalsController@ajaxUploadMedia')->name('proposal_ajax_media_upload');
    Route::post('ajaxdeletemedia', 'ProposalsController@ajaxDeleteMedia')->name('proposal_ajax_media_delete');


    Route::post('/{proposal}/services/reorder', 'ProposalsController@detailsServicesReorder')->name('proposal_details_services_reorder');
    Route::post('{proposal}/service/create', 'ProposalsController@detailsServiceCreate')->name('proposal_details_service_create');

    // for redirections (and development)
    Route::get('/service/create/{proposal_id}', 'ProposalsController@detailsServiceCreateFromGet')->name('get_proposal_details_service_create');
    Route::post('{proposal}/service', 'ProposalsController@detailsServiceStore')->name('proposal_details_service_store');
    Route::get('{proposal_id}/service/{service_id}/edit', 'ProposalsController@detailsServiceEdit')->name('proposal_details_service_edit');
    Route::patch('{order_service}/service', 'ProposalsController@detailsServiceUpdate')->name('proposal_details_service_update');

    Route::delete('/service', 'ProposalsController@detailsServiceDestroy')->name('proposal_details_service_delete');

    Route::post('/', 'ProposalsController@store')->name('proposal_store');
    Route::get('/{proposal}/edit', 'ProposalsController@edit')->name('proposal_edit');
    Route::patch('/{proposal}', 'ProposalsController@update')->name('proposal_update');

    Route::get('/{proposal}/togglestatus', 'ProposalsController@toggleStatus')->name('proposal_toggle_status');
    Route::post('/inlineupdate', 'ProposalsController@inlineUpdate')->name('proposal_inline_update');

    Route::get('/{proposal}/addnote', 'ProposalsController@createNote')->name('proposal_note_create');
    Route::post('/{proposal}/addnote', 'ProposalsController@storeNote')->name('proposal_note_store');

    Route::get('/{proposal}/changestatus', 'ProposalsController@editStatus')->name('proposal_status_edit');
    Route::patch('/{proposal}/changestatus', 'ProposalsController@updateStatus')->name('proposal_status_update');

    Route::get('/{proposal}/upload', 'ProposalsController@uploadForm')->name('proposal_upload_form');
    Route::post('/{proposal}/upload', 'ProposalsController@doUpload')->name('proposal_upload_do');

    Route::get('/{proposal}/history', 'ProposalsController@history')->name('proposal_history');
    Route::get('/{proposal}/print', 'ProposalsController@print')->name('proposal_print');

    Route::get('/intakeform', 'ProposalsController@createIntakeForm')->name('proposal_intake_form_create');
    Route::post('/intakeform', 'ProposalsController@storeIntakeForm')->name('proposal_intake_form_store');

    Route::delete('/', 'ProposalsController@destroy')->name('proposal_delete');
});
/** END Proposals */


/***********************************  Work Orders  ***********************************/

Route::model('workorder', \App\WorkOrder::class);

Route::group(['prefix' => 'workorders'], function () {
    Route::post('/ajaxUploadImage', 'WorkOrdersController@ajaxUploadImage')->name('work_order_ajax_upload_image');
    Route::post('/ajaxDeleteImage', 'WorkOrdersController@ajaxDeleteImage')->name('work_order_ajax_delete_image');
    Route::post('/ajaxfetchrolesprivileges', 'WorkOrdersController@ajaxFetchRolesPrivileges')->name('work_order_fetch_roles_privileges');
    Route::get('/', 'WorkOrdersController@index')->name('work_order_list');
    Route::match(['get', 'post'], '/search', 'WorkOrdersController@search')->name('work_order_search');

    Route::get('/processing', 'WorkOrdersController@processing')->name('work_order_processing_list');
    Route::match(['get', 'post'], '/processing/search', 'WorkOrdersController@processingSearch')->name('work_order_processing_search');

    Route::get('/active', 'WorkOrdersController@active')->name('work_order_active_list');
    Route::match(['get', 'post'], '/active/search', 'WorkOrdersController@activeSearch')->name('work_order_active_search');

    Route::get('/billing', 'WorkOrdersController@billing')->name('work_order_billing_list');
    Route::match(['get', 'post'], '/billing/search', 'WorkOrdersController@billingSearch')->name('work_order_billing_search');


    Route::get('/create', 'WorkOrdersController@create')->name('work_order_create');
    Route::get('/{workorder}', 'WorkOrdersController@show')->name('work_order_show');
    Route::post('/', 'WorkOrdersController@store')->name('work_order_store');
    Route::get('/{workorder}/edit', 'WorkOrdersController@edit')->name('work_order_edit');
    Route::patch('/{workorder}', 'WorkOrdersController@update')->name('work_order_update');

    Route::get('/{workorder}/togglestatus', 'WorkOrdersController@toggleStatus')->name('work_order_toggle_status');
    Route::post('/inlineupdate', 'WorkOrdersController@inlineUpdate')->name('work_order_inline_update');

    Route::get('/{workorder}/addnote', 'WorkOrdersController@createNote')->name('work_order_note_create');
    Route::post('/{workorder}/addnote', 'WorkOrdersController@storeNote')->name('work_order_note_store');

    Route::get('/{workorder}/changestatus', 'WorkOrdersController@editStatus')->name('work_order_status_edit');
    Route::patch('/{workorder}/changestatus', 'WorkOrdersController@updateStatus')->name('work_order_status_update');

    Route::get('/{workorder}/upload', 'WorkOrdersController@uploadForm')->name('work_order_upload_form');
    Route::post('/{workorder}/upload', 'WorkOrdersController@doUpload')->name('work_order_upload_do');

    Route::get('/{workorder}/print', 'WorkOrdersController@print')->name('work_order_print');

    Route::get('/{workorder}/services', 'WorkOrdersController@services')->name('work_order_services');

    Route::get('/{workorder}/notices', 'WorkOrdersController@notices')->name('work_order_notices');

    Route::delete('/', 'WorkOrdersController@destroy')->name('work_order_delete');
});
/** END Work Orders */


/***********************************  Work Orders  ***********************************/

Route::model('orderservice', \App\OrderService::class);

Route::group(['prefix' => 'orderservices'], function () {
    Route::post('/ajaxUploadImage', 'OrderServicesController@ajaxUploadImage')->name('order_service_ajax_upload_image');
    Route::post('/ajaxDeleteImage', 'OrderServicesController@ajaxDeleteImage')->name('order_service_ajax_delete_image');
    Route::post('/ajaxfetchrolesprivileges', 'OrderServicesController@ajaxFetchRolesPrivileges')->name('order_service_fetch_roles_privileges');
    Route::get('/', 'OrderServicesController@index')->name('order_service_list');
    Route::match(['get', 'post'], '/search', 'OrderServicesController@search')->name('order_service_search');
    Route::get('/create', 'OrderServicesController@create')->name('order_service_create');
    Route::get('/{orderservice}', 'OrderServicesController@show')->name('order_service_show');
    Route::post('/', 'OrderServicesController@store')->name('order_service_store');
    Route::get('/{orderservice}/edit', 'OrderServicesController@edit')->name('order_service_edit');
    Route::patch('/{orderservice}', 'OrderServicesController@update')->name('order_service_update');

    Route::get('/{orderservice}/togglestatus', 'OrderServicesController@toggleStatus')->name('order_service_toggle_status');
    Route::post('/inlineupdate', 'OrderServicesController@inlineUpdate')->name('order_service_inline_update');

    Route::get('/{orderservice}/addnote', 'OrderServicesController@createNote')->name('order_service_note_create');
    Route::post('/{orderservice}/addnote', 'OrderServicesController@storeNote')->name('order_service_note_store');

    Route::get('/{orderservice}/changestatus', 'OrderServicesController@editStatus')->name('order_service_status_edit');
    Route::patch('/{orderservice}/changestatus', 'OrderServicesController@updateStatus')->name('order_service_status_update');

    Route::get('/{orderservice}/upload', 'OrderServicesController@uploadForm')->name('order_service_upload_form');
    Route::post('/{orderservice}/upload', 'OrderServicesController@doUpload')->name('order_service_upload_do');

    Route::get('/{orderservice}/print', 'OrderServicesController@print')->name('order_service_print');

    Route::get('/{orderservice}/services', 'OrderServicesController@services')->name('order_service_services');

    Route::get('/{orderservice}/notices', 'OrderServicesController@notices')->name('order_service_notices');

    Route::delete('/', 'OrderServicesController@destroy')->name('order_service_delete');
});
/** END Order Services */



/***********************************  Properties  ***********************************/

Route::group(['prefix' => 'properties'], function () {
    Route::get('/', 'PropertiesController@index')->name('property_list');
    Route::match(['get', 'post'], '/search', 'PropertiesController@search')->name('property_search');
    Route::get('/create', 'PropertiesController@create')->name('property_create');
    Route::get('/{property}', 'PropertiesController@show')->name('property_show');
    Route::post('/', 'PropertiesController@store')->name('property_store');
    Route::get('/{property}/edit', 'PropertiesController@edit')->name('property_edit');
    Route::get('/{property}/togglestatus', 'PropertiesController@toggleStatus')->name('property_toggle_status');
    Route::post('/inlineupdate', 'PropertiesController@inlineUpdate')->name('property_inline_update');
    Route::patch('/{property}', 'PropertiesController@update')->name('property_update');
    Route::delete('/', 'PropertiesController@destroy')->name('property_delete');
    Route::post('ajaxaewownerstore', 'PropertiesController@ajaxNewOwnerStore')->name('ajax_property_new_owner_store');
});
/** END Properties */



/***********************************  Companies  ***********************************/

Route::group(['prefix' => 'companies'], function () {
    Route::get('/', 'CompaniesController@index')->name('company_list');
    Route::match(['get', 'post'], '/search', 'CompaniesController@search')->name('company_search');
    Route::get('/create', 'CompaniesController@create')->name('company_create');
    Route::get('/{company}', 'CompaniesController@show')->name('company_show');
    Route::post('/', 'CompaniesController@store')->name('company_store');
    Route::post('/ajaxcreatecompany', 'CompaniesController@ajaxStore')->name('ajax_company_create');
    Route::get('/{company}/edit', 'CompaniesController@edit')->name('company_edit');
    Route::get('/{id}/togglestatus', 'CompaniesController@toggleStatus')->name('company_toggle_status');
    Route::post('/inlineupdate', 'CompaniesController@inlineUpdate')->name('company_inline_update');
    Route::patch('/{company}', 'CompaniesController@update')->name('company_update');
    Route::delete('/', 'CompaniesController@destroy')->name('company_delete');
});
/** END Companies */


/***********************************  User Categories  ***********************************/

Route::model('usercategory', \App\UserCategory::class);

Route::group(['prefix' => 'usercategories'], function () {
    Route::get('/', 'UserCategoriesController@index')->name('user_category_list');
    Route::match(['get', 'post'], '/search', 'UserCategoriesController@search')->name('user_category_search');
    Route::get('/create', 'UserCategoriesController@create')->name('user_category_create');
    Route::post('/', 'UserCategoriesController@store')->name('user_category_store');
    Route::get('/{usercategory}/edit', 'UserCategoriesController@edit')->name('user_category_edit');
    Route::get('/{id}/togglestatus', 'UserCategoriesController@toggleStatus')->name('user_category_toggle_status');
    Route::post('/inlineupdate', 'UserCategoriesController@inlineUpdate')->name('user_category_inline_update');
    Route::patch('/{usercategory}', 'UserCategoriesController@update')->name('user_category_update');
    Route::delete('/', 'UserCategoriesController@destroy')->name('user_category_delete');
});
/** END User Categories */


/***********************************  Company Categories  ***********************************/

Route::model('companycategory', \App\CompanyCategory::class);

Route::group(['prefix' => 'companycategories'], function () {
    Route::get('/', 'CompanyCategoriesController@index')->name('company_category_list');
    Route::match(['get', 'post'], '/search', 'CompanyCategoriesController@search')->name('company_category_search');
    Route::get('/create', 'CompanyCategoriesController@create')->name('company_category_create');
    Route::post('/', 'CompanyCategoriesController@store')->name('company_category_store');
    Route::get('/{companycategory}/edit', 'CompanyCategoriesController@edit')->name('company_category_edit');
    Route::get('/{id}/togglestatus', 'CompanyCategoriesController@toggleStatus')->name('company_category_toggle_status');
    Route::post('/inlineupdate', 'CompanyCategoriesController@inlineUpdate')->name('company_category_inline_update');
    Route::patch('/{companycategory}', 'CompanyCategoriesController@update')->name('company_category_update');
    Route::delete('/', 'CompanyCategoriesController@destroy')->name('company_category_delete');
});
/** END Company Categories */



/***********************************  Age Periods  ***********************************/

Route::model('ageperiod', \App\AgePeriod::class);

Route::group(['prefix' => 'ageperiods'], function () {
    Route::get('/', 'AgePeriodsController@index')->name('age_period_list');
    Route::match(['get', 'post'], '/search', 'AgePeriodsController@search')->name('age_period_search');
    Route::get('/create', 'AgePeriodsController@create')->name('age_period_create');
    Route::post('/', 'AgePeriodsController@store')->name('age_period_store');
    Route::get('/{ageperiod}/edit', 'AgePeriodsController@edit')->name('age_period_edit');
    Route::get('/{id}/togglestatus', 'AgePeriodsController@toggleStatus')->name('age_period_toggle_status');
    Route::post('/inlineupdate', 'AgePeriodsController@inlineUpdate')->name('age_period_inline_update');
    Route::patch('/{ageperiod}', 'AgePeriodsController@update')->name('age_period_update');
    Route::delete('/', 'AgePeriodsController@destroy')->name('age_period_delete');
});
/** END Age Periods */


/***********************************  Appointments  ***********************************/

Route::group(['prefix' => 'appointments'], function () {
    Route::get('/', 'AppointmentsController@index')->name('appointment_list');
    Route::match(['get', 'post'], '/search', 'AppointmentsController@search')->name('appointment_search');
    Route::get('/create', 'AppointmentsController@create')->name('appointment_create');
    Route::get('/{appointment}', 'AppointmentsController@show')->name('appointment_show');
    Route::post('/', 'AppointmentsController@store')->name('appointment_store');
    Route::post('/ajaxcreateappointment', 'AppointmentsController@ajaxStore')->name('ajax_appointment_create');
    Route::get('/{appointment}/edit', 'AppointmentsController@edit')->name('appointment_edit');
    Route::get('/{id}/togglestatus', 'AppointmentsController@toggleStatus')->name('appointment_toggle_status');
    Route::post('/inlineupdate', 'AppointmentsController@inlineUpdate')->name('appointment_inline_update');
    Route::patch('/{appointment}', 'AppointmentsController@update')->name('appointment_update');
    Route::delete('/', 'AppointmentsController@destroy')->name('appointment_delete');
});
/** END Appointments */


/***********************************  Tasks  ***********************************/

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', 'TasksController@index')->name('task_list');
    Route::match(['get', 'post'], '/search', 'TasksController@search')->name('task_search');
    Route::get('/create', 'TasksController@create')->name('task_create');
    Route::get('/{task}', 'TasksController@show')->name('task_show');
    Route::post('/', 'TasksController@store')->name('task_store');
    Route::post('/ajaxcreatetask', 'TasksController@ajaxStore')->name('ajax_task_create');
    Route::get('/{task}/edit', 'TasksController@edit')->name('task_edit');
    Route::get('/{id}/togglestatus', 'TasksController@toggleStatus')->name('task_toggle_status');
    Route::post('/inlineupdate', 'TasksController@inlineUpdate')->name('task_inline_update');
    Route::patch('/{task}', 'TasksController@update')->name('task_update');
    Route::delete('/', 'TasksController@destroy')->name('task_delete');
});
/** END Tasks */


/***********************************  Vehicle  ***********************************/

Route::model('vehicle', \App\Vehicle::class);

Route::group(['prefix' => 'vehicle'], function () {
    Route::get('/', 'VehiclesController@index')->name('vehicle_list');
    Route::match(['get', 'post'], '/search', 'VehiclesController@search')->name('vehicle_search');
    Route::get('/create', 'VehiclesController@create')->name('vehicle_create');
    Route::post('/', 'VehiclesController@store')->name('vehicle_store');
    Route::get('/{vehicle}/edit', 'VehiclesController@edit')->name('vehicle_edit');
    Route::get('/{vehicle}/togglestatus', 'VehiclesController@toggleStatus')->name('vehicle_toggle_status');
    Route::post('/inlineupdate', 'VehiclesController@inlineUpdate')->name('vehicle_inline_update');
    Route::patch('/{vehicle}', 'VehiclesController@update')->name('vehicle_update');
    Route::delete('/', 'VehiclesController@destroy')->name('vehicle_delete');
});
/** END Vehicle */


/***********************************  Vehicle Types ***********************************/

Route::model('vehicletype', \App\VehicleType::class);

Route::group(['prefix' => 'vehicletypes'], function () {
    Route::get('/', 'VehicleTypesController@index')->name('vehicle_type_list');
    Route::match(['get', 'post'], '/search', 'VehicleTypesController@search')->name('vehicle_type_search');
    Route::get('/create', 'VehicleTypesController@create')->name('vehicle_type_create');
    Route::post('/', 'VehicleTypesController@store')->name('vehicle_type_store');
    Route::get('/{vehicletype}/edit', 'VehicleTypesController@edit')->name('vehicle_type_edit');
    Route::post('/inlineupdate', 'VehicleTypesController@inlineUpdate')->name('vehicle_type_inline_update');
    Route::patch('/{vehicletype}', 'VehicleTypesController@update')->name('vehicle_type_update');
    Route::delete('/', 'VehicleTypesController@destroy')->name('vehicle_type_delete');
});
/** END Vehicle Types */


/***********************************  Vehicle Log Type  ***********************************/

Route::model('vehiclelogtype', \App\VehicleLogType::class);

Route::group(['prefix' => 'vehiclelogtypes'], function () {
    Route::get('/', 'VehicleLogTypesController@index')->name('vehicle_log_type_list');
    Route::match(['get', 'post'], '/search', 'VehicleLogTypesController@search')->name('vehicle_log_type_search');
    Route::get('/create', 'VehicleLogTypesController@create')->name('vehicle_log_type_create');
    Route::post('/', 'VehicleLogTypesController@store')->name('vehicle_log_type_store');
    Route::get('/{vehiclelogtype}/edit', 'VehicleLogTypesController@edit')->name('vehicle_log_type_edit');
    Route::get('/{vehiclelogtype}/togglestatus', 'VehicleLogTypesController@toggleStatus')->name('vehicle_log_type_toggle_status');
    Route::post('/inlineupdate', 'VehicleLogTypesController@inlineUpdate')->name('vehicle_log_type_inline_update');
    Route::patch('/{vehiclelogtype}', 'VehicleLogTypesController@update')->name('vehicle_log_type_update');
    Route::delete('/', 'VehicleLogTypesController@destroy')->name('vehicle_log_type_delete');
});
/** END Vehicle Log Type */



/***********************************  Materials  ***********************************/

Route::model('material', \App\Material::class);

Route::group(['prefix' => 'materials'], function () {
    Route::get('/', 'MaterialsController@index')->name('materials_list');
    Route::match(['get', 'post'], '/search', 'MaterialsController@search')->name('materials_search');
    Route::get('/create', 'MaterialsController@create')->name('materials_create');
    Route::post('/', 'MaterialsController@store')->name('materials_store');
    Route::get('/{material}/edit', 'MaterialsController@edit')->name('materials_edit');
    Route::get('/{material}/togglestatus', 'MaterialsController@toggleStatus')->name('materials_toggle_status');
    Route::post('/inlineupdate', 'MaterialsController@inlineUpdate')->name('materials_inline_update');
    Route::patch('/{material}', 'MaterialsController@update')->name('materials_update');
    Route::delete('/', 'MaterialsController@destroy')->name('materials_delete');
});
/** END material */

/***********************************  Equipments  ***********************************/

Route::model('equipment', \App\Equipment::class);

Route::group(['prefix' => 'equipments'], function () {
    Route::get('/', 'EquipmentsController@index')->name('equipments_list');
    Route::match(['get', 'post'], '/search', 'EquipmentsController@search')->name('equipments_search');
    Route::get('/create', 'EquipmentsController@create')->name('equipments_create');
    Route::post('/', 'EquipmentsController@store')->name('equipments_store');
    Route::get('/{equipment}/edit', 'EquipmentsController@edit')->name('equipments_edit');
    Route::get('/{equipment}/togglestatus', 'EquipmentsController@toggleStatus')->name('equipments_toggle_status');
    Route::post('/inlineupdate', 'EquipmentsController@inlineUpdate')->name('equipments_inline_update');
    Route::patch('/{equipment}', 'EquipmentsController@update')->name('equipments_update');
    Route::delete('/', 'EquipmentsController@destroy')->name('equipments_delete');
});
/** END Equipment */


/***********************************  Labor  ***********************************/

Route::model('labor', \App\Labor::class);

Route::group(['prefix' => 'labor'], function () {
    Route::get('/', 'LaborController@index')->name('labor_list');
    Route::match(['get', 'post'], '/search', 'LaborController@search')->name('labor_search');
    Route::get('/create', 'LaborController@create')->name('labor_create');
    Route::post('/', 'LaborController@store')->name('labor_store');
    Route::get('/{labor}/edit', 'LaborController@edit')->name('labor_edit');
    Route::get('/{labor}/togglestatus', 'LaborController@toggleStatus')->name('labor_toggle_status');
    Route::post('/inlineupdate', 'LaborController@inlineUpdate')->name('labor_inline_update');
    Route::patch('/{labor}', 'LaborController@update')->name('labor_update');
    Route::delete('/', 'LaborController@destroy')->name('labor_delete');
});
/** END Labor */


/***********************************  Location company locations  ***********************************/

Route::model('location', \App\Location::class);

Route::group(['prefix' => 'location'], function () {
    Route::get('/', 'LocationsController@index')->name('location_list');
    Route::match(['get', 'post'], '/search', 'LocationsController@search')->name('location_search');
    Route::get('/create', 'LocationsController@create')->name('location_create');
    Route::post('/', 'LocationsController@store')->name('location_store');
    Route::get('/{location}/edit', 'LocationsController@edit')->name('location_edit');
    Route::get('/{location}/togglestatus', 'LocationsController@toggleStatus')->name('location_toggle_status');
    Route::post('/inlineupdate', 'LocationsController@inlineUpdate')->name('location_inline_update');
    Route::patch('/{location}', 'LocationsController@update')->name('location_update');
    Route::delete('/', 'LocationsController@destroy')->name('location_delete');
});
/** END Location */



//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
/*Route Intake*/
Route::group(['prefix' => 'intake'], function () {
	Route::post('/store_intake_form', 'LeadController@storeIntakeForm')->name('store_intake_form');
    Route::get('/create', 'LeadController@createIntakeForm')->name('lead_create');
	Route::get('/test', 'LeadController@test');
	Route::get('/lists', 'LeadController@lists')->name('intake_list');
	Route::match(['get', 'post'], '/search', 'LeadController@search')->name('intake_search');
	Route::get('/detail/{ID}', 'LeadController@detail')->name('intake_detail');
	Route::post('/update', 'LeadController@update')->name('intake_update');
	Route::get('/send', 'LeadController@send');
	Route::post('/ajax_send_email_to_customer', 'LeadController@sentEmailtoCustomer')->name('ajax_send_email_to_customer');
	Route::post('/ajax_add_comment_to_customer', 'LeadController@addCommenttoCumstomer')->name('ajax_add_comment_to_customer');
});
Route::group(['prefix' => 'email-template'], function () {
	Route::get('/', 'EmailTemplate@index')->name('edit-email-template');
	Route::get('/create', 'EmailTemplate@create')->name('email_template_create');
	Route::post('/store_email_template', 'EmailTemplate@store')->name('store_email_template');
	Route::post('/update_email_template', 'EmailTemplate@update')->name('update_email_template');
});
/*End*/
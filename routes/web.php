<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/addpercentagecharged',  [
    'uses' => 'BillsController@addPercentageCharged'
]);

Route::get('/everyminute',  [
    'uses' => 'IndexController@everyminute'
]);

Route::get('/jobopentoall',  [
    'uses' => 'IndexController@jobopentoall'
]);

Route::get('/reportdaily',  [
    'uses' => 'IndexController@reportdaily'
]);

Route::get('/reportweekly',  [
    'uses' => 'IndexController@reportweekly'
]);

Route::get('/reportmonthly',  [
    'uses' => 'IndexController@reportmonthly'
]);

Route::get('/passiveclient',  [
    'uses' => 'IndexController@passiveclient'
]);

Route::get('/index', [
    'uses' => 'IndexController@getIndex'
]);

Route::get('/overview', [
    'uses' => 'IndexController@getOverview'
]);

Route::get('/front-dashboard', [
    'uses' => 'IndexController@getDashboard'
]);

Route::get('/features', [
    'uses' => 'IndexController@getFeatures'
]);

Route::get('/modules', [
    'uses' => 'IndexController@getModules'
]);

Route::get('/time_saver', [
    'uses' => 'IndexController@getTimeSaver'
]);

Route::get('/transparent', [
    'uses' => 'IndexController@getTransparent'
]);

Route::get('/data_insight',[
    'uses' => 'IndexController@getDataInsight'
]);

Route::get('/coming_soon',[
    'uses' => 'IndexController@getComingSoon'
]);

Route::get('/about_us',[
    'uses' => 'IndexController@getAboutUs'
]);

Route::get('/careers',[
    'uses' => 'IndexController@getCareers'
]);

Route::get('/contact_us',[
    'uses' => 'IndexController@getContactUs'
]);

Route::post('/contactus', [
     'as' => 'contact.us',
     'uses' => 'IndexController@sendMail'
]);

Route::get('/demo_request',[
    'uses' => 'IndexController@getDemoRequest'
]);

Route::post('/demorequest', [
     'as' => 'demo.request',
     'uses' => 'IndexController@sendDemoRequest'
]);

//Auth::routes();

Route::get('/login', [
    'uses' => 'Auth\LoginController@showLoginForm'
 ]);

Route::post('/login', [
    'uses' => 'Auth\LoginController@login'
]);

Route::post('/logout', [
    'uses' => 'Auth\LoginController@logout'
]);

Route::post('/password/email', [
    'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
]);

Route::get('/password/reset', [
    'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
]);

Route::post('/password/reset', [
    'uses' => 'Auth\ResetPasswordController@reset'
]);

// Create Form
Route::get('candidate/add',[
    'as'=>'candidate.createf',
    'uses'=>'CandidateCreateFormController@createf'
]);

//Store Form
Route::post('candidate/add',[
    'as'=>'candidate.storef',
    'uses'=>'CandidateCreateFormController@storef'
]);

// For get specialization of selected education

Route::get('specialization/getspecializationbyid',[
    'as'=>'get.specialization',
    'uses'=>'CandidateCreateFormController@getSpecialization'
]);

// Report > Daily , Weekly
Route::get('report/daily' ,[
    'as' => 'report.daily',
    'uses' => 'ReportController@dailyreport'
]);

Route::get('report/weekly' ,[
    'as' => 'report.weekly',
    'uses' => 'ReportController@weeklyreport'
]);

Route::group(['middleware' => ['auth']], function () {

    Route::any('/dashboard', array (
        //'middleware' => ['permission:dashboard'],
        'uses' => 'HomeController@dashboard'
    ));

    Route::get('/dashboard/opentoalljob',[
        'as' => 'open.toall',
        'uses' => 'HomeController@OpentoAllJob'
    ]);

    Route::any('/dashboard/monthwise',[
        'as' => 'dashboard.monthwise',
        'uses' => 'HomeController@dashboardMonthwise'
    ]);

    Route::any('/home', array (
        'middleware' => ['permission:display-attendance-of-all-users-in-admin-panel|display-attendance-by-loggedin-user-in-admin-panel'],
        'uses' => 'HomeController@index'
    ));

    Route::any('/userattendance', array (
        'as' => 'user.attendance',
        'middleware' => ['permission:display-attendance-of-all-users|display-attendance-by-loggedin-user'],
        'uses' => 'HomeController@userAttendance'
    ));

    Route::post('/storerserremarks',[
        'as' => 'userremarks.store',
        'uses' => 'HomeController@storeUserRemarks'
    ]);

    Route::post('home/calender',[
        'as' => 'home.calender',
        'uses' => 'HomeController@calenderevent'
    ]);

    Route::post('home/export',[
        'as'=>'home.export',
        'uses'=>'HomeController@export']);

    // test mail route
    Route::get('/testmail',[
        'as' => 'home.testmail',
        'uses' => 'HomeController@testMail'
    ]);

    //lead management route

    Route::get('lead/create',[
        'as'=>'lead.create',
        'uses'=>'LeadController@create',
        'middleware' => ['permission:lead-add']
    ]);

    Route::get('lead',[
        'as'=>'lead.index',
        'uses'=>'LeadController@index',
        'middleware' => ['permission:display-lead|display-user-wise-lead']
    ]);

    Route::get('lead/all',[
        'as' => 'lead.all',
        'uses' => 'LeadController@getAllLeadsDetails',
        'middleware' => ['permission:display-lead|display-user-wise-lead']
    ]);

    Route::get('lead/cancel', [
        'as' => 'lead.leadcancel',
        'uses' => 'LeadController@cancellead',
        'middleware' => ['permission:display-cancel-lead'],
    ]);

    Route::get('lead/cancel/all', [
        'as' => 'lead.cancelall',
        'uses' => 'LeadController@getCancelLeadsDetails',
        'middleware' => ['permission:display-cancel-lead'],
    ]);
    
   Route::post('lead/store', [
        'as' => 'lead.store',
        'uses' => 'LeadController@store',
        'middleware' => ['permission:lead-add'],
    ]);
     
    Route::get('lead/{id}/edit', [
        'as' => 'lead.edit',
        'uses' => 'LeadController@edit',
        'middleware' => ['permission:lead-edit'],
    ]);

    Route::put('lead/{id}', [
        'as' => 'lead.update',
        'uses' => 'LeadController@update',
        'middleware' => ['permission:lead-edit'],
    ]);

    Route::delete('lead/{id}', [
        'as' => 'lead.destroy',
        'uses' => 'LeadController@destroy',
        'middleware' => ['permission:lead-delete'],
    ]);

    Route::get('lead/{id}/clone', [
        'as' => 'lead.clone',
        'uses' => 'LeadController@leadClone',
        'middleware' => ['permission:lead-to-client'],
    ]);
    
    Route::post('lead/{id}', [
        'as' => 'lead.clonestore',
        'uses' => 'LeadController@clonestore',
        'middleware' => ['permission:lead-to-client'],
    ]); 

    Route::get('lead/{id}', [
        'as' => 'lead.cancel',
        'uses' => 'LeadController@cancel',
        'middleware' => ['permission:cancel-lead'],
    ]);

    //User Profile
    
    Route::get('users/editprofile/{id}',[
        'as' => 'users.editprofile',
        'uses' => 'UserController@editProfile',
        'middleware' => ['permission:edit-profile-of-loggedin-user']
    ]);

    Route::post('users/profilestore/{id}',[
        'as' => 'users.profilestore',
        'uses' => 'UserController@profileStore',
        'middleware' => ['permission:edit-profile-of-loggedin-user']
    ]);

    Route::get('users/myprofile/{id}',[
        'as' => 'users.myprofile',
        'uses' => 'UserController@profileShow',
        'middleware' => ['permission:user-profile']
    ]);

    Route::post('usersattachments/upload/{id}',[
        'as' => 'usersattachments.upload',
        'uses' => 'UserController@Upload'
    ]);

    Route::post('/upload-signature',[
        'as' => 'upload.signature',
        'uses' => 'UserController@uploadSignatureImage'
    ]);

    Route::delete('usersattachments/destroy/{id}',[
        'as' =>'usersattachments.destroy',
        'uses' =>'UserController@attachmentsDestroy'
    ]);

    // User Leave Route
    Route::get('/leave',[
        'as' => 'leave.index',
        'uses' => 'LeaveController@index',
    ]);

    Route::get('leave/add',[
        'as' => 'leave.add',
        'uses' => 'LeaveController@userLeaveAdd'
    ]);

    Route::post('leave/add',[
        'as' => 'leave.store',
        'uses' => 'LeaveController@leaveStore'
    ]);

    Route::get('leave/reply/{id}',[
        'as' => 'leave.reply',
        'uses' => 'LeaveController@leaveReply'
    ]);

    Route::post('leave/reply/{id}',[
        'as' => 'leave.replysend',
        'uses' => 'LeaveController@leaveReplySend'
    ]);

    // All Users Leave Balance Routes
    Route::get('userwiseleave',[
        'as' => 'leave.userwise',
        'uses' => 'LeaveController@userWiseLeave'
    ]);

    Route::get('userwiseleave/create',[
        'as' => 'leave.userwisecreate',
        'uses' => 'LeaveController@userWiseLeavaAdd'
    ]);

    Route::post('userwiseleave/create',[
        'as' => 'leave.userwisestore',
        'uses' => 'LeaveController@userWiseLeaveStore'
    ]);

    Route::get('userwiseleave/{id}/edit',[
        'as' => 'leave.userwiseedit',
        'uses' => 'LeaveController@userWiseLeaveEdit'
    ]);

    Route::patch('userwiseleave/{id}/edit',[
        'as' => 'leave.userwiseupdate',
        'uses' => 'LeaveController@userWiseLeaveUpdate'
    ]);

    Route::delete('userwiseleave/{id}',[
        'as' => 'leaveuserwise.destroy',
        'uses' => 'LeaveController@userWiseLeaveDestroy'
    ]);

    // Admin > Users

    Route::get('users', [
        'as' => 'users.index',
        'uses' => 'UserController@index',
        'middleware' => ['permission:display-users|user-add|user-edit|user-delete']
    ]);

    Route::get('users/attendance',[
        'as' => 'users.attendance',
        'uses' => 'UserController@UserAttendanceAdd',
        'middleware' => ['permission:display-attendance-of-all-users']
    ]);

    Route::post('users/attendance',[
        'as' => 'users.attendancestore',
        'uses' => 'UserController@UserAttendanceStore',
        'middleware' => ['permission:display-attendance-of-all-users']
    ]);
    
    Route::get('users/create', [
        'as' => 'users.create',
        'uses' => 'UserController@create',
        'middleware' => ['permission:user-add']
    ]);

    Route::post('users/create', [
        'as' => 'users.store',
        'uses' => 'UserController@store',
        'middleware' => ['permission:user-add']
    ]);

    Route::get('users/{id}', [
        'as' => 'users.show',
        'uses' => 'UserController@show',
        'middleware' => ['permission:display-users']
    ]);

    Route::get('users/{id}/edit', [
        'as' => 'users.edit',
        'uses' => 'UserController@edit',
        'middleware' => ['permission:user-edit']
    ]);

    Route::patch('users/{id}', [
        'as' => 'users.update',
        'uses' => 'UserController@update',
        'middleware' => ['permission:user-edit']
    ]);

    Route::delete('users/{id}', [
        'as' => 'users.destroy',
        'uses' => 'UserController@destroy',
        'middleware' => ['permission:user-delete']
    ]);

    Route::post('users/jobopentoall',[
        'as' => 'users.jobopentoall',
        'uses' => 'UserController@setJobOpentoAll',
    ]);

    // Admin > Roles
    Route::get('roles', [
        'as' => 'roles.index',
        'uses' => 'RoleController@index',
        'middleware' => ['permission:display-roles|role-add|role-edit|role-delete']
    ]);

    Route::get('roles/create', [
        'as' => 'roles.create',
        'uses' => 'RoleController@create',
        'middleware' => ['permission:role-add']
    ]);

    Route::post('roles/create', [
        'as' => 'roles.store',
        'uses' => 'RoleController@store',
        'middleware' => ['permission:role-add']
    ]);

    Route::get('roles/{id}', [
        'as' => 'roles.show',
        'uses' => 'RoleController@show',
        'middleware' => ['permission:display-roles']
    ]);

    Route::get('roles/{id}/edit', [
        'as' => 'roles.edit',
        'uses' => 'RoleController@edit',
        'middleware' => ['permission:role-edit']
    ]);

    Route::patch('roles/{id}', [
        'as' => 'roles.update',
        'uses' => 'RoleController@update',
        'middleware' => ['permission:role-edit']
    ]);
    
    Route::delete('roles/{id}', [
        'as' => 'roles.destroy',
        'uses' => 'RoleController@destroy',
        'middleware' => ['permission:role-delete']
    ]);

    Route::resource('documents', 'DocumentController');

    // Admin > Industry
    Route::get('industry', [
        'as' => 'industry.index',
        'uses' => 'IndustryController@index',
        //'middleware' => ['permission:industry-list|industry-create|industry-edit|industry-delete']
    ]);

    Route::get('industry/create', [
        'as' => 'industry.create',
        'uses' => 'IndustryController@create',
        //'middleware' => ['permission:industry-create']
    ]);

    Route::post('industry/create', [
        'as' => 'industry.store',
        'uses' => 'IndustryController@store',
        //'middleware' => ['permission:industry-create']
    ]);

    Route::get('industry/{id}', [
        'as' => 'industry.show',
        'uses' => 'IndustryController@show',
        //'middleware' => ['permission:industry-list']
    ]);

    Route::get('industry/{id}/edit', [
        'as' => 'industry.edit',
        'uses' => 'IndustryController@edit',
        //'middleware' => ['permission:industry-edit']
    ]);

    Route::patch('industry/{id}', [
        'as' => 'industry.update',
        'uses' => 'IndustryController@update',
        //'middleware' => ['permission:industry-edit']
    ]);

    Route::delete('industry/{id}', [
        'as' => 'industry.destroy',
        'uses' => 'IndustryController@destroy',
        //'middleware' => ['permission:industry-delete']
    ]);

    // Admin > Permissions
    //Route::group(['middleware' => ['permission:permission-list']], function () {
    Route::group([], function () {
        Route::resource('permissions', 'PermissionsController', [
            'except' => 'show',
            'names' => [
                'index' => 'permission.index',
                'create' => 'permission.create',
                'store' => 'permission.store',
                'show' => 'permission.show',
                'update' => 'permission.update',
                'edit' => 'permission.edit',
                'destroy' => 'permission.destroy',
            ],
        ]);
    });

    // Admin > Candidate Source
    Route::get('candidateSource', [
        'as' => 'candidateSource.index',
        'uses' => 'CandidateSourceController@index',
        //'middleware' => ['permission:candidatesource-list|candidatesource-create|candidatesource-edit|candidatesource-delete']
    ]);

    Route::get('candidateSource/create', [
        'as' => 'candidateSource.create',
        'uses' => 'CandidateSourceController@create',
        //'middleware' => ['permission:candidatesource-create']
    ]);

    Route::post('candidateSource/create', [
        'as' => 'candidateSource.store',
        'uses' => 'CandidateSourceController@store',
        //'middleware' => ['permission:candidatesource-create']
    ]);

    Route::get('candidateSource/{id}', [
        'as' => 'candidateSource.show',
        'uses' => 'CandidateSourceController@show',
        //'middleware' => ['permission:candidatesource-list']
    ]);

    Route::get('candidateSource/{id}/edit', [
        'as' => 'candidateSource.edit',
        'uses' => 'CandidateSourceController@edit',
        //'middleware' => ['permission:candidatesource-edit']
    ]);

    Route::patch('candidateSource/{id}', [
        'as' => 'candidateSource.update',
        'uses' => 'CandidateSourceController@update',
        //'middleware' => ['permission:candidatesource-edit']
    ]);

    Route::delete('candidateSource/{id}', [
        'as' => 'candidateSource.destroy',
        'uses' => 'CandidateSourceController@destroy',
        //'middleware' => ['permission:candidatesource-delete']
    ]);

    Route::get('candidate/resume', [
        'as' => 'candidate.resume',
        'uses' => 'CandidateController@extractResume'
    ]);

    Route::post('candidate/resume', [
        'as' => 'candidate.resumeStore',
        'uses' => 'CandidateController@extractResumeStore'
    ]);

    // Admin > Candidate Status
    Route::get('candidateStatus', [
        'as' => 'candidateStatus.index',
        'uses' => 'candidateStatusController@index',
        //'middleware' => ['permission:candidatestatus-list|candidatestatus-create|candidatestatus-edit|candidatestatus-delete']
    ]);
    Route::get('candidateStatus/create', [
        'as' => 'candidateStatus.create',
        'uses' => 'candidateStatusController@create',
        //'middleware' => ['permission:candidatestatus-create']
    ]);

    Route::post('candidateStatus/create', [
        'as' => 'candidateStatus.store',
        'uses' => 'candidateStatusController@store',
        //'middleware' => ['permission:candidatestatus-create']
    ]);

    Route::get('candidateStatus/{id}', [
        'as' => 'candidateStatus.show',
        'uses' => 'candidateStatusController@show',
        //'middleware' => ['permission:candidatestatus-list']
    ]);

    Route::get('candidateStatus/{id}/edit', [
        'as' => 'candidateStatus.edit',
        'uses' => 'candidateStatusController@edit',
        //'middleware' => ['permission:candidatestatus-edit']
    ]);

    Route::patch('candidateStatus/{id}', [
        'as' => 'candidateStatus.update',
        'uses' => 'candidateStatusController@update',
        //'middleware' => ['permission:candidatestatus-edit']
    ]);

    Route::delete('candidateStatus/{id}', [
        'as' => 'candidateStatus.destroy',
        'uses' => 'candidateStatusController@destroy',
        //'middleware' => ['permission:candidatestatus-delete']
    ]);

    // Client

    Route::get('client/create', [
        'as' => 'client.create',
        'uses' => 'ClientController@create',
        'middleware' => ['permission:client-add']
    ]);

    Route::get('client/clientemail', [
        'as' => 'client.clientemail',
        'uses' => 'ClientController@postClientNames'
    ]);

    Route::post('client/emailnotification', [
        'as' => 'client.emailnotification',
        'uses' => 'ClientController@postClientEmails'
    ]);

    Route::post('client/checkClientId',[
        'as' => 'client.checkClientId',
        'uses' => 'ClientController@checkClientId'
    ]);

    Route::post('client/accountmanager', [
        'as' => 'client.accountmanager',
        'uses' => 'ClientController@postClientAccountManager'
    ]);

    Route::post('client/secondlineam', [
        'as' => 'client.secondlineam',
        'uses' => 'ClientController@postSecondlineClientAccountManager'
    ]);
     
    Route::get('client', [
        'as' => 'client.index',
        'uses' => 'ClientController@index',
        'middleware' => ['permission:display-client|display-account-manager-wise-client']
    ]);

    Route::get('client-list/Forbid',[
        'as' => 'clients.forbid',
        'uses' => 'ClientController@getForbidClient',
        'middleware' => ['permission:display-forbid-client']
    ]);
    
    Route::get('client/all', [
        'as' => 'client.all',
        'uses' => 'ClientController@getAllClientsDetails',
        'middleware' => ['permission:display-client|display-account-manager-wise-client']
    ]);

    Route::get('client-list/{source}', [
        'as' => 'client.list',
        'uses' => 'ClientController@getAllClientsBySource',
        'middleware' => ['permission:display-client|display-account-manager-wise-client']
    ]);

    Route::get('client/allbytype', [
        'as' => 'client.allbytype',
        'uses' => 'ClientController@getAllClientsDetailsByType',
        'middleware' => ['permission:display-client|display-account-manager-wise-client']
    ]);

    Route::get('client-list', [
        'as' => 'clientlist.amwise',
        'uses' => 'ClientController@getAllClientsByAM',
        'middleware' => ['permission:display-client|display-account-manager-wise-client']
    ]);

    Route::get('/allbyam', [
        'as' => 'client.allbyam',
        'uses' => 'ClientController@getAllClientsDetailsByAM',
        'middleware' => ['permission:display-client|display-account-manager-wise-client']
    ]);

    Route::get('monthwiseclient/{month}/{year}', [
        'as' => 'monthwiseclient.index',
        'uses' => 'ClientController@getMonthWiseClient',
        'middleware' => ['permission:display-client|display-account-manager-wise-client']
    ]);

    Route::post('client/create', [
        'as' => 'client.store',
        'uses' => 'ClientController@store',
        'middleware' => ['permission:client-add']
    ]);

    Route::get('client/importExport', 'ClientController@importExport');
    Route::post('client/importExcel', 'ClientController@importExcel');

    Route::get('client/{id}', [
        'as' => 'client.show',
        'uses' => 'ClientController@show',
        'middleware' => ['permission:display-client|display-account-manager-wise-client']
    ]);
    
    Route::get('client/{id}/edit', [
        'as' => 'client.edit',
        'uses' => 'ClientController@edit',
        'middleware' => ['permission:client-edit']
    ]);

    // Client Remarks Section Start

    Route::get('client/{id}/remarks', [
        'as' => 'client.remarks',
        'uses' => 'ClientController@remarks'
    ]);

    Route::post('client/{client_id}/post',[
        'as'=>'client.post.write',
        'uses'=>'ClientController@writePost'
    ]);

    Route::post('post/update/{client_id}/{post_id}',[
        'as'=>'client.post.update',
        'uses'=>'ClientController@updateClientRemarks'
    ]);

    Route::post('post/{post_id}',[
        'as'=>'post.comments.write',
        'uses'=>'ClientController@writeComment'
    ]);

    Route::post('client/post/delete/{id}',[
        'as'=>'client.reviewdestroy',
        'uses'=>'ClientController@postDestroy'
    ]);

    Route::post('client/comment/delete/{id}',[
        'as'=>'client.commentdelete',
        'uses'=>'ClientController@commentDestroy'
    ]);

    Route::post('client/comment/update',[
        'as'=>'client.commentupdate',
        'uses'=>'ClientController@updateComment'
    ]);

    // Client Remarks Section End

    Route::patch('client/{id}', [
        'as' => 'client.update',
        'uses' => 'ClientController@update',
        'middleware' => ['permission:client-edit']
    ]);

    Route::delete('client/destroy/{id}', [
        'as' => 'clientattachments.destroy',
        'uses' => 'ClientController@attachmentsDestroy',
        'middleware' => ['permission:client-edit']
    ]);

    Route::post('clientattachments/upload/{id}', [
        'as' => 'clientattachments.upload',
        'uses' => 'ClientController@upload',
        'middleware' => ['permission:client-edit']
    ]);

    Route::delete('client/{id}', [
        'as' => 'client.destroy',
        'uses' => 'ClientController@destroy',
        'middleware' => ['permission:client-delete']
    ]);
    
    Route::post('client/account_manager',[
        'as' => 'client.account_manager',
        'uses' => 'ClientController@getAccountManager',
    ]);

    Route::post('client/secondline_account_manager',[
        'as' => 'client.secondline_account_manager',
        'uses' => 'ClientController@getSecondlineAccountManager',
    ]);

    // Candidate
    Route::get('candidate', [
        'as' => 'candidate.index',
        'uses' => 'CandidateController@index',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::get('applicant-candidate', [
        'as' => 'applicant.candidate',
        'uses' => 'CandidateController@applicantIndex',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::get('candidate/all', [
        'as' => 'candidate.all',
        'uses' => 'CandidateController@getAllCandidates',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::get('applicant-candidate/all', [
        'as' => 'applicant-candidate.all',
        'uses' => 'CandidateController@getAllApplicantCandidates',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::get('candidate/create', [
        'as' => 'candidate.create',
        'uses' => 'CandidateController@create',
        'middleware' => ['permission:candidate-add']
    ]);

    Route::post('candidate', [
        'as' => 'candidate.store',
        'uses' => 'CandidateController@store',
        'middleware' => ['permission:candidate-add']
    ]);

    Route::get('candidate/{id}/edit', [
        'as' => 'candidate.edit',
        'uses' => 'CandidateController@edit',
        'middleware' => ['permission:candidate-edit']
    ]);

    Route::get('applicant-candidate/{id}/edit', [
        'as' => 'applicant-candidate.edit',
        'uses' => 'CandidateController@applicantCandidateEdit',
        'middleware' => ['permission:candidate-edit']
    ]);

    Route::put('candidate/{id}', [
        'as' => 'candidate.update',
        'uses' => 'CandidateController@update',
        'middleware' => ['permission:candidate-edit']
    ]);

    Route::put('applicant-candidate/{id}', [
        'as' => 'applicant-candidate.update',
        'uses' => 'CandidateController@applicantCandidateUpdate',
        'middleware' => ['permission:candidate-edit']
    ]);

    Route::delete('candidate/{id}', [
        'as' => 'candidate.destroy',
        'uses' => 'CandidateController@destroy',
        'middleware' => ['permission:candidate-delete']
    ]);

    Route::get('candidate/{id}/show', [
        'as' => 'candidate.show',
        'uses' => 'CandidateController@show',
        'middleware' => ['permission:candidate-edit']
    ]);

    Route::get('applicant-candidate/{id}/show', [
        'as' => 'applicant-candidate.show',
        'uses' => 'CandidateController@applicantCandidateShow',
        'middleware' => ['permission:candidate-edit']
    ]);

    Route::post('candidateattachments/upload/{id}', [
        'as' => 'candidateattachments.upload',
        'uses' => 'CandidateController@upload',
    ]);

    Route::delete('candidate/destroy/{id}', [
        'as' => 'candidateattachments.destroy',
        'uses' => 'CandidateController@attachmentsDestroy'
    ]);

    Route::get('candidateinfo/{id}', [
        'as' => 'candidate.candidateinfo',
        'uses' => 'CandidateController@getCandidateInfo'
    ]);

    Route::get('candidatejoin/{month}/{year}', [
        'as' => 'candidatejoin.index',
        'uses' => 'CandidateController@candidatejoin',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::get('candidate/importExport', 'CandidateController@importExport');
    Route::post('candidate/importExcel', 'CandidateController@importExcel');
    Route::get('candidate/fullname', 'CandidateController@fullname');
    Route::get('candidatejoin/salary', 'CandidateController@candidatesalary');

    Route::post('candidate/candidate_owner',[
        'as' => 'candidate.candidate_owner',
        'uses' => 'CandidateController@getCandidateOwner',
    ]);

    // Daily Report
    Route::get('dailyreport', [
        'as' => 'dailyreport.index',
        'uses' => 'DailyReportController@index'
    ]);

    Route::get('dailyreport/create', [
        'as' => 'dailyreport.create',
        'uses' => 'DailyReportController@create'
    ]);

    Route::post('dailyreport', [
        'as' => 'dailyreport.store',
        'uses' => 'DailyReportController@store'
    ]);

    Route::get('dailyreport/{id}/edit', [
        'as' => 'dailyreport.edit',
        'uses' => 'DailyReportController@edit'
    ]);

    Route::put('dailyreport/{id}', [
        'as' => 'dailyreport.update',
        'uses' => 'DailyReportController@update'
    ]);

    Route::delete('dailyreport/{id}', [
        'as' => 'dailyreport.destroy',
        'uses' => 'DailyReportController@destroy'
    ]);

    Route::get('dailyreport/{id}/show', [
        'as' => 'dailyreport.show',
        'uses' => 'DailyReportController@show'
    ]);

    Route::post('dailyreport/reportMailToandCC', [
        'as' => 'dailyreport.reportMailToandCC',
        'uses' => 'DailyReportController@reportMailToandCC'
    ]);

    Route::post('dailyreport/sendmail', [
        'as' => 'dailyreport.reportMail',
        'uses' => 'DailyReportController@reportMail'
    ]);

    // Job Opening

    Route::get('all-jobs', [
        'as' => 'all.jobs',
        'uses' => 'JobOpenController@getAllPositionsJobs'
    ]);

     Route::get('get-alljobs', [
        'as' => 'get.alljobs',
        'uses' => 'JobOpenController@getAllPositionsJobsByAJAX'
    ]);

    Route::get('all-jobs/{id}/associated_candidates', [
        'as' => 'alljobs.associated_candidates_get',
        'uses' => 'JobOpenController@getAllJobsAssociatedCandidates'
    ]);

    Route::get('jobs/create', [
        'as' => 'jobopen.create',
        'uses' => 'JobOpenController@create',
        'middleware' => ['permission:job-add']
    ]);

    Route::get('jobs/clone/{id}', [
        'as' => 'jobopen.clone',
        'uses' => 'JobOpenController@jobClone',
        'middleware' => ['permission:clone-job']
    ]);

    Route::get('jobs/importExport', 'JobOpenController@importExport');
    Route::post('jobs/importExcel', 'JobOpenController@importExcel');
    Route::get('jobs/salary', 'JobOpenController@salary');
    Route::get('jobs/work', 'JobOpenController@work');
    Route::get('jobs/opentoalldate', 'JobOpenController@openToAllDate');

    Route::any('jobs', [
        'as' => 'jobopen.index',
        'uses' => 'JobOpenController@index',
        'middleware' => ['permission:display-jobs|display-jobs-by-loggedin-user']
    ]);
    
    Route::get('jobs/all', [
        'as' => 'jobopen.all',
        'uses' => 'JobOpenController@getAllJobsDetails',
        'middleware' => ['permission:display-jobs|display-jobs-by-loggedin-user']
    ]);

    Route::post('jobs', [
        'as' => 'jobopen.index',
        'uses' => 'JobOpenController@index',
        'middleware' => ['permission:display-jobs|display-jobs-by-loggedin-user']
    ]); 

    Route::get('jobs/opentoall', [
        'as' => 'jobopen.toall',
        'uses' => 'JobOpenController@OpentoAll',
        'middleware' => ['permission:display-jobs|display-jobs-by-loggedin-user']
    ]);

    /*Route::get('jobs/priority/{priority}/{year}',[
        'as' => 'jobopen.priority',
        'uses' => 'JobOpenController@priorityWise'
    ]);*/

    Route::get('jobs/priority/{priority}',[
        'as' => 'jobopen.priority',
        'uses' => 'JobOpenController@priorityWise',
        'middleware' => ['permission:display-jobs|display-jobs-by-loggedin-user']
    ]);

    Route::get('jobs/priority/{priority}/{year}',[
        'as' => 'jobclose.priority',
        'uses' => 'JobOpenController@priorityWiseClosedJobs',
        'middleware' => ['permission:display-closed-jobs|display-closed-jobs-by-loggedin-user']
    ]);

    Route::post('jobs/create', [
        'as' => 'jobopen.store',
        'uses' => 'JobOpenController@store',
        'middleware' => ['permission:job-add']
    ]);

    Route::post('jobs/clone', [
        'as' => 'jobopen.clonestore',
        'uses' => 'JobOpenController@clonestore',
        'middleware' => ['permission:clone-job']
    ]); 
    
    Route::get('jobs/{id}', [
        'as' => 'jobopen.show',
        'uses' => 'JobOpenController@show',
        'middleware' => ['permission:display-jobs|display-jobs-by-loggedin-user']
    ]);

   /* Route::get('jobs/{id}/{year}/edit', [
        'as' => 'jobopen.edit',
        'uses' => 'JobOpenController@edit',
        'middleware' => ['permission:job-edit']
    ]);*/

    Route::get('jobs/{id}/edit', [
        'as' => 'jobopen.edit',
        'uses' => 'JobOpenController@edit',
        'middleware' => ['permission:job-edit']
    ]);

    Route::get('jobs/{id}/{year}/edit', [
        'as' => 'jobclose.edit',
        'uses' => 'JobOpenController@editClosedJob',
        'middleware' => ['permission:job-edit']
    ]);

    Route::delete('jobs/{id}', [
        'as' => 'jobopen.destroy',
        'uses' => 'JobOpenController@destroy',
        'middleware' => ['permission:job-delete']
    ]);

    Route::patch('jobs/{id}', [
        'as' => 'jobopen.update',
        'uses' => 'JobOpenController@update',
        'middleware' => ['permission:job-edit']
    ]);

    Route::post('jobs/upload/{id}', [
        'as' => 'jobopen.upload',
        'uses' => 'JobOpenController@upload',
        'middleware' => ['permission:job-edit']
    ]);

    Route::delete('jobs/destroy/{id}', [
        'as' => 'jobopenattachments.destroy',
        'uses' => 'JobOpenController@attachmentsDestroy',
        'middleware' => ['permission:job-edit']
    ]);

    Route::get('jobs/{id}/associate_candidate', [
        'as' => 'jobopen.associate_candidate_get',
        'uses' => 'JobOpenController@associateCandidate',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::get('/associate-candidate/all', [
        'as' => 'associate-candidate.all',
        'uses' => 'JobOpenController@getAllAssociateCandidates',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::post('jobs/associate_candidate', [
        'as' => 'jobopen.associate_candidate',
        'uses' => 'JobOpenController@postAssociateCandidates',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::post('jobs/associated_candidates_count', [
        'as' => 'jobopen.associated_candidates_count',
        'uses' => 'JobOpenController@associateCandidateCount',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::get('jobs/{id}/associated_candidates', [
        'as' => 'jobopen.associated_candidates_get',
        'uses' => 'JobOpenController@associatedCandidates',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::post('jobs/deassociate_candidate', [
        'as' => 'jobopen.deassociate_candidate',
        'uses' => 'JobOpenController@deAssociateCandidates',
        'middleware' => ['permission:display-candidates|display-candidates-by-loggedin-user']
    ]);

    Route::get('jobs/{id}/candidates_details', [
        'as' => 'jobopen.candidates_details_get',
        'uses' => 'JobOpenController@getCandidateDetailsByJob'
    ]);
    
    Route::post('jobs/updatecandidatestatus', [
        'as' => 'jobopen.updatecandidatestatus',
        'uses' => 'JobOpenController@updateCandidateStatus'
    ]);

    Route::post('jobs/scheduleinterview', [
        'as' => 'jobopen.scheduleinterview',
        'uses' => 'JobOpenController@scheduleInterview'
    ]);

    Route::post('jobs/addjoiningdate', [
        'as' => 'jobopen.addjoiningdate',
        'uses' => 'JobOpenController@addJoiningDate',
    ]);

    Route::post('jobs/moreoptions', [
        'as' => 'jobopen.moreoptions',
        'uses' => 'JobOpenController@moreOptions'
    ]);

    Route::post('jobs/status', [
        'as' => 'jobopen.status',
        'uses' => 'JobOpenController@status',
        'middleware' => ['permission:change-job-priority']
    ]);

    // Route for changes priority of multiple job
    Route::post('jobs/checkJobId',[
        'as' => 'jobopen.checkjobid',
        'uses' => 'JobOpenController@checkJobId'
    ]);

    Route::post('jobs/mutijobpriority', [
        'as' => 'jobopen.mutijobpriority',
        'uses' => 'JobOpenController@MultipleJobPriority',
        'middleware' => ['permission:update-multiple-jobs-priority']
    ]);

    Route::get('job/close', [
        'as' => 'jobopen.close',
        'uses' => 'JobOpenController@close',
        'middleware' => ['permission:display-closed-jobs|display-closed-jobs-by-loggedin-user']
    ]);

    Route::get('job/allclose', [
        'as' => 'jobopen.allclose',
        'uses' => 'JobOpenController@getAllCloseJobDetails',
        'middleware' => ['permission:display-closed-jobs|display-closed-jobs-by-loggedin-user']
    ]);

    Route::get('job/associatedcandidate', [
        'as' => 'jobopen.associatedcandidate',
        'uses' => 'JobOpenController@getAssociatedcandidates'
    ]);

    Route::get('job/getClientInfos', [
        'as' => 'jobopen.getClientInfos',
        'uses' => 'JobOpenController@getClientInfos'
    ]);

    //Candidate Shortlisted
    Route::post('jobs/shortlisted/{id}',[
        'as' => 'jobopen.shortlisted',
        'uses' => 'JobOpenController@shortlisted',
    ]);

    //Undo Shortlisted Candidate
    Route::post('jobs/undo/{id}',[
        'as' => 'jobopen.undo',
        'uses' => 'JobOpenController@undoshortlisted',
    ]);

    // Get list of Associated cvs
    Route::get('associatedcvs/{month}/{year}', [
        'as' => 'jobopen.associatedcvs',
        'uses' => 'JobOpenController@associatedCVS',
    ]);

    // Get list of Shortlisted cvs
    Route::get('shortlistedcvs/{month}/{year}', [
        'as' => 'jobopen.shortlistedcvs',
        'uses' => 'JobOpenController@shortlistedCVS',
    ]);

    // Associated candidate mail route
    Route::post('/jobs/checkids',[
        'as' => 'jobs.checkids',
        'uses' => 'JobOpenController@CheckIds'
    ]);

    Route::post('/jobs/checkcandidateids',[
        'as' => 'jobs.checkcandidateids',
        'uses' => 'JobOpenController@CheckCandidateIds'
    ]);

    Route::post('/jobs/usersforsendmail',[
        'as' => 'jobs.usersforsendmail',
        'uses' => 'JobOpenController@UsersforSendMail'
    ]);

    Route::post('/jobs/associatedcandidatemail',[
        'as' => 'jobs.associatedcandidatemail',
        'uses' => 'JobOpenController@AssociatedCandidateMail'
    ]);

    Route::post('/jobs/shortlistedcandidate',[
        'as' => 'jobs.shortlistedcandidate',
        'uses' => 'JobOpenController@shortlistedCandidates'
    ]);

    // Interview Module
    Route::get('interview', [
        'as' => 'interview.index',
        'uses' => 'InterviewController@index',
        'middleware' => ['permission:display-interviews|display-interviews-by-loggedin-user']
    ]);

    Route::get('interview/all', [
        'as' => 'interview.all',
        'uses' => 'InterviewController@getAllInterviewsDetails',
        'middleware' => ['permission:display-interviews|display-interviews-by-loggedin-user']
    ]);

    Route::get('todaytomorrow',[
        'as' => 'interview.todaytomorrow',
        'uses' => 'InterviewController@todaytomorrow',
        'middleware' => ['permission:display-interviews|display-interviews-by-loggedin-user']
    ]);

    Route::get('interview/today', [
        'as' => 'interview.today',
        'uses' => 'InterviewController@today',
        'middleware' => ['permission:display-interviews|display-interviews-by-loggedin-user']
    ]);

    Route::get('interview/tomorrow', [
        'as' => 'interview.tomorrow',
        'uses' => 'InterviewController@tomorrow',
        'middleware' => ['permission:display-interviews|display-interviews-by-loggedin-user']
    ]);

    Route::get('interview/thisweek', [
        'as' => 'interview.thisweek',
        'uses' => 'InterviewController@thisweek',
        'middleware' => ['permission:display-interviews|display-interviews-by-loggedin-user']
    ]);

    Route::get('interview/upcomingprevious', [
        'as' => 'interview.upcomingprevious',
        'uses' => 'InterviewController@UpcomingPrevious',
        'middleware' => ['permission:display-interviews|display-interviews-by-loggedin-user']
    ]);

    Route::get('interview/allbytype', [
        'as' => 'interview.allbytype',
        'uses' => 'InterviewController@getAllInterviewsDetailsByType',
        'middleware' => ['permission:display-interviews|display-interviews-by-loggedin-user']
    ]);

    Route::get('attendedinterview/{month}/{year}',[
        'as' => 'interview.attendedinterview',
        'uses' => 'InterviewController@attendedinterview',
        'middleware' => ['permission:display-interviews|display-interviews-by-loggedin-user']
    ]);

    Route::post('interview/multistatus', [
        'as' => 'interview.multistatus',
        'uses' => 'InterviewController@multipleInterviewStatus',
        'middleware' => ['permission:send-consolidated-schedule']
    ]);

    Route::get('interview/create', [
        'as' => 'interview.create',
        'uses' => 'InterviewController@create',
        'middleware' => ['permission:interview-add']
    ]);

    Route::post('interview/store', [
        'as' => 'interview.store',
        'uses' => 'InterviewController@store',
        'middleware' => ['permission:interview-add']
    ]);

    Route::get('interview/{id}/show', [
        'as' => 'interview.show',
        'uses' => 'InterviewController@show',
        'middleware' => ['permission:display-interviews-by-loggedin-user']
    ]);

    Route::get('interview/{id}/edit/{source}', [
        'as' => 'interview.edit',
        'uses' => 'InterviewController@edit',
        'middleware' => ['permission:interview-edit']
    ]);

    Route::put('interview/{id}/{source}', [
        'as' => 'interview.update',
        'uses' => 'InterviewController@update',
        'middleware' => ['permission:interview-edit']
    ]);

    Route::delete('interview/{id}/{source}', [
        'as' => 'interview.destroy',
        'uses' => 'InterviewController@destroy',
        'middleware' => ['permission:interview-delete']
    ]);

    Route::get('interview/getclientinfos', [
        'as' => 'interview.getclientinfos',
        'uses' => 'InterviewController@getClientInfos'
    ]);

    Route::post('interview/checkidsmail',[
        'as' => 'interview.checkidsmail',
        'uses' => 'InterviewController@CheckIdsforMail',
        'middleware' => ['permission:send-consolidated-schedule']
    ]);

    Route::post('interview/status', [
        'as' => 'interview.status',
        'uses' => 'InterviewController@status'
    ]);

    Route::post('interview/multipleinterviewschedule',[
        'as' => 'interview.multipleinterviewschedule',
        'uses' => 'InterviewController@multipleInterviewScheduleMail',
        'middleware' => ['permission:send-consolidated-schedule']
    ]);

    Route::get('recovery-duplicate', [
        'as' => 'recovery.duplicate',
        'uses' => 'BillsController@billsMade2'
    ]);

    // Bills Module
    Route::get('forecasting/create', [
        'as' => 'bills.create',
        'uses' => 'BillsController@create',
        'middleware' => ['permission:forecasting-add']
    ]);

    Route::get('forecasting', [
        'as' => 'forecasting.index',
        'uses' => 'BillsController@index',
        'middleware' => ['permission:display-forecasting|display-forecasting-by-loggedin-user|display-forecasting-by-candidate-owner']
    ]);

    Route::any('bills/all', [
        'as' => 'bills.all',
        'uses' => 'BillsController@getAllBillsDetails',
        'middleware' => ['permission:display-forecasting|display-forecasting-by-loggedin-user|display-forecasting-by-candidate-owner']
    ]);

    Route::get('forecasting/cancel', [
        'as' => 'forecasting.cancelbnm',
        'uses' => 'BillsController@cancelbnm',
        'middleware' => ['permission:cancel-bill']
    ]);

    Route::get('/bills/cancel/all', [
        'as' => 'bills.cancelall',
        'uses' => 'BillsController@getAllCancelBillsDetails',
        'middleware' => ['permission:cancel-bill']
    ]);

    Route::get('recovery', [
        'as' => 'bills.recovery',
        'uses' => 'BillsController@billsMade',
        'middleware' => ['permission:display-recovery|display-recovery-by-loggedin-user|display-recovery-by-candidate-owner']
    ]);

    Route::get('recovery/cancel', [
        'as' => 'bills.bmcancel',
        'uses' => 'BillsController@cancelbm',
        'middleware' => ['permission:cancel-bill']
    ]);

    Route::get('forecasting/{id}/edit', [
        'as' => 'forecasting.edit',
        'uses' => 'BillsController@edit',
        'middleware' => ['permission:forecasting-edit']
    ]);

    Route::patch('forecasting/{id}', [
        'as' => 'forecasting.update',
        'uses' => 'BillsController@update',
        'middleware' => ['permission:forecasting-edit']
    ]);

    Route::post('forecasting/store', [
        'as' => 'forecasting.store',
        'uses' => 'BillsController@store',
        'middleware' => ['permission:forecasting-add']
    ]);

    Route::get('recovery/{id}/generaterecovery', [
        'as' => 'bills.generaterecovery',
        'uses' => 'BillsController@generateBM',
        'middleware' => ['permission:generate-recovery']
    ]);

    Route::get('forecasting/{id}/show', [
        'as' => 'forecasting.show',
        'uses' => 'BillsController@show',
        'middleware' => ['permission:display-forecasting-by-loggedin-user|display-recovery-by-loggedin-user|display-forecasting-by-candidate-owner|display-recovery-by-candidate-owner']
    ]);

    Route::delete('forecasting/{id}', [
        'as' => 'forecasting.destroy',
        'uses' => 'BillsController@delete',
        'middleware' => ['permission:forecasting-delete']
    ]);

    Route::get('forecasting/{id}', [
        'as' => 'forecasting.cancel',
        'uses' => 'BillsController@cancel',
        'middleware' => ['permission:cancel-bill']
    ]);

    Route::post('bills/downloadexcel', [
        'as' => 'bnm.downloadexcel',
        'uses' => 'BillsController@downloadExcel'
    ]);

    Route::get('bills/getclientinfo', [
        'as' => 'bills.getclientinfo',
        'uses' => 'BillsController@getClientInfo'
    ]);

    Route::get('bills/getcandidateinfo', [
        'as' => 'bills.getcandidateinfo',
        'uses' => 'BillsController@getCandidateInfo'
    ]);

    Route::delete('bills/destroy/{id}', [
        'as' => 'billattachments.destroy',
        'uses' => 'BillsController@attachmentsDestroy',
        'middleware' => ['permission:forecasting-edit']
    ]);

    Route::post('billattachments/upload/{id}', [
        'as' => 'billattachments.upload',
        'uses' => 'BillsController@upload',
        'middleware' => ['permission:forecasting-edit']
    ]);

    // for recovery joining confirmation mail route
    Route::post('recovery/sendconfirmationmail/{id}',[
        'as' => 'recovery.sendconfirmationmail',
        'uses' => 'BillsController@getSendConfirmationMail',
        'middleware' => ['permission:send-joining-confirmation']
    ]);

    // for recovery go confirmation route
    Route::post('recovery/gotconfirmation/{id}',[
        'as' => 'recovery.gotconfirmation',
        'uses' => 'BillsController@getGotConfirmation',
        'middleware' => ['permission:send-joining-confirmation']
    ]);

    // for recovery invoice genereate route
    Route::post('recovery/invoicegenerate/{id}',[
        'as' => 'recovery.invoicegenerate',
        'uses' => 'BillsController@getInvoiceGenerate',
        'middleware' => ['permission:send-joining-confirmation']
    ]);

    // for recovery payment received route
    Route::post('recovery/paymentreceived/{id}',[
        'as' => 'recovery.paymentreceived',
        'uses' => 'BillsController@getPaymentReceived',
        'middleware' => ['permission:send-joining-confirmation']
    ]);

    //for relive bill
    Route::get('recovery/{id}',[
        'as' => 'recovery.relive',
        'uses' => 'BillsController@reliveBill',
        'middleware' => ['permission:cancel-bill']
    ]);

    Route::get('invoice/exceldownload/{id}',[
        'as' => 'invoice.excel',
        'uses' => 'BillsController@DownloadInvoiceExcel',
    ]);

    Route::get('invoice/pdfdownload/{id}',[
        'as' => 'invoice.pdf',
        'uses' => 'BillsController@DownloadInvoicePDF',
    ]);

    // Admin > Teams
    Route::get('team', [
        'as' => 'team.index',
        'uses' => 'TeamController@index',
        //'middleware' => ['permission:team-list|team-create|team-edit|team-delete']
    ]);

    Route::get('team/create', [
        'as' => 'team.create',
        'uses' => 'TeamController@create',
        //'middleware' => ['permission:team-create']
    ]);

    Route::post('team/create', [
        'as' => 'team.store',
        'uses' => 'TeamController@store',
        //'middleware' => ['permission:team-create']
    ]);

    Route::get('team/{id}/edit', [
        'as' => 'team.edit',
        'uses' => 'TeamController@edit',
        //'middleware' => ['permission:team-edit']
    ]);

    Route::patch('team/{id}', [
        'as' => 'team.update',
        'uses' => 'TeamController@update',
        //'middleware' => ['permission:team-edit']
    ]);

    Route::delete('team/{id}', [
        'as' => 'team.destroy',
        'uses' => 'TeamController@destroy',
        //'middleware' => ['permission:team-delete']
    ]);


    // To Do's Routes start

    Route::get('todos', [
        'as' => 'todos.index',
        'uses' => 'ToDosController@index',
        'middleware' => ['permission:display-todos|display-todos-by-loggedin-user|todo-create|todo-edit|todo-delete']
    ]);

    Route::get('todos/alltodos', [
        'as' => 'todos.alltodos',
        'uses' => 'ToDosController@getAllTodosDetails',
        'middleware' => ['permission:display-todos-by-loggedin-user']
    ]);

    Route::get('todos/complete', [
        'as' => 'todos.completetodo',
        'uses' => 'ToDosController@completetodo',
        'middleware' => ['permission:display-completed-todos']
    ]);

    Route::get('todos/complete/all',[
        'as' => 'todos.completeall',
        'uses' => 'ToDosController@getCompleteTodosDetails',
        'middleware' => ['permission:display-completed-todos']
    ]);

    Route::get('todos/mytask', [
        'as' => 'todos.mytask',
        'uses' => 'ToDosController@mytask',
        'middleware' => ['permission:display-my-todos']
    ]);

    Route::get('todos/my/all', [
        'as' => 'todos.myall',
        'uses' => 'ToDosController@getMyTodosDetails',
        'middleware' => ['permission:display-my-todos']
    ]);

    Route::get('todos/create', [
        'as' => 'todos.create',
        'uses' => 'ToDosController@create',
        'middleware' => ['permission:todo-create']
    ]);

    Route::post('todos/store', [
        'as' => 'todos.store',
        'uses' => 'ToDosController@store',
        'middleware' => ['permission:todo-create']
    ]);

    Route::get('ajax/todotype', [
        'as' => 'todos.getType',
        'uses' => 'ToDosController@getType',
    ]);

    Route::get('todos/getselectedtypelist', [
        'as' => 'todos.getselectedtypelist',
        'uses' => 'ToDosController@getSelectedTypeList',
    ]);

    Route::get('todos/all', [
        'as' => 'todos.list',
        'uses' => 'ToDosController@getAjaxtodo',
    ]);

    Route::get('todos/daily', [
        'as' => 'todos.daily',
        'uses' => 'ToDosController@daily',
        'middleware' => ['permission:display-todos']
    ]);

    Route::get('todos/weekly', [
        'as' => 'todos.weekly',
        'uses' => 'ToDosController@weekly',
        'middleware' => ['permission:display-todos']
    ]);

    Route::get('todos/monthly', [
        'as' => 'todos.monthly',
        'uses' => 'ToDosController@monthly',
        'middleware' => ['permission:display-todos']
    ]);

    Route::get('todos/read', [
        'as' => 'todos.read',
        'uses' => 'ToDosController@readTodos',
        'middleware' => ['permission:display-todos']
    ]);

    Route::get('todos/{id}', [
        'as' => 'todos.show',
        'uses' => 'ToDosController@show',
        'middleware' => ['permission:display-todos-by-loggedin-user']
    ]);

    Route::get('todos/{id}/edit', [
        'as' => 'todos.edit',
        'uses' => 'ToDosController@edit',
        'middleware' => ['permission:todo-edit']
    ]);

    Route::put('todos/{id}', [
        'as' => 'todos.update',
        'uses' => 'ToDosController@update',
        'middleware' => ['permission:todo-edit']
    ]);

    Route::delete('todos/{id}', [
        'as' => 'todos.destroy',
        'uses' => 'ToDosController@destroy',
        'middleware' => ['permission:todo-delete']
    ]);

    Route::post('todos/status', [
        'as' => 'todos.status',
        'uses' => 'ToDosController@status',
    ]);

    Route::post('todos/{id}', [
        'as' => 'todos.complete',
        'uses' => 'ToDosController@complete',
    ]);

    // To do's Routes End

    // Admin > Company

    Route::get('companies', [
        'as' => 'companies.index',
        'uses' => 'CompaniesController@index',
        'middleware' => ['permission:display-companies|companies-add|companies-edit']
    ]);

    Route::get('companies/create', [
        'as' => 'companies.create',
        'uses' => 'CompaniesController@create',
        'middleware' => ['permission:companies-add']
    ]);

    Route::post('companies/create', [
        'as' => 'companies.store',
        'uses' => 'CompaniesController@store',
        'middleware' => ['permission:companies-add']
    ]);

    Route::get('companies/{id}', [
        'as' => 'companies.show',
        'uses' => 'CompaniesController@show'
    ]);

    Route::get('companies/{id}/edit', [
        'as' => 'companies.edit',
        'uses' => 'CompaniesController@edit',
        'middleware' => ['permission:companies-edit']
    ]);

    Route::patch('companies/{id}', [
        'as' => 'companies.update',
        'uses' => 'CompaniesController@update',
        'middleware' => ['permission:companies-edit']
    ]);

    /*Route::delete('companies/{id}', [
        'as' => 'companies.destroy',
        'uses' => 'CompaniesController@destroy'
    ]);*/

    // Notification Related Routes Start

    Route::get('notifications', [
        'as' => 'notification.index',
        'uses' => 'NotificationController@index',
    ]);

    Route::get('notifications/all', [
        'as' => 'notification.list',
        'uses' => 'NotificationController@getAjaxNotification',
    ]);

    Route::get('notifications/read', [
        'as' => 'notification.read',
        'uses' => 'NotificationController@readNotification',
    ]);

    // Notification Related Routes End

    //Email Notification start
    Route::get('sendingmail', [
        'as' => 'email.sendingmail',
        'uses' => 'EmailNotificationController@sendingmail'
    ]);

    //Email Notification stop

    // Admin > Training

    Route::get('training', [
        'as' => 'training.index',
        'uses' => 'TrainingController@index',
        'middleware' => ['permission:display-training-material|display-training-material-added-by-loggedin-user']
    ]);

    Route::get('training/all', [
        'as' => 'training.all',
        'uses' => 'TrainingController@getAllTrainingDetails',
        'middleware' => ['permission:display-training-material|display-training-material-added-by-loggedin-user']
    ]);

    Route::get('training/create', [
        'as' => 'training.create',
        'uses' => 'TrainingController@create',
        'middleware' => ['permission:training-material-add']
    ]);

    Route::post('training/create', [
        'as' => 'training.store',
        'uses' => 'TrainingController@store',
        'middleware' => ['permission:training-material-add']
    ]);
    
    Route::get('training/{id}/edit', [
        'as' => 'training.edit',
        'uses' => 'TrainingController@edit',
        'middleware' => ['permission:training-material-edit']
    ]);

    Route::patch('training/{id}', [
        'as' => 'training.update',
        'uses' => 'TrainingController@update',
        'middleware' => ['permission:training-material-edit']
    ]);

    Route::post('training/upload/{id}', [
        'as' => 'trainingattachments.upload',
        'uses' => 'TrainingController@upload',
        'middleware' => ['permission:training-material-edit']
    ]);

    Route::get('training/{id}/show', [
        'as' => 'training.show',
        'uses' => 'TrainingController@show',
        'middleware' => ['permission:display-training-material-added-by-loggedin-user']
    ]);

    Route::delete('training/{id}', [
        'as' => 'training.destroy',
        'uses' => 'TrainingController@trainingDestroy',
        'middleware' => ['permission:training-material-delete']
    ]);

    Route::delete('training/destroy/{id}', [
        'as' => 'trainingattachments.destroy',
        'uses' => 'TrainingController@attachmentsDestroy',
        'middleware' => ['permission:training-material-delete']
    ]);

    Route::get('training/update-position',[
        'as' => 'training.update-position',
        'uses' => 'TrainingController@UpdatePosition'
    ]);

    // Admin > Process Manual
    
    Route::get('process', [
        'as' => 'process.index',
        'uses' => 'ProcessController@index',
        'middleware' => ['permission:display-process-manual|display-process-manual-added-by-loggedin-user']
    ]);

    Route::get('process/all', [
        'as' => 'process.all',
        'uses' => 'ProcessController@getAllProcessDetails',
        'middleware' => ['permission:display-process-manual|display-process-manual-added-by-loggedin-user']
    ]);
    
    Route::get('process/create', [
        'as' => 'process.create',
        'uses' => 'ProcessController@create',
        'middleware' => ['permission:process-manual-add']
    ]);

    Route::post('process/create', [
        'as' => 'process.store',
        'uses' => 'ProcessController@store',
        'middleware' => ['permission:process-manual-add']
    ]);

    Route::get('process/{id}/edit', [
        'as' => 'process.edit',
        'uses' => 'ProcessController@edit',
        'middleware' => ['permission:process-manual-edit']
    ]);

    Route::patch('process/{id}', [
        'as' => 'process.update',
        'uses' => 'ProcessController@update',
        'middleware' => ['permission:process-manual-edit']
    ]);

    Route::post('process/upload/{id}', [
        'as' => 'processattachments.upload',
        'uses' => 'ProcessController@upload',
        'middleware' => ['permission:process-manual-edit']
    ]);

    Route::get('process/{id}/show', [
        'as' => 'process.show',
        'uses' => 'ProcessController@show',
        'middleware' => ['permission:display-process-manual-added-by-loggedin-user']
    ]);

    Route::delete('process/{id}', [
        'as' => 'process.destroy',
        'uses' => 'ProcessController@processDestroy',
        'middleware' => ['permission:process-manual-delete']
    ]);

    Route::delete('process/destroy/{id}', [
        'as' => 'processattachments.destroy',
        'uses' => 'ProcessController@attachmentsDestroy',
        'middleware' => ['permission:process-manual-delete']
    ]);

    Route::get('process/update-position',[
        'as' => 'process.update-position',
        'uses' => 'ProcessController@UpdatePosition',
        'middleware' => ['permission:display-process-manual']
    ]);

    // Admin > Accounting Heads
    
    Route::get('accounting', [
        'as' => 'accounting.index',
        'uses' => 'AccountingController@index',
        'middleware' => ['permission:display-accounting-heads|accounting-head-add|accounting-head-edit|accounting-head-delete']
    ]);

    Route::get('accounting/create', [
        'as' => 'accounting.create',
        'uses' => 'AccountingController@create',
        'middleware' => ['permission:accounting-head-add']
    ]);

    Route::post('accounting/create', [
        'as' => 'accounting.store',
        'uses' => 'AccountingController@store',
        'middleware' => ['permission:accounting-head-add']
    ]);
    
    Route::get('accounting/{id}/edit', [
        'as' => 'accounting.edit',
        'uses' => 'AccountingController@edit',
        'middleware' => ['permission:accounting-head-edit']
    ]);

    Route::patch('accounting/{id}', [
        'as' => 'accounting.update',
        'uses' => 'AccountingController@update',
        'middleware' => ['permission:accounting-head-edit']
    ]);
    
    Route::delete('accounting/{id}', [
        'as' => 'accounting.destroy',
        'uses' => 'AccountingController@destroy',
        'middleware' => ['permission:accounting-head-delete']
    ]);

    // Expense Route
    Route::get('expense',[
        'as' => 'expense.index',
        'uses' => 'ExpenseController@index',
        'middleware' => ['permission:display-expense|expense-add|expense-edit|expense-delete']
    ]);

    Route::get('expense/all',[
        'as' => 'expense.all',
        'uses' => 'ExpenseController@getAllExpenseDetails',
        'middleware' => ['permission:display-expense|expense-add|expense-edit|expense-delete'] 
    ]);

    Route::get('expense/create',[
        'as' => 'expense.create',
        'uses' => 'ExpenseController@create',
        'middleware' => ['permission:expense-add']
    ]);

    Route::post('expense/create',[
        'as' => 'expense.store',
        'uses' => 'ExpenseController@store',
        'middleware' => ['permission:expense-add']
    ]);

    Route::get('expense/importExport',[ 
        'as' => 'expense.importExport',
        'uses' => 'ExpenseController@importExport',
        'middleware' => ['permission:expense-add']
    ]);

    Route::post('expense/importExcel',[
        'as' => 'expense.importExcel',
        'uses' => 'ExpenseController@importExcel',
        'middleware' => ['permission:expense-add']
    ]);

    Route::get('expense/getvendorinfo', [
        'as' => 'expense.getvendorinfo',
        'uses' => 'ExpenseController@getVendorInfo'
    ]);

    Route::get('expense/{id}', [
        'as' => 'expense.show',
        'uses' => 'ExpenseController@show',
        'middleware' => ['permission:display-expense']
    ]);

    Route::get('expense/{id}/edit', [
        'as' => 'expense.edit',
        'uses' => 'ExpenseController@edit',
        'middleware' => ['permission:expense-edit']
    ]);

    Route::patch('expense/{id}', [
        'as' => 'expense.update',
        'uses' => 'ExpenseController@update',
        'middleware' => ['permission:expense-edit']
    ]);

    Route::delete('expense/{id}', [
        'as' => 'expense.destroy',
        'uses' => 'ExpenseController@destroy',
        'middleware' => ['permission:expense-delete']
    ]);

    Route:: post('expenseattachments/upload/{id}',[
        'as' => 'expenseattachments.upload',
        'uses' => 'ExpenseController@upload'
    ]);

    Route::delete('expense/destroy/{id}',[
        'as' =>'expenseattachments.destroy',
        'uses' =>'ExpenseController@attachmentsDestroy'
    ]);

    // Reports Routes

    Route::get('recoveryreport',[
        'as' => 'recoveryreport.index',
        'uses' => 'RecoveryReportController@index',
        'middleware' => ['permission:display-recovery-report']
    ]);

    Route::post('recoveryreport/export',[
        'as' => 'recoveryreport.export',
        'uses' => 'RecoveryReportController@export',
        'middleware' => ['permission:display-recovery-report']
    ]);

    Route::any('selectionreport',[
        'as' => 'selectionreport.index',
        'uses' => 'SelectionReportController@index',
        'middleware' => ['permission:display-selection-report']
    ]);

    Route::post('selectionreport/export',[
        'as' => 'selectionreport.export',
        'uses' => 'SelectionReportController@export',
        'middleware' => ['permission:display-selection-report']
    ]);

    Route::any('userreport',[
        'as' => 'userreport.index',
        'uses' => 'UserwiseReportController@index',
        'middleware' => ['permission:display-user-report']
    ]);

    Route::post('userreport/export',[
        'as' => 'userreport.export',
        'uses' => 'UserwiseReportController@export',
        'middleware' => ['permission:display-user-report']
    ]);

    Route::any('daily-report',[
        'as' => 'report.dailyreportindex',
        'uses' => 'ReportController@dailyreportIndex',
        'middleware' => ['permission:display-daily-report-of-loggedin-user']
    ]);

    Route::any('weekly-report',[
        'as' => 'report.weeklyreportindex',
        'uses' => 'ReportController@weeklyreportIndex',
        'middleware' => ['permission:display-weekly-report-of-loggedin-user']
    ]);

    Route::any('monthly-report',[
        'as' => 'report.monthlyreportindex',
        'uses' => 'ReportController@monthlyreportIndex',
        'middleware' => ['permission:display-monthly-report-of-loggedin-user']
    ]);

    Route::any('userwise-monthly-report',[
        'as' => 'report.monthlyreportindex',
        'uses' => 'ReportController@userWiseMonthlyReport',
        'middleware' => ['permission:display-monthly-report-of-loggedin-user']
    ]);

    Route::any('personwise-report',[
        'as' => 'report.personwisereportindex',
        'uses' => 'ReportController@personWiseReportIndex',
        'middleware' => ['permission:display-person-wise-report-of-all-users|display-person-wise-report-of-loggedin-user-team']
    ]);

    Route::post('personwise-report/export',[
        'as' => 'report.personwisereportexport',
        'uses' => 'ReportController@personWiseReportExport',
        'middleware' => ['permission:display-person-wise-report-of-all-users|display-person-wise-report-of-loggedin-user-team']
    ]);

    Route::any('monthwise-report',[
        'as' => 'report.monthwisereportindex',
        'uses' => 'ReportController@monthwiseReprotIndex',
        'middleware' => ['permission:display-month-wise-report-of-all-users']
    ]);

    Route::post('monthwise-report/export',[
        'as' => 'report.monthwisereportexport',
        'uses' => 'ReportController@monthWiseReportExport',
        'middleware' => ['permission:display-month-wise-report-of-all-users']
    ]);

    Route::any('eligibility-report',[
        'as' => 'report.eligibilityreportindex',
        'uses' => 'EligibilityReportController@index',
        'middleware' => ['permission:display-eligibility-report-of-all-users']
    ]);

    Route::post('eligibility-report/export',[
        'as' => 'report.eligibilityreportexport',
        'uses' => 'EligibilityReportController@export',
        'middleware' => ['permission:display-eligibility-report-of-all-users']
    ]);

    Route::get('eligibility-report/add',[
        'as' => 'report.eligibilityreportadd',
        'uses' => 'EligibilityReportController@create',
        'middleware' => ['permission:display-eligibility-report-of-all-users']
    ]);

    Route::post('eligibility-report/add',[
        'as' => 'report.eligibilityreportstore',
        'uses' => 'EligibilityReportController@store'
    ]);

    Route::any('clientwise-report',[
        'as' => 'report.clientwisereportindex',
        'uses' => 'ReportController@clientWiseReportIndex',
        'middleware' => ['permission:display-client-wise-report-of-all-users']
    ]);

    Route::post('clientwise-report/export',[
        'as' => 'report.clientwisereportexport',
        'uses' => 'ReportController@clientWiseReportExport',
        'middleware' => ['permission:display-client-wise-report-of-all-users']
    ]);

    Route::any('productivity-report',[
        'as' => 'productivity.report',
        'uses' => 'ReportController@productivityReport',
        'middleware' => ['permission:display-productivity-report-of-loggedin-user']
    ]);

    Route::any('master-productivity-report',[
        'as' => 'master-productivity.report',
        'uses' => 'ReportController@masterProductivityReport',
        'middleware' => ['permission:display-productivity-report-of-all-users']
    ]);

    // Report End

    // Vendors start
    Route::get('vendors', [
        'as' => 'vendor.index',
        'uses' => 'VendorController@index',
        'middleware' => ['permission:display-vendors|vendor-add|vendor-edit|vendor-delete']
    ]);

    Route::get('vendor/create', [
        'as' => 'vendor.create',
        'uses' => 'VendorController@create',
        'middleware' => ['permission:vendor-add']
    ]);

    Route::post('vendor/create', [
        'as' => 'vendor.store',
        'uses' => 'VendorController@store',
        'middleware' => ['permission:vendor-add']
    ]);

    Route::get('vendor/importExport', 
        'VendorController@importExport'
    );

    Route::post('vendor/importExcel', 
        'VendorController@importExcel'
    );

    Route::get('vendor/{id}', [
        'as' => 'vendor.show',
        'uses' => 'VendorController@show',
        'middleware' => ['permission:display-vendors']
    ]);

    Route::get('vendor/{id}/edit', [
        'as' => 'vendor.edit',
        'uses' => 'VendorController@edit',
        'middleware' => ['permission:vendor-edit']
    ]);

    Route::delete('vendor/{id}', [
        'as' => 'vendor.destroy',
        'uses' => 'VendorController@destroy',
        'middleware' => ['permission:vendor-delete']
    ]);

    Route::patch('vendor/{id}', [
        'as' => 'vendor.update',
        'uses' => 'VendorController@update',
        'middleware' => ['permission:vendor-edit']
    ]);

    Route:: post('vendorattachments/upload/{id}',[
        'as' => 'vendorattachments.upload',
        'uses' => 'VendorController@upload'
    ]);

    Route::delete('vendor/destroy/{id}',[
        'as' =>'vendorattachments.destroy',
        'uses' =>'VendorController@attachmentsDestroy'
    ]);

    Route::get('test/email', [
        'as' => 'user.testemail',
        'uses' => 'UserController@testEmail',
    ]);

    // Admin > Holidays
    Route::get('holidays', [
        'as' => 'holidays.index',
        'uses' => 'HolidaysController@index',
        'middleware' => ['permission:display-holidays|holiday-add|holiday-edit|holiday-delete']
    ]);

    Route::get('holidays/create', [
        'as' => 'holidays.create',
        'uses' => 'HolidaysController@create',
        'middleware' => ['permission:holiday-add']
    ]);

    Route::post('holidays/create', [
        'as' => 'holidays.store',
        'uses' => 'HolidaysController@store',
        'middleware' => ['permission:holiday-add']
    ]);

    Route::get('holidays/edit/{id}', [
        'as' => 'holidays.edit',
        'uses' => 'HolidaysController@edit',
        'middleware' => ['permission:holiday-edit']
    ]);

    Route::patch('holidays/{id}', [
        'as' => 'holidays.update',
        'uses' => 'HolidaysController@update',
        'middleware' => ['permission:holiday-edit']
    ]);

    Route::delete('holidays/{id}', [
        'as' => 'holidays.destroy',
        'uses' => 'HolidaysController@destroy',
        'middleware' => ['permission:holiday-delete']
    ]);

    // Receipt module

    //Talent
    Route::any('receipt/talent',[
        'as' => 'receipt.talent',
        'uses' => 'ReceiptController@receiptTalent',
        //'middleware' => ['permission:receipt-talent']
    ]);

    Route::get('receipt/talent/import',[
        'as' => 'receipt.talentimport',
        'uses' => 'ReceiptController@receiptTalentImport',
        //'middleware' => ['permission:receipt-talent']
    ]);

    Route::post('receipt/talent/import',[
        'as' => 'receipt.talentimportstore',
        'uses' => 'ReceiptController@receiptTalentImportStore',
        //'middleware' => ['permission:receipt-talent']
    ]);

    Route::get('receipt/talent/create',[
        'as' => 'receipt.talentcreate',
        'uses' => 'ReceiptController@receiptTalentCreate',
        //'middleware' => ['permission:receipt-talent']
    ]);

    Route::post('receipt/talent/store',[
        'as' => 'receipt.talentstore',
        'uses' => 'ReceiptController@receiptTalentStore',
        //'middleware' => ['permission:receipt-talent']
    ]);

    //Temp

    Route::any('receipt/temp',[
        'as' => 'receipt.temp',
        'uses' => 'ReceiptController@receiptTemp',
        //'middleware' => ['permission:receipt-temp']
    ]);

    Route::get('receipt/temp/import',[
        'as' => 'receipt.tempimport',
        'uses' => 'ReceiptController@receiptTempImport',
        //'middleware' => ['permission:receipt-temp']
    ]);

    Route::post('receipt/temp/import',[
        'as' => 'receipt.tempimportstore',
        'uses' => 'ReceiptController@receiptTempImportStore',
        //'middleware' => ['permission:receipt-temp']
    ]);

    Route::get('receipt/temp/create',[
        'as' => 'receipt.tempcreate',
        'uses' => 'ReceiptController@receiptTempCreate',
        //'middleware' => ['permission:receipt-temp']
    ]);

    Route::post('receipt/temp/store',[
        'as' => 'receipt.tempstore',
        'uses' => 'ReceiptController@receiptTempStore',
        //'middleware' => ['permission:receipt-temp']
    ]);

    //Other

    Route::any('receipt/other',[
        'as' => 'receipt.other',
        'uses' => 'ReceiptController@receiptOther',
        //'middleware' => ['permission:receipt-temp']
    ]);

    Route::get('receipt/other/import',[
        'as' => 'receipt.otherimport',
        'uses' => 'ReceiptController@receiptOtherImport',
        //'middleware' => ['permission:receipt-temp']
    ]);

    Route::post('receipt/other/import',[
        'as' => 'receipt.otherimportstore',
        'uses' => 'ReceiptController@receiptOtherImportStore',
        //'middleware' => ['permission:receipt-temp']
    ]);

    Route::get('receipt/other/create',[
        'as' => 'receipt.othercreate',
        'uses' => 'ReceiptController@receiptOtherCreate',
        //'middleware' => ['permission:receipt-temp']
    ]);

    Route::post('receipt/other/store',[
        'as' => 'receipt.otherstore',
        'uses' => 'ReceiptController@receiptOtherStore',
        //'middleware' => ['permission:receipt-temp']
    ]);

    Route::get('receipt/edit/{id}',[
        'as' => 'receipt.edit',
        'uses' => 'ReceiptController@edit',
        //'middleware' => ['permission:receipt-temp|receipt-talent|receipt-others']
    ]);

    Route::patch('receipt/{id}',[
        'as' => 'receipt.update',
        'uses' => 'ReceiptController@update',
        //'middleware' => ['permission:receipt-temp|receipt-talent|receipt-others']
    ]);

    Route::delete('receipt/{id}',[
        'as' => 'receipt.destroy',
        'uses' => 'ReceiptController@ReceiptDestroy',
        //'middleware' => ['permission:receipt-temp|receipt-talent|receipt-others']
    ]);

    // Module Route
    Route::get('module',[
        'as' => 'module.index',
        'uses' => 'ModuleController@index',
        'middleware' => ['permission:display-modules|module-add|module-edit|module-delete|']
    ]);

    Route::get('module/create',[
        'as' => 'module.create',
        'uses' => 'ModuleController@create',
        'middleware' => ['permission:module-add']
    ]);

    Route::post('module/create',[
        'as' => 'module.store',
        'uses' => 'ModuleController@store',
        'middleware' => ['permission:module-add']
    ]);

    Route::get('module/edit/{id}',[
        'as' => 'module.edit',
        'uses' => 'ModuleController@edit',
        'middleware' => ['permission:module-edit']
    ]);

    Route::patch('module/edit/{id}',[
        'as' => 'module.update',
        'uses' => 'ModuleController@update',
        'middleware' => ['permission:module-edit']
    ]);

    Route::delete('module/{d}',[
        'as' => 'module.destroy',
        'uses' => 'ModuleController@destroy',
        'middleware' => ['permission:module-delete']
    ]);

    // Module Visible Users route
    Route::get('modulevisible',[
        'as' => 'modulevisible.index',
        'uses' => 'ModuleVisibleController@index',
        'middleware' => ['permission:display-module-visibilities|module-visibility-add|module-visibility-edit|module-visibility-delete']
    ]);

    Route::get('modulevisible/create',[
        'as' => 'modulevisible.create',
        'uses' => 'ModuleVisibleController@create',
        'middleware' => ['permission:module-visibility-add']
    ]);

    Route::post('modulevisible/create',[
        'as' => 'modulevisible.store',
        'uses' => 'ModuleVisibleController@store',
        'middleware' => ['permission:module-visibility-add']
    ]);

    Route::get('modulevisible/{id}/edit',[
        'as' => 'modulevisible.edit',
        'uses' => 'ModuleVisibleController@edit',
        'middleware' => ['permission:module-visibility-edit']
    ]);

    Route::patch('modulevisible/{id}/edit',[
        'as' => 'modulevisible.update',
        'uses' => 'ModuleVisibleController@update',
        'middleware' => ['permission:module-visibility-edit']
    ]);

    Route::delete('modulevisible/{id}',[
        'as' => 'modulevisible.destroy',
        'uses' => 'ModuleVisibleController@destroy',
        'middleware' => ['permission:module-visibility-delete']
    ]);

    Route::post('/usermodule/visible',[
        'as' => 'usermodule.visible',
        'uses' => 'ModuleVisibleController@userWiseModuleAjax'
    ]);

    // Customer Support routes

    Route::get('customer-support',[
        'as' => 'customer.index',
        'uses' => 'CustomerSupportController@index'
    ]);

    Route::get('customer-support/create',[
        'as' => 'customer.create',
        'uses' => 'CustomerSupportController@create'
    ]);

    Route::post('customer-support/create',[
        'as' => 'customer.store',
        'uses' => 'CustomerSupportController@store'
    ]);

    Route::get('customer-support/{id}',[
        'as'=>'customer.show',
        'uses'=>'CustomerSupportController@show'
    ]);

    Route::get('customer-support/{id}/edit',[
        'as' => 'customer.edit',
        'uses' => 'CustomerSupportController@edit'
    ]);

    Route::patch('customer-support/{id}',[
        'as' => 'customer.update',
        'uses' => 'CustomerSupportController@update'
    ]);

    Route::post('customer-support/upload/{id}', [
        'as' => 'customerattachments.upload',
        'uses' => 'CustomerSupportController@upload'
    ]);
    
    Route::delete('customer-support/destroy/{id}', [
        'as' => 'customerattachments.destroy',
        'uses' => 'CustomerSupportController@attachmentsDestroy'
    ]);

    Route::delete('customer-support/{id}',[
        'as' => 'customer.destroy',
        'uses' => 'CustomerSupportController@destroy'
    ]);

    // Client Heirarchy Route
    Route::get('client-hierarchy',[
        'as' => 'clientheirarchy.index',
        'uses' => 'ClientHeirarchyController@index',
        'middleware' => ['permission:display-client-hierarchy|client-hierarchy-add|client-hierarchy-edit|client-hierarchy-delete']
    ]);

    Route::get('client-hierarchy/create',[
        'as' => 'clientheirarchy.create',
        'uses' => 'ClientHeirarchyController@create',
        'middleware' => ['permission:client-hierarchy-add']
    ]);

    Route::post('client-hierarchy/create',[
        'as' => 'clientheirarchy.store',
        'uses' => 'ClientHeirarchyController@store',
        'middleware' => ['permission:client-hierarchy-add']
    ]);

    Route::get('client-hierarchy/edit/{id}',[
        'as' => 'clientheirarchy.edit',
        'uses' => 'ClientHeirarchyController@edit',
        'middleware' => ['permission:client-hierarchy-edit']
    ]);

    Route::patch('client-hierarchy/edit/{id}',[
        'as' => 'clientheirarchy.update',
        'uses' => 'ClientHeirarchyController@update',
        'middleware' => ['permission:client-hierarchy-edit']
    ]);

    Route::delete('client-hierarchy/{id}',[
        'as' => 'clientheirarchy.destroy',
        'uses' => 'ClientHeirarchyController@destroy',
        'middleware' => ['permission:client-hierarchy-delete']
    ]);

    Route::get('client-hierarchy/update-position',[
        'as' => 'clientheirarchy.update-position',
        'uses' => 'ClientHeirarchyController@UpdatePosition',
        'middleware' => ['permission:display-client-hierarchy']
    ]);

    // Client Remarks Route
    Route::get('client-remarks',[
        'as' => 'clientremarks.index',
        'uses' => 'ClientRemarksController@index',
        'middleware' => ['permission:display-client-remarks|client-remarks-add|client-remarks-edit|client-remarks-delete']
    ]);

    Route::get('/search-remarks',[
        'as' => 'search.remarks',
        'uses' => 'ClientRemarksController@searchRemarks'
    ]);

    Route::get('client-remarks/create',[
        'as' => 'clientremarks.create',
        'uses' => 'ClientRemarksController@create',
        'middleware' => ['permission:client-remarks-add']
    ]);

    Route::post('client-remarks/create',[
        'as' => 'clientremarks.store',
        'uses' => 'ClientRemarksController@store',
        'middleware' => ['permission:client-remarks-add']
    ]);

    Route::get('client-remarks/edit/{id}',[
        'as' => 'clientremarks.edit',
        'uses' => 'ClientRemarksController@edit',
        'middleware' => ['permission:client-remarks-edit']
    ]);

    Route::patch('client-remarks/edit/{id}',[
        'as' => 'clientremarks.update',
        'uses' => 'ClientRemarksController@update',
        'middleware' => ['permission:client-remarks-edit']
    ]);

    Route::delete('client-remarks/{id}',[
        'as' => 'clientremarks.destroy',
        'uses' => 'ClientRemarksController@destroy',
        'middleware' => ['permission:client-remarks-delete']
    ]);

    // Email Template Routes
    Route::get('email-template',[
        'as' => 'emailtemplate.index',
        'uses' => 'EmailTemplateController@index',
        'middleware' => ['permission:display-email-template|email-template-add|email-template-edit|email-template-delete']
    ]);

    Route::get('email-template/create',[
        'as' => 'emailtemplate.create',
        'uses' => 'EmailTemplateController@create',
        'middleware' => ['permission:email-template-add']
    ]);

    Route::post('email-template/store',[
        'as' => 'new-email-template.store',
        'uses' => 'EmailTemplateController@storeNewEmailTemplate',
    ]);

    Route::post('email-template/create',[
        'as' => 'emailtemplate.store',
        'uses' => 'EmailTemplateController@store',
        'middleware' => ['permission:email-template-add']
    ]);

    Route::get('email-template/getDetailsById',[
        'as' => 'emailtemplate.getDetailsById',
        'uses' => 'EmailTemplateController@getEmailTemplateById'
    ]);

    Route::any('/email-body-image',[
        'as' => 'emailbody.image',
        'uses' => 'EmailTemplateController@uploadEmailbodyImage'
    ]);

    Route::get('email-template/edit/{id}',[
        'as' => 'emailtemplate.edit',
        'uses' => 'EmailTemplateController@edit',
        'middleware' => ['permission:email-template-edit']
    ]);

    Route::patch('email-template/edit/{id}',[
        'as' => 'emailtemplate.update',
        'uses' => 'EmailTemplateController@update',
        'middleware' => ['permission:email-template-edit']
    ]);

    Route::get('email-template/{id}/show', [
        'as' => 'emailtemplate.show',
        'uses' => 'EmailTemplateController@show',
        'middleware' => ['permission:display-email-template']
    ]);

    Route::delete('email-template/{id}',[
        'as' => 'emailtemplate.destroy',
        'uses' => 'EmailTemplateController@destroy',
        'middleware' => ['permission:email-template-delete']
    ]);

    Route::get('rolewise-permissions', [
        'as' => 'rolewise.permissions',
        'uses' => 'NewRoleController@rolewisePermissions'
    ]);

    Route::post('rolewise-permissions/add',[
        'as' => 'rolewise-permissions.add',
        'uses' => 'NewRoleController@addRolePermissions',
    ]);

    // Admin > New User Permissions
    //Route::group(['middleware' => ['permission:permission-list']], function () {
    Route::group([], function () {
        Route::resource('user-permissions', 'NewPermissionsController', [
            'names' => [
                'index' => 'userpermission.index',
                'create' => 'userpermission.create',
                'store' => 'userpermission.store',
                'update' => 'userpermission.update',
                'edit' => 'userpermission.edit',
                'destroy' => 'userpermission.destroy',
            ],
        ]);
    });

    // Admin > New User Roles

    Route::get('getPermissions', [
        'as' => 'get.permissions',
        'uses' => 'NewRoleController@getPermissions',
    ]);

    Route::get('getPermissionsByRoleID', [
        'as' => 'getpermissions.byroleid',
        'uses' => 'NewRoleController@getPermissionsByRoleID',
    ]);

    Route::get('user-role', [
        'as' => 'userrole.index',
        'uses' => 'NewRoleController@index',
        'middleware' => ['permission:display-roles|role-add|role-edit|role-delete']
    ]);

    Route::get('user-role/create', [
        'as' => 'userrole.create',
        'uses' => 'NewRoleController@create',
        'middleware' => ['permission:role-add']
    ]);

    Route::post('user-role/create', [
        'as' => 'userrole.store',
        'uses' => 'NewRoleController@store',
        'middleware' => ['permission:role-add']
    ]);

    Route::get('user-role/{id}', [
        'as' => 'userrole.show',
        'uses' => 'NewRoleController@show'
    ]);

    Route::get('user-role/{id}/edit', [
        'as' => 'userrole.edit',
        'uses' => 'NewRoleController@edit',
        'middleware' => ['permission:role-edit']
    ]);

    Route::patch('user-role/{id}', [
        'as' => 'userrole.update',
        'uses' => 'NewRoleController@update',
        'middleware' => ['permission:role-edit']
    ]);

    Route::delete('user-role/{id}', [
        'as' => 'userrole.destroy',
        'uses' => 'NewRoleController@destroy',
        'middleware' => ['permission:role-delete']
    ]);

    Route::post('/userrolewise/modulevisible',[
        'as' => 'userrolewise.modulevisible',
        'uses' => 'NewRoleController@userWiseModuleAjax'
    ]);
    // User Bench Mark Routes

    Route::get('user-bench-mark',[
        'as' => 'userbenchmark.index',
        'uses' => 'UserBenchMarkController@index',
        'middleware' => ['permission:display-user-benchmark|user-benchmark-add|user-benchmark-edit|user-benchmark-delete']
    ]);

    Route::get('user-bench-mark/add',[
        'as' => 'userbenchmark.create',
        'uses' => 'UserBenchMarkController@create',
        'middleware' => ['permission:user-benchmark-add']
    ]);

    Route::post('user-bench-mark/add',[
        'as' => 'userbenchmark.store',
        'uses' => 'UserBenchMarkController@store',
        'middleware' => ['permission:user-benchmark-add']
    ]);

    Route::get('user-bench-mark/edit/{id}',[
        'as' => 'userbenchmark.edit',
        'uses' => 'UserBenchMarkController@edit',
        'middleware' => ['permission:user-benchmark-edit']
    ]);

    Route::patch('user-bench-mark/edit/{id}',[
        'as' => 'userbenchmark.update',
        'uses' => 'UserBenchMarkController@update',
        'middleware' => ['permission:user-benchmark-edit']
    ]);

    Route::delete('user-bench-mark/{id}',[
        'as' => 'userbenchmark.destroy',
        'uses' => 'UserBenchMarkController@destroy',
        'middleware' => ['permission:user-benchmark-delete']
    ]);

    // User Bench Mark Routes End
});

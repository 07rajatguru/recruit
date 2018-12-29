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

Route::get('/index', [
    'uses' => 'IndexController@index'
]);

Route::post('/contactus', [
     'as' => 'contact.us',
     'uses' => 'IndexController@sendMail'
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
Route::get('candidate/createform',[
    'as'=>'candidate.createf',
    'uses'=>'CandidateCreateFormController@createf'
]);

//Store Form
Route::post('candidate/createform',[
    'as'=>'candidate.storef',
    'uses'=>'CandidateCreateFormController@storef'
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
        'middleware' => 'auth',
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
        'middleware' => 'auth',
        'uses' => 'HomeController@index'
    ));

    Route::get('/userattendance', array (
        'middleware' => 'auth',
        'uses' => 'HomeController@userAttendance'
    ));

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
        'uses'=>'LeadController@create']);

    Route::get('lead',[
        'as'=>'lead.index',
        'uses'=>'LeadController@index']);

    Route::get('lead/all',[
        'as' => 'lead.all',
        'uses' => 'LeadController@getAllLeadsDetails'
    ]);

    Route::get('lead/cancel', [
        'as' => 'lead.leadcancel',
        'uses' => 'LeadController@cancellead'
    ]);
    
   Route::post('lead/store', [
        'as' => 'lead.store',
        'uses' => 'LeadController@store'
    ]);
     
    Route::get('lead/{id}/edit', [
        'as' => 'lead.edit',
        'uses' => 'LeadController@edit'
    ]);

    Route::put('lead/{id}', [
        'as' => 'lead.update',
        'uses' => 'LeadController@update'
    ]);

    Route::delete('lead/{id}', [
        'as' => 'lead.destroy',
        'uses' => 'LeadController@destroy',
    ]);

    Route::get('lead/{id}/clone', [
        'as' => 'lead.clone',
        'uses' => 'LeadController@leadClone',
    ]);
    
    Route::post('lead/{id}', [
        'as' => 'lead.clonestore',
        'uses' => 'LeadController@clonestore',
    ]); 

    Route::get('lead/{id}', [
        'as' => 'lead.cancel',
        'uses' => 'LeadController@cancel'
    ]);


    //User Profile
    
    Route::get('users/editprofile/{id}',[
        'as' => 'users.editprofile',
        'uses' => 'UserController@editProfile'
    ]);
    Route::post('users/profilestore/{id}',[
        'as' => 'users.profilestore',
        'uses' => 'UserController@profileStore'
    ]);
    Route::get('users/myprofile/{id}',[
        'as' => 'users.myprofile',
        'uses' => 'UserController@profileShow'
    ]);
    Route::post('usersattachments/upload/{id}',[
        'as' => 'usersattachments.upload',
        'uses' => 'UserController@Upload'
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
        'middleware' => ['permission:user-list|user-create|user-edit|user-delete']
    ]);
/*
    Route::post('users/upload/{id}',[
        'as' => 'users.upload',
        'uses' => 'UserController@Upload'
    ]);*/
    Route::get('users/attendance',[
        'as' => 'users.attendance',
        'uses' => 'UserController@UserAttendanceAdd',
        'middleware' => ['permission:user-create']
    ]);

    Route::post('users/attendance',[
        'as' => 'users.attendancestore',
        'uses' => 'UserController@UserAttendanceStore'
    ]);
    
    Route::get('users/create', [
        'as' => 'users.create',
        'uses' => 'UserController@create',
        'middleware' => ['permission:user-create']
    ]);
    Route::post('users/create', [
        'as' => 'users.store',
        'uses' => 'UserController@store',
        'middleware' => ['permission:user-create']
    ]);
    Route::get('users/{id}', [
        'as' => 'users.show',
        'uses' => 'UserController@show'
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


    // Admin > Roles
    Route::get('roles', [
        'as' => 'roles.index',
        'uses' => 'RoleController@index',
        'middleware' => ['permission:role-list|role-create|role-edit|role-delete']
    ]);
    Route::get('roles/create', [
        'as' => 'roles.create',
        'uses' => 'RoleController@create',
        'middleware' => ['permission:role-create']
    ]);
    Route::post('roles/create', [
        'as' => 'roles.store',
        'uses' => 'RoleController@store',
        'middleware' => ['permission:role-create']
    ]);
    Route::get('roles/{id}', [
        'as' => 'roles.show',
        'uses' => 'RoleController@show'
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
       // 'middleware' => ['permission:industry-list|industry-create|industry-edit|industry-delete']
    ]);
    Route::get('industry/create', [
            'as' => 'industry.create',
            'uses' => 'IndustryController@create',
           // 'middleware' => ['permission:industry-create']
     ]
    );
    Route::post('industry/create', [
        'as' => 'industry.store',
        'uses' => 'IndustryController@store',
        //'middleware' => ['permission:industry-create']
    ]);
    Route::get('industry/{id}', [
        'as' => 'industry.show',
        'uses' => 'IndustryController@show'
    ]);
    Route::get('industry/{id}/edit', [
        'as' => 'industry.edit',
        'uses' => 'IndustryController@edit',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::patch('industry/{id}', [
        'as' => 'industry.update',
        'uses' => 'IndustryController@update',
       // 'middleware' => ['permission:industry-edit']
    ]);
    Route::delete('industry/{id}', [
        'as' => 'industry.destroy',
        'uses' => 'IndustryController@destroy',
       // 'middleware' => ['permission:industry-delete']
    ]);

    // Admin > Permissions
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
        ]
    ]);

    // Admin > Candidate Source
    Route::get('candidateSource', [
        'as' => 'candidateSource.index',
        'uses' => 'CandidateSourceController@index',
         'middleware' => ['permission:candidatesource-list|candidatesource-create|candidatesource-edit|candidatesource-delete']
    ]);
    Route::get('candidateSource/create', [
            'as' => 'candidateSource.create',
            'uses' => 'CandidateSourceController@create',
            'middleware' => ['permission:candidatesource-create']
        ]
    );
    Route::post('candidateSource/create', [
        'as' => 'candidateSource.store',
        'uses' => 'CandidateSourceController@store',
        'middleware' => ['permission:candidatesource-create']
    ]);
    Route::get('candidateSource/{id}', [
        'as' => 'candidateSource.show',
        'uses' => 'CandidateSourceController@show'
    ]);
    Route::get('candidateSource/{id}/edit', [
        'as' => 'candidateSource.edit',
        'uses' => 'CandidateSourceController@edit',
        'middleware' => ['permission:candidatesource-edit']
    ]);
    Route::patch('candidateSource/{id}', [
        'as' => 'candidateSource.update',
        'uses' => 'CandidateSourceController@update',
        'middleware' => ['permission:candidatesource-edit']
    ]);
    Route::delete('candidateSource/{id}', [
        'as' => 'candidateSource.destroy',
        'uses' => 'CandidateSourceController@destroy',
        'middleware' => ['permission:candidatesource-delete']
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
        'middleware' => ['permission:candidatestatus-list|candidatestatus-create|candidatestatus-edit|candidatestatus-delete']
    ]);
    Route::get('candidateStatus/create', [
            'as' => 'candidateStatus.create',
            'uses' => 'candidateStatusController@create',
            'middleware' => ['permission:candidatestatus-create']
        ]
    );
    Route::post('candidateStatus/create', [
        'as' => 'candidateStatus.store',
        'uses' => 'candidateStatusController@store',
        'middleware' => ['permission:candidatestatus-create']
    ]);
    Route::get('candidateStatus/{id}', [
        'as' => 'candidateStatus.show',
        'uses' => 'candidateStatusController@show'
    ]);
    Route::get('candidateStatus/{id}/edit', [
        'as' => 'candidateStatus.edit',
        'uses' => 'candidateStatusController@edit',
        'middleware' => ['permission:candidatestatus-edit']
    ]);
    Route::patch('candidateStatus/{id}', [
        'as' => 'candidateStatus.update',
        'uses' => 'candidateStatusController@update',
        'middleware' => ['permission:candidatestatus-edit']
    ]);
    Route::delete('candidateStatus/{id}', [
        'as' => 'candidateStatus.destroy',
        'uses' => 'candidateStatusController@destroy',
        'middleware' => ['permission:candidatestatus-delete']
    ]);

    // Client
    Route::get('client/create', [
        'as' => 'client.create',
        'uses' => 'ClientController@create',
        // 'middleware' => ['permission:industry-create']
    ]);

    Route::get('client/clientemail', [
        'as' => 'client.clientemail',
        'uses' => 'ClientController@postClientNames',
        //'middleware' => ['permission:industry-edit']
    ]);

    Route::post('client/emailnotification', [
        'as' => 'client.emailnotification',
        'uses' => 'ClientController@postClientEmails',
        //'middleware' => ['permission:industry-edit']
    ]);
     
    Route::get('client', [
        'as' => 'client.index',
        'uses' => 'ClientController@index',
        //'middleware' => ['permission:industry-list|industry-create|industry-edit|industry-delete']
    ]);

    Route::get('client/active',[
        'as' => 'client.active',
        'uses' => 'ClientController@ActiveClient',
    ]);

    Route::get('client/passive',[
        'as' => 'client.passive',
        'uses' => 'ClientController@PassiveClient'
    ]);

    Route::get('client/paramount',[
        'as' => 'client.paramount',
        'uses' => 'ClientController@ParamountClient',
    ]);

    Route::get('client/moderate',[
        'as' => 'client.moderate',
        'uses' => 'ClientController@ModerateClient',
    ]);

    Route::get('client/standard',[
        'as' => 'client.standard',
        'uses' => 'ClientController@StandardClient',
    ]);

    Route::get('client/all', [
        'as' => 'client.all',
        'uses' => 'ClientController@getAllClientsDetails',
        //'middleware' => ['permission:industry-list|industry-create|industry-edit|industry-delete']
    ]);

    Route::get('monthwiseclient', [
        'as' => 'monthwiseclient.index',
        'uses' => 'ClientController@getMonthWiseClient'
    ]);

    Route::post('client/create', [
        'as' => 'client.store',
        'uses' => 'ClientController@store',
        //'middleware' => ['permission:industry-create']
    ]);

    Route::get('client/importExport', 'ClientController@importExport');
    Route::post('client/importExcel', 'ClientController@importExcel');

    Route::get('client/{id}', [
        'as' => 'client.show',
        'uses' => 'ClientController@show'
    ]);
    Route::get('client/{id}/edit', [
        'as' => 'client.edit',
        'uses' => 'ClientController@edit',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::delete('client/{id}', [
        'as' => 'client.destroy',
        'uses' => 'ClientController@destroy',
        //    'middleware' => ['permission:industry-delete']
    ]);
    Route::patch('client/{id}', [
        'as' => 'client.update',
        'uses' => 'ClientController@update',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::delete('client/destroy/{id}', [
        'as' => 'clientattachments.destroy',
        'uses' => 'ClientController@attachmentsDestroy',
        //    'middleware' => ['permission:industry-delete']
    ]);
    Route::post('clientattachments/upload/{id}', [
        'as' => 'clientattachments.upload',
        'uses' => 'ClientController@upload',
        //    'middleware' => ['permission:industry-delete']
    ]);
    Route::delete('client/{id}', [
        'as' => 'client.destroy',
        'uses' => 'ClientController@delete',
        //    'middleware' => ['permission:industry-delete']
    ]);
    
    Route::post('client/account_manager',[
        'as' => 'client.account_manager',
        'uses' => 'ClientController@getAccountManager',
    ]);

    // Candidate
    Route::get('candidate', [
        'as' => 'candidate.index',
        'uses' => 'CandidateController@index'
    ]);

    Route::get('candidate/all', [
        'as' => 'candidate.all',
        'uses' => 'CandidateController@getAllCandidates'
    ]);

    Route::get('candidate/create', [
        'as' => 'candidate.create',
        'uses' => 'CandidateController@create'
    ]);

    Route::post('candidate', [
        'as' => 'candidate.store',
        'uses' => 'CandidateController@store'
    ]);

    Route::get('candidate/{id}/edit', [
        'as' => 'candidate.edit',
        'uses' => 'CandidateController@edit'
    ]);

    Route::put('candidate/{id}', [
        'as' => 'candidate.update',
        'uses' => 'CandidateController@update'
    ]);

    Route::delete('candidate/{id}', [
        'as' => 'candidate.destroy',
        'uses' => 'CandidateController@destroy'
    ]);

    Route::get('candidate/{id}/show', [
        'as' => 'candidate.show',
        'uses' => 'CandidateController@show'
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
    Route::get('candidatejoin', [
        'as' => 'candidatejoin.index',
        'uses' => 'CandidateController@candidatejoin'
    ]);

    Route::get('candidate/importExport', 'CandidateController@importExport');
    Route::post('candidate/importExcel', 'CandidateController@importExcel');
    Route::get('candidate/fullname', 'CandidateController@fullname');
    Route::get('candidatejoin/salary', 'CandidateController@candidatesalary');

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
    Route::get('jobs/create', [
        'as' => 'jobopen.create',
        'uses' => 'JobOpenController@create',
        // 'middleware' => ['permission:industry-create']
    ]);

    Route::get('jobs/clone/{id}', [
        'as' => 'jobopen.clone',
        'uses' => 'JobOpenController@jobClone',
    ]);


    Route::get('jobs/getopenjobs', [
        'as' => 'jobopen.getOpenJobs',
        'uses' => 'JobOpenController@getOpenJobs',
        // 'middleware' => ['permission:industry-create']
    ]);
    Route::get('jobs/importExport', 'JobOpenController@importExport');
    Route::post('jobs/importExcel', 'JobOpenController@importExcel');
    Route::get('jobs/salary', 'JobOpenController@salary');
    Route::get('jobs/work', 'JobOpenController@work');
    Route::get('jobs/opentoalldate', 'JobOpenController@openToAllDate');
    Route::get('jobs', [
        'as' => 'jobopen.index',
        'uses' => 'JobOpenController@index',
        //'middleware' => ['permission:industry-list|industry-create|industry-edit|industry-delete']
    ]);
    Route::get('jobs/all', [
        'as' => 'jobopen.all',
        'uses' => 'JobOpenController@getAllJobsDetails',
        //'middleware' => ['permission:industry-list|industry-create|industry-edit|industry-delete']
    ]);
    Route::post('jobs', [
        'as' => 'jobopen.index',
        'uses' => 'JobOpenController@index',
        //'middleware' => ['permission:industry-list|industry-create|industry-edit|industry-delete']
    ]); 

    Route::get('jobs/opentoall', [
        'as' => 'jobopen.toall',
        'uses' => 'JobOpenController@OpentoAll'
    ]);

    Route::get('jobs/priority/{priority}',[
        'as' => 'jobopen.priority',
        'uses' => 'JobOpenController@priorityWise'
    ]);

    Route::post('jobs/create', [
        'as' => 'jobopen.store',
        'uses' => 'JobOpenController@store',
        //'middleware' => ['permission:industry-create']
    ]);

    Route::post('jobs/clone', [
        'as' => 'jobopen.clonestore',
        'uses' => 'JobOpenController@clonestore',
    ]); 
    
    Route::get('jobs/{id}', [
        'as' => 'jobopen.show',
        'uses' => 'JobOpenController@show'
    ]);
    Route::get('jobs/{id}/edit', [
        'as' => 'jobopen.edit',
        'uses' => 'JobOpenController@edit',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::delete('jobs/{id}', [
        'as' => 'jobopen.destroy',
        'uses' => 'JobOpenController@destroy',
        //    'middleware' => ['permission:industry-delete']
    ]);
    Route::patch('jobs/{id}', [
        'as' => 'jobopen.update',
        'uses' => 'JobOpenController@update',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::post('jobs/upload/{id}', [
        'as' => 'jobopen.upload',
        'uses' => 'JobOpenController@upload',
        //    'middleware' => ['permission:industry-delete']
    ]);
    Route::delete('jobs/destroy/{id}', [
        'as' => 'jobopenattachments.destroy',
        'uses' => 'JobOpenController@attachmentsDestroy',
        //    'middleware' => ['permission:industry-delete']
    ]);
    Route::get('jobs/{id}/associate_candidate', [
        'as' => 'jobopen.associate_candidate_get',
        'uses' => 'JobOpenController@associateCandidate',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::post('jobs/associate_candidate', [
        'as' => 'jobopen.associate_candidate',
        'uses' => 'JobOpenController@postAssociateCandidates',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::post('jobs/associated_candidates_count', [
        'as' => 'jobopen.associated_candidates_count',
        'uses' => 'JobOpenController@associateCandidateCount',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::get('jobs/{id}/associated_candidates', [
        'as' => 'jobopen.associated_candidates_get',
        'uses' => 'JobOpenController@associatedCandidates',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::post('jobs/deassociate_candidate', [
        'as' => 'jobopen.deassociate_candidate',
        'uses' => 'JobOpenController@deAssociateCandidates',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::post('jobs/updatecandidatestatus', [
        'as' => 'jobopen.updatecandidatestatus',
        'uses' => 'JobOpenController@updateCandidateStatus',
        //'middleware' => ['permission:industry-edit']
    ]);
    Route::post('jobs/scheduleinterview', [
        'as' => 'jobopen.scheduleinterview',
        'uses' => 'JobOpenController@scheduleInterview',
        //'middleware' => ['permission:industry-create']
    ]);
    Route::post('jobs/addjoiningdate', [
        'as' => 'jobopen.addjoiningdate',
        'uses' => 'JobOpenController@addJoiningDate',
        //'middleware' => ['permission:industry-create']
    ]);
    Route::post('jobs/moreoptions', [
        'as' => 'jobopen.moreoptions',
        'uses' => 'JobOpenController@moreOptions',
        //'middleware' => ['permission:industry-create']
    ]);
    Route::post('jobs/status', [
        'as' => 'jobopen.status',
        'uses' => 'JobOpenController@status',
        //'middleware' => ['permission:industry-create']
    ]);

    // Route for changes priority of multiple job
    Route::post('jobs/checkJobId',[
        'as' => 'jobopen.checkjobid',
        'uses' => 'JobOpenController@checkJobId'
    ]);

    Route::post('jobs/mutijobpriority', [
        'as' => 'jobopen.mutijobpriority',
        'uses' => 'JobOpenController@MultipleJobPriority',
    ]);

    Route::get('job/close', [
        'as' => 'jobopen.close',
        'uses' => 'JobOpenController@close',
        // 'middleware' => ['permission:industry-create']
    ]);

    Route::get('job/allclose', [
        'as' => 'jobopen.allclose',
        'uses' => 'JobOpenController@getAllCloseJobDetails',
    ]);

    Route::get('job/associatedcandidate', [
        'as' => 'jobopen.associatedcandidate',
        'uses' => 'JobOpenController@getAssociatedcandidates',
        // 'middleware' => ['permission:industry-create']
    ]);

    //job status
     Route::post('jobs/jobonhlod', [
        'as' => 'jobopen.jobonhold',
        'uses' => 'JobOpenController@jobonhold',
    ]);

    Route::post('jobs/jobclosebyus', [
        'as' => 'jobopen.jobclosebyus',
        'uses' => 'JobOpenController@jobclosebyus',
    ]);

    Route::post('jobs/jobclosebyclient', [
        'as' => 'jobopen.jobclosebyclient',
        'uses' => 'JobOpenController@jobclosebyclient',
    ]);

    //candidate shortlisted
    Route::post('jobs/shortlisted/{id}',[
        'as' => 'jobopen.shortlisted',
        'uses' => 'JobOpenController@shortlisted',
    ]);

    //undo shortlisted candidate
    Route::post('jobs/undo/{id}',[
        'as' => 'jobopen.undo',
        'uses' => 'JobOpenController@undoshortlisted',
    ]);

    // get list of associated cvs
    Route::get('associatedcvs', [
        'as' => 'jobopen.associatedcvs',
        'uses' => 'JobOpenController@associatedCVS',
        //'middleware' => ['permission:industry-edit']
    ]);

    // Associated candidate mail route
    Route::post('/jobs/checkids',[
        'as' => 'jobs.checkids',
        'uses' => 'JobOpenController@CheckIds'
    ]);

    Route::post('/jobs/usersforsendmail',[
        'as' => 'jobs.usersforsendmail',
        'uses' => 'JobOpenController@UsersforSendMail'
    ]);

    Route::post('/jobs/associatedcandidatemail',[
        'as' => 'jobs.associatedcandidatemail',
        'uses' => 'JobOpenController@AssociatedCandidateMail'
    ]);

    // Interview Module
    Route::get('interview', [
        'as' => 'interview.index',
        'uses' => 'InterviewController@index'
    ]);

    Route::get('interview/all', [
        'as' => 'interview.all',
        'uses' => 'InterviewController@getAllInterviewsDetails'
    ]);

    Route::get('todaytomorrow',[
        'as' => 'interview.todaytomorrow',
        'uses' => 'InterviewController@todaytomorrow'
    ]);

    Route::get('interview/today', [
        'as' => 'interview.today',
        'uses' => 'InterviewController@today'
    ]);

    Route::get('interview/tomorrow', [
        'as' => 'interview.tomorrow',
        'uses' => 'InterviewController@tomorrow'
    ]);

    Route::get('interview/thisweek', [
        'as' => 'interview.thisweek',
        'uses' => 'InterviewController@thisweek'
    ]);

    Route::get('interview/upcomingprevious', [
        'as' => 'interview.upcomingprevious',
        'uses' => 'InterviewController@UpcomingPrevious'
    ]);

    Route::get('attendedinterview',[
        'as' => 'interview.attendedinterview',
        'uses' => 'InterviewController@attendedinterview'
    ]);


    Route::get('interview/create', [
        'as' => 'interview.create',
        'uses' => 'InterviewController@create'
    ]);

    Route::post('interview/store', [
        'as' => 'interview.store',
        'uses' => 'InterviewController@store'
    ]);

    Route::get('interview/{id}/show', [
        'as' => 'interview.show',
        'uses' => 'InterviewController@show'
    ]);

    Route::get('interview/{id}/edit/{source}', [
        'as' => 'interview.edit',
        'uses' => 'InterviewController@edit'
    ]);

    Route::put('interview/{id}/{source}', [
        'as' => 'interview.update',
        'uses' => 'InterviewController@update'
    ]);

    Route::delete('interview/{id}/{source}', [
        'as' => 'interview.destroy',
        'uses' => 'InterviewController@destroy'
    ]);
    Route::get('interview/getclientinfos', [
        'as' => 'interview.getclientinfos',
        'uses' => 'InterviewController@getClientInfos'
    ]);

    Route::post('interview/checkidsmail',[
        'as' => 'interview.checkidsmail',
        'uses' => 'InterviewController@CheckIdsforMail'
    ]);

    Route::post('interview/multipleinterviewschedule',[
        'as' => 'interview.multipleinterviewschedule',
        'uses' => 'InterviewController@multipleInterviewScheduleMail'
    ]);
    /*Route::get('ajax/interviewcandidate', [
        'as' => 'interview.getCandidate',
        'uses' => 'InterviewController@getCandidate',
    ]);*/

    // Bills Module
    Route::get('forecasting/create', [
        'as' => 'bills.create',
        'uses' => 'BillsController@create',
        'middleware' => ['permission:bnm-create']
    ]);

    Route::get('forecasting', [
        'as' => 'forecasting.index',
        'uses' => 'BillsController@index',
        'middleware' => ['permission:bills-list']
    ]);

    Route::get('forecasting/cancel', [
        'as' => 'forecasting.cancelbnm',
        'uses' => 'BillsController@cancelbnm'
    ]);

    Route::get('recovery', [
        'as' => 'bills.recovery',
        'uses' => 'BillsController@billsMade'
    ]);

    Route::get('recovery/cancel', [
        'as' => 'bills.bmcancel',
        'uses' => 'BillsController@cancelbm'
    ]);

    Route::get('forecasting/{id}/edit', [
        'as' => 'forecasting.edit',
        'uses' => 'BillsController@edit',
        'middleware' => ['permission:bnm-edit']
    ]);

    Route::patch('forecasting/{id}', [
        'as' => 'forecasting.update',
        'uses' => 'BillsController@update',
        'middleware' => ['permission:bnm-edit']
    ]);

    Route::post('forecasting/store', [
        'as' => 'forecasting.store',
        'uses' => 'BillsController@store',
        'middleware' => ['permission:bills-list']
    ]);

    Route::get('recovery/{id}/generaterecovery', [
        'as' => 'bills.generaterecovery',
        'uses' => 'BillsController@generateBM'
    ]);

    Route::get('forecasting/{id}/show', [
        'as' => 'forecasting.show',
        'uses' => 'BillsController@show'
    ]);

    Route::delete('forecasting/{id}', [
        'as' => 'forecasting.destroy',
        'uses' => 'BillsController@delete',
        'middleware' => ['permission:bnm-delete']
    ]);

    Route::get('forecasting/{id}', [
        'as' => 'forecasting.cancel',
        'uses' => 'BillsController@cancel'
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
        'uses' => 'BillsController@attachmentsDestroy'
    ]);
    Route::post('billattachments/upload/{id}', [
        'as' => 'billattachments.upload',
        'uses' => 'BillsController@upload',
    ]);
    Route::post('recovery/sendconfirmationmail/{id}',[
        'as' => 'recovery.sendconfirmationmail',
        'uses' => 'BillsController@SendConfirmationMail'
    ]);
    //for relive bill
    Route::get('recovery/{id}',[
        'as' => 'recovery.relive',
        'uses' => 'BillsController@reliveBill'
    ]);



    // Admin > Teams
    Route::get('team', [
        'as' => 'team.index',
        'uses' => 'TeamController@index',
//        'middleware' => ['permission:team-list|team-create|team-edit|team-delete']
    ]);
    Route::get('team/create', [
        'as' => 'team.create',
        'uses' => 'TeamController@create',
//        'middleware' => ['permission:team-create']
    ]);
    Route::post('team/create', [
        'as' => 'team.store',
        'uses' => 'TeamController@store',
//        'middleware' => ['permission:team-create']
    ]);
    Route::get('team/{id}', [
        'as' => 'team.show',
//        'uses' => 'TeamController@show'
    ]);
    Route::get('team/{id}/edit', [
        'as' => 'team.edit',
        'uses' => 'TeamController@edit',
//        'middleware' => ['permission:team-edit']
    ]);
    Route::patch('team/{id}', [
        'as' => 'team.update',
        'uses' => 'TeamController@update',
        'middleware' => ['permission:team-edit']
    ]);
    Route::delete('team/{id}', [
        'as' => 'team.destroy',
        'uses' => 'TeamController@destroy',
//        'middleware' => ['permission:team-delete']
    ]);


    // To Do's Routes start

    Route::get('todos', [
        'as' => 'todos.index',
        'uses' => 'ToDosController@index',
    ]);

     Route::get('todos/complete', [
        'as' => 'todos.completetodo',
        'uses' => 'ToDosController@completetodo',
    ]);

    Route::get('todos/mytask', [
        'as' => 'todos.mytask',
        'uses' => 'ToDosController@mytask',
    ]);

    Route::get('todos/create', [
        'as' => 'todos.create',
        'uses' => 'ToDosController@create',
    ]);

    Route::post('todos/store', [
        'as' => 'todos.store',
        'uses' => 'ToDosController@store',
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
    ]);

    Route::get('todos/weekly', [
        'as' => 'todos.weekly',
        'uses' => 'ToDosController@weekly',
    ]);

    Route::get('todos/monthly', [
        'as' => 'todos.monthly',
        'uses' => 'ToDosController@monthly',
    ]);

    Route::get('todos/read', [
        'as' => 'todos.read',
        'uses' => 'ToDosController@readTodos',
    ]);

    Route::get('todos/{id}', [
        'as' => 'todos.show',
        'uses' => 'ToDosController@show'
    ]);
    Route::get('todos/{id}/edit', [
        'as' => 'todos.edit',
        'uses' => 'ToDosController@edit',
    ]);
    Route::put('todos/{id}', [
        'as' => 'todos.update',
        'uses' => 'ToDosController@update',
        
    ]);
    Route::delete('todos/{id}', [
        'as' => 'todos.destroy',
        'uses' => 'ToDosController@destroy',
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


    // Admin > Industry
    Route::get('companies', [
        'as' => 'companies.index',
        'uses' => 'CompaniesController@index',
        'middleware' => ['permission:companies-list|companies-create|companies-edit']
    ]);
    Route::get('companies/create', [
            'as' => 'companies.create',
            'uses' => 'CompaniesController@create',
            'middleware' => ['permission:companies-create']
    ]);
    Route::post('companies/create', [
        'as' => 'companies.store',
        'uses' => 'CompaniesController@store',
        'middleware' => ['permission:companies-create']
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
        'middleware' => ['permission:industry-edit']
    ]);
    /*Route::delete('companies/{id}', [
        'as' => 'companies.destroy',
        'uses' => 'CompaniesController@destroy',
         'middleware' => ['permission:industry-delete']
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

    //Training Routes start

     // Admin > Training
    Route::get('training', [
        'as' => 'training.index',
        'uses' => 'TrainingController@index',
        'middleware' => ['permission:training-list']
    ]);
    Route::get('training/create', [
        'as' => 'training.create',
        'uses' => 'TrainingController@create',
        'middleware' => ['permission:training-create']
    ]);
    Route::post('training/create', [
        'as' => 'training.store',
        'uses' => 'TrainingController@store',
        'middleware' => ['permission:training-create']
    ]);
    
    Route::get('training/{id}/edit', [
        'as' => 'training.edit',
        'uses' => 'TrainingController@edit',
        'middleware' => ['permission:training-edit']
    ]);
    Route::patch('training/{id}', [
        'as' => 'training.update',
        'uses' => 'TrainingController@update',
        'middleware' => ['permission:training-edit']
    ]);
    

    Route::post('training/upload/{id}', [
        'as' => 'trainingattachments.upload',
        'uses' => 'TrainingController@upload',
        'middleware' => ['permission:training-edit']
    ]);

    Route::get('training/{id}/show', [
        'as' => 'training.show',
        'uses' => 'TrainingController@show',
        'middleware' => ['permission:training-list']
    ]);

    Route::delete('training/{id}', [
        'as' => 'training.destroy',
        'uses' => 'TrainingController@trainingDestroy',
        'middleware' => ['permission:training-destroy']
    ]);

    Route::delete('training/destroy/{id}', [
        'as' => 'trainingattachments.destroy',
        'uses' => 'TrainingController@attachmentsDestroy',
        'middleware' => ['permission:training-destroy']
   ]);

    // Admin > Process Manual
    
    Route::get('process', [
        'as' => 'process.index',
        'uses' => 'ProcessController@index',
        'middleware' => ['permission:process-list']
    ]);
    
    Route::get('process/create', [
        'as' => 'process.create',
        'uses' => 'ProcessController@create',
        'middleware' => ['permission:process-create']
    ]);

    Route::post('process/create', [
        'as' => 'process.store',
        'uses' => 'ProcessController@store',
        'middleware' => ['permission:process-create']
    ]);
    Route::get('process/{id}/edit', [
        'as' => 'process.edit',
        'uses' => 'ProcessController@edit',
        'middleware' => ['permission:process-edit']
    ]);
    Route::patch('process/{id}', [
        'as' => 'process.update',
        'uses' => 'ProcessController@update',
        'middleware' => ['permission:process-edit']
    ]);
    Route::post('process/upload/{id}', [
        'as' => 'processattachments.upload',
        'uses' => 'ProcessController@upload',
        'middleware' => ['permission:process-edit']
    ]);
    Route::get('process/{id}/show', [
        'as' => 'process.show',
        'uses' => 'ProcessController@show',
        'middleware' => ['permission:process-list']
    ]);

    Route::delete('process/{id}', [
        'as' => 'process.destroy',
        'uses' => 'ProcessController@processDestroy',
        'middleware' => ['permission:process-destroy']
    ]);

    Route::delete('process/destroy/{id}', [
        'as' => 'processattachments.destroy',
        'uses' => 'ProcessController@attachmentsDestroy',
        'middleware' => ['permission:process-destroy']
   ]);

    // Admin > Accounting Heads
    
    Route::get('accounting', [
        'as' => 'accounting.index',
        'uses' => 'AccountingController@index',
//        'middleware' => ['permission:team-list|team-create|team-edit|team-delete']
    ]);
    Route::get('accounting/create', [
        'as' => 'accounting.create',
        'uses' => 'AccountingController@create',
//        'middleware' => ['permission:team-create']
    ]);
    Route::post('accounting/create', [
        'as' => 'accounting.store',
        'uses' => 'AccountingController@store',
//        'middleware' => ['permission:team-create']
    ]);
    
    Route::get('accounting/{id}/edit', [
        'as' => 'accounting.edit',
        'uses' => 'AccountingController@edit',
//        'middleware' => ['permission:team-edit']
    ]);
    Route::patch('accounting/{id}', [
        'as' => 'accounting.update',
        'uses' => 'AccountingController@update',
       // 'middleware' => ['permission:team-edit']
    ]);
    
    Route::delete('accounting/{id}', [
        'as' => 'accounting.destroy',
        'uses' => 'AccountingController@Destroy',

    ]);

    // Expense Route
    Route::get('expense',[
        'as' => 'expense.index',
        'uses' => 'ExpenseController@index',
        'middleware' => ['permission:expense-list|expense-create|expense-edit|expense-delete']
        
    ]);

    Route::get('expense/create',[
        'as' => 'expense.create',
        'uses' => 'ExpenseController@create',
        'middleware' => ['permission:expense-create']
    ]);

    Route::post('expense/create',[
        'as' => 'expense.store',
        'uses' => 'ExpenseController@store',
        'middleware' => ['permission:expense-create']
    ]);

    Route::get('expense/importExport',[ 
        'as' => 'expense.importExport',
        'uses' => 'ExpenseController@importExport',
        'middleware' => ['permission:expense-create']
    ]);

    Route::post('expense/importExcel',[
        'as' => 'expense.importExcel',
        'uses' => 'ExpenseController@importExcel'
    ]);

    Route::get('expense/getvendorinfo', [
        'as' => 'expense.getvendorinfo',
        'uses' => 'ExpenseController@getVendorInfo'
    ]);

    Route::get('expense/{id}', [
        'as' => 'expense.show',
        'uses' => 'ExpenseController@show'
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
        'middleware' => ['permission:billrecovery-list']
    ]);

    Route::post('recoveryreport/export',[
        'as' => 'recoveryreport.export',
        'uses' => 'RecoveryReportController@export',
        'middleware' => ['permission:billrecovery-list']
    ]);

    Route::any('selectionreport',[
        'as' => 'selectionreport.index',
        'uses' => 'SelectionReportController@index',
        'middleware' => ['permission:billselection-list']
    ]);

    Route::post('selectionreport/export',[
        'as' => 'selectionreport.export',
        'uses' => 'SelectionReportController@export',
        'middleware' => ['permission:billselection-list']
    ]);

    Route::any('userreport',[
        'as' => 'userreport.index',
        'uses' => 'UserwiseReportController@index',
        'middleware' => ['permission:billuserwise-list']

    ]);

    Route::post('userreport/export',[
        'as' => 'userreport.export',
        'uses' => 'UserwiseReportController@export',
        'middleware' => ['permission:billuserwise-list']
    ]);

    Route::any('daily-report',[
        'as' => 'report.dailyreportindex',
        'uses' => 'ReportController@dailyreportIndex'
    ]);

    Route::any('weekly-report',[
        'as' => 'report.weeklyreportindex',
        'uses' => 'ReportController@weeklyreportIndex'
    ]);

    Route::any('monthly-report',[
        'as' => 'report.monthlyreportindex',
        'uses' => 'ReportController@monthlyreportIndex'
    ]);

    Route::any('userwise-monthly-report',[
        'as' => 'report.monthlyreportindex',
        'uses' => 'ReportController@userWiseMonthlyReport'
    ]);

    Route::get('vendors', [
        'as' => 'vendor.index',
        'uses' => 'VendorController@index',
        'middleware' => ['permission:vendor-list|vendor-create|vendor-edit|vendor-delete']
        
    ]);

    Route::get('vendor/create', [
        'as' => 'vendor.create',
        'uses' => 'VendorController@create',
        'middleware' => ['permission:vendor-create']
        
    ]);

    Route::post('vendor/create', [
        'as' => 'vendor.store',
        'uses' => 'VendorController@store',
        'middleware' => ['permission:vendor-create']
    ]);

    Route::get('vendor/importExport', 
        'VendorController@importExport'
    );

    Route::post('vendor/importExcel', 
        'VendorController@importExcel'
    );

    Route::get('vendor/{id}', [
        'as' => 'vendor.show',
        'uses' => 'VendorController@show'
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
        'middleware' => ['permission:holiday-list|holiday-create|holiday-edit|holiday-delete']
    ]);

    Route::get('holidays/create', [
        'as' => 'holidays.create',
        'uses' => 'HolidaysController@create',
        'middleware' => ['permission:holiday-list|holiday-create|holiday-edit|holiday-delete']
    ]);

    Route::post('holidays/create', [
        'as' => 'holidays.store',
        'uses' => 'HolidaysController@store',
        'middleware' => ['permission:holiday-list|holiday-create|holiday-edit|holiday-delete']
    ]);

    Route::get('holidays/edit/{id}', [
        'as' => 'holidays.edit',
        'uses' => 'HolidaysController@edit',
        'middleware' => ['permission:holiday-list|holiday-create|holiday-edit|holiday-delete']
    ]);

    Route::patch('holidays/{id}', [
        'as' => 'holidays.update',
        'uses' => 'HolidaysController@update',
        'middleware' => ['permission:holiday-list|holiday-create|holiday-edit|holiday-delete']
    ]);

    Route::delete('holidays/{id}', [
        'as' => 'holidays.destroy',
        'uses' => 'HolidaysController@destroy',
        'middleware' => ['permission:holiday-list|holiday-create|holiday-edit|holiday-delete']
    ]);

    // Receipt module

    //Talent
    Route::any('receipt/talent',[
        'as' => 'receipt.talent',
        'uses' => 'ReceiptController@receiptTalent',
        'middleware' => ['permission:receipt-talent']
    ]);

    Route::get('receipt/talent/import',[
        'as' => 'receipt.talentimport',
        'uses' => 'ReceiptController@receiptTalentImport',
        'middleware' => ['permission:receipt-talent']
    ]);

    Route::post('receipt/talent/import',[
        'as' => 'receipt.talentimportstore',
        'uses' => 'ReceiptController@receiptTalentImportStore',
        'middleware' => ['permission:receipt-talent']
    ]);

    Route::get('receipt/talent/create',[
        'as' => 'receipt.talentcreate',
        'uses' => 'ReceiptController@receiptTalentCreate',
        'middleware' => ['permission:receipt-talent']
    ]);

    Route::post('receipt/talent/store',[
        'as' => 'receipt.talentstore',
        'uses' => 'ReceiptController@receiptTalentStore',
        'middleware' => ['permission:receipt-talent']
    ]);

    //Temp

    Route::any('receipt/temp',[
        'as' => 'receipt.temp',
        'uses' => 'ReceiptController@receiptTemp',
        'middleware' => ['permission:receipt-temp']
    ]);

    Route::get('receipt/temp/import',[
        'as' => 'receipt.tempimport',
        'uses' => 'ReceiptController@receiptTempImport',
        'middleware' => ['permission:receipt-temp']
    ]);

    Route::post('receipt/temp/import',[
        'as' => 'receipt.tempimportstore',
        'uses' => 'ReceiptController@receiptTempImportStore',
        'middleware' => ['permission:receipt-temp']
    ]);

    Route::get('receipt/temp/create',[
        'as' => 'receipt.tempcreate',
        'uses' => 'ReceiptController@receiptTempCreate',
        'middleware' => ['permission:receipt-temp']
    ]);

    Route::post('receipt/temp/store',[
        'as' => 'receipt.tempstore',
        'uses' => 'ReceiptController@receiptTempStore',
        'middleware' => ['permission:receipt-temp']
    ]);

    //Other

    Route::any('receipt/other',[
        'as' => 'receipt.other',
        'uses' => 'ReceiptController@receiptOther',
        'middleware' => ['permission:receipt-temp']
    ]);

    Route::get('receipt/other/import',[
        'as' => 'receipt.otherimport',
        'uses' => 'ReceiptController@receiptOtherImport',
        'middleware' => ['permission:receipt-temp']
    ]);

    Route::post('receipt/other/import',[
        'as' => 'receipt.otherimportstore',
        'uses' => 'ReceiptController@receiptOtherImportStore',
        'middleware' => ['permission:receipt-temp']
    ]);

    Route::get('receipt/other/create',[
        'as' => 'receipt.othercreate',
        'uses' => 'ReceiptController@receiptOtherCreate',
        'middleware' => ['permission:receipt-temp']
    ]);

    Route::post('receipt/other/store',[
        'as' => 'receipt.otherstore',
        'uses' => 'ReceiptController@receiptOtherStore',
        'middleware' => ['permission:receipt-temp']
    ]);

    Route::get('receipt/edit/{id}',[
        'as' => 'receipt.edit',
        'uses' => 'ReceiptController@edit',
        'middleware' => ['permission:receipt-temp|receipt-talent|receipt-others']
    ]);

    Route::patch('receipt/{id}',[
        'as' => 'receipt.update',
        'uses' => 'ReceiptController@update',
        'middleware' => ['permission:receipt-temp|receipt-talent|receipt-others']
    ]);

    Route::delete('receipt/{id}',[
        'as' => 'receipt.destroy',
        'uses' => 'ReceiptController@ReceiptDestroy',
        'middleware' => ['permission:receipt-temp|receipt-talent|receipt-others']
    ]);

    // Module Route
    Route::get('module',[
        'as' => 'module.index',
        'uses' => 'ModuleController@index',
        'middleware' => ['permission:modulevisible-list']
    ]);

    Route::get('module/create',[
        'as' => 'module.create',
        'uses' => 'ModuleController@create',
        'middleware' => ['permission:modulevisible-create']
    ]);

    Route::post('module/create',[
        'as' => 'module.store',
        'uses' => 'ModuleController@store',
        'middleware' => ['permission:modulevisible-create']
    ]);

    Route::get('module/edit/{id}',[
        'as' => 'module.edit',
        'uses' => 'ModuleController@edit',
        'middleware' => ['permission:modulevisible-edit']
    ]);

    Route::patch('module/edit/{id}',[
        'as' => 'module.update',
        'uses' => 'ModuleController@update',
        'middleware' => ['permission:modulevisible-edit']
    ]);

    Route::delete('module/{d}',[
        'as' => 'module.destroy',
        'uses' => 'ModuleController@destroy',
        'middleware' => ['permission:modulevisible-delete']
    ]);

    // Module Visible Users route
    Route::get('modulevisible',[
        'as' => 'modulevisible.index',
        'uses' => 'ModuleVisibleController@index',
        'middleware' => ['permission:modulevisible-list']
    ]);

    Route::get('modulevisible/create',[
        'as' => 'modulevisible.create',
        'uses' => 'ModuleVisibleController@create',
        'middleware' => ['permission:modulevisible-create']
    ]);

    Route::post('modulevisible/create',[
        'as' => 'modulevisible.store',
        'uses' => 'ModuleVisibleController@store',
        'middleware' => ['permission:modulevisible-create']
    ]);

    Route::get('modulevisible/{id}/edit',[
        'as' => 'modulevisible.edit',
        'uses' => 'ModuleVisibleController@edit',
        'middleware' => ['permission:modulevisible-edit']
    ]);

    Route::patch('modulevisible/{id}/edit',[
        'as' => 'modulevisible.update',
        'uses' => 'ModuleVisibleController@update',
        'middleware' => ['permission:modulevisible-edit']
    ]);

    Route::delete('modulevisible/{id}',[
        'as' => 'modulevisible.destroy',
        'uses' => 'ModuleVisibleController@destroy',
        'middleware' => ['permission:modulevisible-delete']
    ]);

    Route::post('/usermodule/visible',[
        'as' => 'usermodule.visible',
        'uses' => 'ModuleVisibleController@userWiseModuleAjax'
    ]);
    
});

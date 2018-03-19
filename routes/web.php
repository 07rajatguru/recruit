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


Route::group(['middleware' => ['auth']], function () {

    Route::any('/dashboard', array (
        'middleware' => 'auth',
        'uses' => 'HomeController@dashboard'
    ));

    Route::any('/home', array (
        'middleware' => 'auth',
        'uses' => 'HomeController@index'
    ));

    // Admin > Users
    Route::get('users', [
        'as' => 'users.index',
        'uses' => 'UserController@index',
        'middleware' => ['permission:user-list|user-create|user-edit|user-delete']
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
    Route::get('client', [
        'as' => 'client.index',
        'uses' => 'ClientController@index',
        //'middleware' => ['permission:industry-list|industry-create|industry-edit|industry-delete']
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


    // CANDIDATE
    Route::get('candidate', [
        'as' => 'candidate.index',
        'uses' => 'CandidateController@index'
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

    Route::get('jobs/getopenjobs', [
        'as' => 'jobopen.getOpenJobs',
        'uses' => 'JobOpenController@getOpenJobs',
        // 'middleware' => ['permission:industry-create']
    ]);
    Route::get('jobs/importExport', 'JobOpenController@importExport');
    Route::post('jobs/importExcel', 'JobOpenController@importExcel');
    Route::get('jobs', [
        'as' => 'jobopen.index',
        'uses' => 'JobOpenController@index',
        //'middleware' => ['permission:industry-list|industry-create|industry-edit|industry-delete']
    ]);
    Route::post('jobs', [
        'as' => 'jobopen.index',
        'uses' => 'JobOpenController@index',
        //'middleware' => ['permission:industry-list|industry-create|industry-edit|industry-delete']
    ]); 
    Route::post('jobs/create', [
        'as' => 'jobopen.store',
        'uses' => 'JobOpenController@store',
        //'middleware' => ['permission:industry-create']

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
    Route::get('job/close', [
        'as' => 'jobopen.close',
        'uses' => 'JobOpenController@close',
        // 'middleware' => ['permission:industry-create']
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




    // Interview Module
    Route::get('interview', [
        'as' => 'interview.index',
        'uses' => 'InterviewController@index'
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

    Route::get('interview/{id}/edit', [
        'as' => 'interview.edit',
        'uses' => 'InterviewController@edit'
    ]);

    Route::put('interview/{id}', [
        'as' => 'interview.update',
        'uses' => 'InterviewController@update'
    ]);

    Route::delete('interview/{id}', [
        'as' => 'interview.destroy',
        'uses' => 'InterviewController@destroy'
    ]);

    /*Route::get('ajax/interviewcandidate', [
        'as' => 'interview.getCandidate',
        'uses' => 'InterviewController@getCandidate',
    ]);*/

    // Bills Module
    Route::get('bnm/create', [
        'as' => 'bills.create',
        'uses' => 'BillsController@create'
    ]);

    Route::get('bnm', [
        'as' => 'bnm.index',
        'uses' => 'BillsController@index'
    ]);

    Route::get('bm', [
        'as' => 'bills.bm',
        'uses' => 'BillsController@billsMade'
    ]);

    Route::get('bnm/{id}/edit', [
        'as' => 'bnm.edit',
        'uses' => 'BillsController@edit'
    ]);

    Route::patch('bnm/{id}', [
        'as' => 'bnm.update',
        'uses' => 'BillsController@update'
    ]);

    Route::post('bnm/store', [
        'as' => 'bnm.store',
        'uses' => 'BillsController@store'
    ]);

    Route::get('bills/{id}/generatebm', [
        'as' => 'bills.generatebm',
        'uses' => 'BillsController@generateBM'
    ]);

    Route::get('bnm/{id}', [
        'as' => 'bnm.show',
        'uses' => 'BillsController@show'
    ]);

    Route::post('bills/downloadexcel', [
        'as' => 'bnm.downloadexcel',
        'uses' => 'BillsController@downloadExcel'
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
    Route::get('todo/getselectedtypelist', [
        'as' => 'todos.getselectedtypelist',
        'uses' => 'ToDosController@getSelectedTypeList',
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

});


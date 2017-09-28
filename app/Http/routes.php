<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|

*/

Route::get('/', ['as' => 'admin.home', 'middleware' => 'auth.login','uses' => '\App\Http\Controllers\LoginController@index']);

/*Route::get('/', ['as' => 'admin.home', 'middleware' => 'auth.login','uses' => '\App\Http\Controllers\LoginController@index']);


Route::group(['prefix' => config('admin.prefix', 'admin')], function () {



    Route::group(['middleware' => 'auth.login'], function () {




    });
});

// Users

Route::get ('users', array (
    'middleware' => 'auth.login',
    'as' => 'users.index',
    'uses' => '\App\Http\Controllers\UsersController@index'
));

Route::get ('users/create', array (
    'middleware' => 'auth.login',
    'as' => 'users.create',
    'uses' => '\App\Http\Controllers\UsersController@create'
));

Route::post ('users/store', array (
    'middleware' => 'auth.login',
    'as' => 'users.store',
    'uses' => '\App\Http\Controllers\UsersController@store'
));

Route::get ('users/{id}/edit', array (
    'middleware' => 'auth.login',
    'as' => 'users.edit',
    'uses' => '\App\Http\Controllers\UsersController@edit'
));

Route::put ('users/{id}/update', array (
    'middleware' => 'auth.login',
    'as' => 'users.update',
    'uses' => '\App\Http\Controllers\UsersController@update'
));

Route::delete ('users/{id}/destroy', array (
    'middleware' => 'auth.login',
    'as' => 'users.destroy',
    'uses' => '\App\Http\Controllers\UsersController@destroy'
));


Route::get ('changePassword', array (
    'middleware' => 'auth',
    'as' => 'changePassword',
    'uses' => '\App\Http\Controllers\UsersController@changePassword'
));

Route::post ('changePassword', array (
    'middleware' => 'auth',
    'as' => 'changePasswordStored',
    'uses' => '\App\Http\Controllers\UsersController@changePasswordStored'
));

Route::post('ajax/check_password', array(
    'middleware' => 'auth',
    'as' => 'check_password',
    'uses' =>'\App\Http\Controllers\UsersController@check_password'
));



// Temp route to read log files
Route::get ('admin/logfile', array (
    'middleware' => 'auth.login',
    'as' => 'admin.logfile',
    'uses' => '\App\Http\Controllers\CommonTaskController@readLatestLogFile'
));


Route::get ('activities/', array (
    'middleware' => 'auth.login',
    'as' => 'steps.index',
    'uses' => '\App\Http\Controllers\StepsController@activitiesIndex'
));

Route::get ('activities/add', array (
    'middleware' => 'auth.login',
    'as' => 'admin.steps.add',
    'uses' => '\App\Http\Controllers\StepsController@create'
));

Route::post ('activities/add', array (
    'middleware' => 'auth.login',
    'as' => 'admin.steps.add',
    'uses' => '\App\Http\Controllers\StepsController@add'
));

Route::get ('activities/edit/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'admin.steps.edit',
    'uses' => '\App\Http\Controllers\StepsController@edit'
));

//get Activities Node
Route::get ('/activities/ajax/getActivitiesNode', array (
    'middleware' => 'auth',
    'as' => 'admin.steps.getActivitiesNode',
    'uses' => '\App\Http\Controllers\StepsController@getActivitiesNode'
));


Route::put ('admin/launchflow/update/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'admin.steps.update',
    'uses' => '\App\Http\Controllers\StepsController@update'
));

Route::post ('activities/upload', array (
    'middleware' => 'auth.login',
    'as' => 'admin.steps.upload',
    'uses' => '\App\Http\Controllers\StepsController@importActivities'
));

Route::get ('activities/shipping/index', array (
    'middleware' => 'auth.login',
    'as' => 'admin.shipping.index',
    'uses' => '\App\Http\Controllers\StepsController@shippingIndex'
));
Route::get ('activities/shipping', array (
    'middleware' => 'auth.login',
    'as' => 'admin.shipping',
    'uses' => '\App\Http\Controllers\StepsController@shipping'
));
Route::post ('activities/shipping/upload', array (
    'middleware' => 'auth.login',
    'as' => 'admin.shipping.upload',
    'uses' => '\App\Http\Controllers\StepsController@importShipping'
));

Route::get('activities/export', array(
    'middleware' => 'auth',
    'as' => 'activities.export',
    'uses' => '\App\Http\Controllers\StepsController@exportActivities'
));
Route::get('activities/import', array(
    'middleware' => 'auth',
    'as' => 'activities.import',
    'uses' => '\App\Http\Controllers\StepsController@importActivitiesindex'
));


Route::get('/launchflow/getlevel1list', array(
	'middleware' => 'auth.login',
	'as' => 'admin.steps.getlevel1list',
	'uses' =>'\App\Http\Controllers\StepsController@getlevel1list'
));

Route::get('/launchflow/getlevel2list', array(
	'middleware' => 'auth.login',
	'as' => 'admin.steps.getlevel2list',
	'uses' =>'\App\Http\Controllers\StepsController@getlevel2list'
));

Route::get('/launchflow/getlevel3list', array(
	'middleware' => 'auth.login',
	'as' => 'admin.steps.getlevel3list',
	'uses' =>'\App\Http\Controllers\StepsController@getlevel3list'
));
Route::get ('/launchflow/getactivitieslist', array (
    'middleware' => 'auth.login',
    'as' => 'admin.template.getactivitieslist',
    'uses' => '\App\Http\Controllers\StepsController@getactivitieslist'
));

// Template
Route::get ('template/list', array (
    'middleware' => 'auth.login',
    'as' => 'template.index',
    'uses' => '\App\Http\Controllers\TemplateController@index'
));

Route::get ('template/add', array (
    'middleware' => 'auth.login',
    'as' => 'admin.template.add',
    'uses' => '\App\Http\Controllers\TemplateController@create'
));

Route::post ('admin/template/add', array (
    'middleware' => 'auth.login',
    'as' => 'admin.template.add',
    'uses' => '\App\Http\Controllers\TemplateController@insert'
));

Route::get ('template/edit/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'template.edit',
    'uses' => '\App\Http\Controllers\TemplateController@edit'
));

Route::put ('admin/template/update/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'admin.template.update',
    'uses' => '\App\Http\Controllers\TemplateController@update'
));

Route::get ('admin/template/delete/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'admin.template.delete',
    'uses' => '\App\Http\Controllers\TemplateController@delete'
));

// Project
Route::get ('project/list/', array (
    'middleware' => 'auth.login',
    'as' => 'project.index',
    'uses' => '\App\Http\Controllers\ProjectController@index'
));

Route::get ('project/add', array (
    'middleware' => 'auth.login',
    'as' => 'admin.project.add',
    'uses' => '\App\Http\Controllers\ProjectController@create'
));

Route::post ('project/add', array (
    'middleware' => 'auth.login',
    'as' => 'project.add',
    'uses' => '\App\Http\Controllers\ProjectController@insert'
));

Route::post ('project/newProjectApprovalMail/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'project.newProjectApprovalMail',
    'uses' => '\App\Http\Controllers\ProjectController@newProjectApprovalMail'
));

Route::post ('project/add/selectAvtivities', array (
    'middleware' => 'auth.login',
    'as' => 'project.add.selectActivities',
    'uses' => '\App\Http\Controllers\ProjectController@getParentActivities'
));

Route::get ('project/approval/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'admin.project.approval',
    'uses' => '\App\Http\Controllers\ProjectController@projectApprovel'
));

Route::post ('project/approval/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'project.approval',
    'uses' => '\App\Http\Controllers\ProjectController@projectApprovelStore'
));

Route::get ('project/edit/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'project.edit',
    'uses' => '\App\Http\Controllers\ProjectController@edit'
));

Route::put ('project/update/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'project.update',
    'uses' => '\App\Http\Controllers\ProjectController@update'
));

Route::put ('project/update/selectAvtivities/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'project.update.selectAvtivities',
    'uses' => '\App\Http\Controllers\ProjectController@getUpdateParentActivities'
));

Route::delete ('project/destroy/{id}', array (
    'middleware' => 'auth.login',
    'as' => 'project.destroy',
    'uses' => '\App\Http\Controllers\ProjectController@destroy'
));

Route::get ('project/secondarylevel/{id}/{isProjectAdmin}', array (
        'middleware' => 'auth.login',
        'as' => 'project.secondarylevel',
        'uses' => '\App\Http\Controllers\ProjectController@secondaryLevel'
));

Route::get ('project/secondryListactivities/{project_id}/{is_project_admin}/{activity_id}', array (
        'middleware' => 'auth.login',
        'as' => 'project.secondarylevel',
        'uses' => '\App\Http\Controllers\ProjectController@secondaryListactivities'
));

Route::post ('project/save/', array (
    'middleware' => 'auth.login',
    'as' => 'project.save',
    'uses' => '\App\Http\Controllers\ProjectController@save'
));

Route::put ('project/updateProject', array (
    'middleware' => 'auth.login',
    'as' => 'project.updateProject',
    'uses' => '\App\Http\Controllers\ProjectController@updateProject'
));

Route::post('project/saveActivities/', array (
    'as' => 'project.saveActivities',
    'uses' => '\App\Http\Controllers\ProjectController@saveActivities'
));

Route::get('project/getMemberList/{project_id}', array (
    'as' => 'project.getMemberList',
    'uses' => '\App\Http\Controllers\ProjectController@getMemberList'
));

Route::get('/ajax/statusUpdateModal', array (
    'as' => 'project.statusUpdateModal',
    'uses' => '\App\Http\Controllers\ProjectController@statusUpdateModal'
));

Route::get('/ajax/documentsModal', array (
    'as' => 'project.documentsModal',
    'uses' => '\App\Http\Controllers\ProjectController@documentsModal'
));



Route::get ('/documents/{id}/{project_admin}/documentsDestroy', array (
    'middleware' => 'auth.login',
    'as' => 'documents.documentsDestroy',
    'uses' => '\App\Http\Controllers\ProjectController@documentsDestroy'
));


Route::post ( 'files/upload', array (
    'middleware' => 'auth.login',
    'as' => 'file_uploader',
    'uses' => '\App\Http\Controllers\FilesController@uploadFile'
));

Route::get('/download/{documents_id}', array(
    'uses' => '\App\Http\Controllers\FilesController@downloadFile'
));*/
/*
Route::get ('project/listactivities/{id}/{isProjectAdmin}/{activity_id}', array (
    'middleware' => 'auth.login',
    'as' => 'project.listactivities',
    'uses' => '\App\Http\Controllers\ProjectController@projectActivities'
));

Route::get ('project/completedActivities/{id}/{isProjectAdmin}', array (
    'middleware' => 'auth.login',
    'as' => 'project.completedActivities',
    'uses' => '\App\Http\Controllers\ProjectController@completedActivities'
));

Route::get ('project/inProgressActivities/{id}/{isProjectAdmin}', array (
    'middleware' => 'auth.login',
    'as' => 'project.inProgressActivities',
    'uses' => '\App\Http\Controllers\ProjectController@inProgressActivities'
));

Route::get ('project/notRequiredActivities/{id}/{isProjectAdmin}', array (
    'middleware' => 'auth.login',
    'as' => 'project.notRequiredActivities',
    'uses' => '\App\Http\Controllers\ProjectController@notRequiredActivities'
));

Route::get ('project/yetToStartActivities/{id}/{isProjectAdmin}', array (
    'middleware' => 'auth.login',
    'as' => 'project.yetToStartActivities',
    'uses' => '\App\Http\Controllers\ProjectController@yetToStartActivities'
));

Route::get ('project/delayActivities/{id}/{isProjectAdmin}', array (
    'middleware' => 'auth.login',
    'as' => 'project.delayActivities',
    'uses' => '\App\Http\Controllers\ProjectController@delayActivities'
));

Route::get ('project/dashboard', array (
    'middleware' => 'auth.login',
    'as' => 'project.dashboard',
    'uses' => '\App\Http\Controllers\ProjectController@dashboard'
));

Route::post ('/project/filterDashboard', array (
    'middleware' => 'auth.login',
    'as' => 'project.filterDashboard',
    'uses' => '\App\Http\Controllers\ProjectController@filterDashboard'
));

Route::post ('admin/project/updatestatus/{activity_id}/{isProjectAdmin}', array (
    'middleware' => 'auth.login',
    'as' => 'admin.project.updatestatus',
    'uses' => '\App\Http\Controllers\ProjectController@updatestatus'
));

Route::get('/ajax/getRegions', array(
    'middleware' => 'auth',
    'as' => 'project.getRegions',
    'uses' =>'\App\Http\Controllers\ProjectController@getRegions'
));

Route::get('ajax/getCountries', array(
    'middleware' => 'auth',
    'as' => 'project.getCountries',
    'uses' =>'\App\Http\Controllers\ProjectController@getCountries'
));

Route::get('ajax/getShippingList', array(
    'middleware' => 'auth',
    'as' => 'project.getShippingList',
    'uses' =>'\App\Http\Controllers\ProjectController@getShippingList'
));

Route::get('ajax/getLOB', array(
    'middleware' => 'auth',
    'as' => 'project.getLOB',
    'uses' =>'\App\Http\Controllers\ProjectController@getLob'
));

Route::get('ajax/getPPL', array(
    'middleware' => 'auth',
    'as' => 'project.getPPL',
    'uses' =>'\App\Http\Controllers\ProjectController@getModels'
));

Route::get('ajax/getProjectName', array(
    'middleware' => 'auth',
    'as' => 'project.getProjectName',
    'uses' =>'\App\Http\Controllers\ProjectController@getProjectName'
));*/
/*

// Password reset link request routes...
Route::get('password/email', '\App\Http\Controllers\Auth\PasswordController@getEmail');
Route::post('password/email', '\App\Http\Controllers\Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', '\App\Http\Controllers\Auth\PasswordController@getReset');
Route::post('password/reset-custom', '\App\Http\Controllers\Auth\PasswordController@postResetCustom');

//Route::get('/',array(
//    'as' => 'login.home',
//    'uses' => '\App\Http\Controllers\LoginController@index'
//));

Route::get('/logout',array(
    'as' => 'login.logout',
    'uses' => '\App\Http\Controllers\LoginController@logout'
));


// Emil Notifications

Route::get('emails/', array(
    'middleware' => 'auth.login',
    'as' => 'email.index',
    'uses' => '\App\Http\Controllers\EmailNotificationsController@index'
));

Route::post('emails/', array(
    'middleware' => 'auth.login',
    'as' => 'email.getmails',
    'uses' => '\App\Http\Controllers\EmailNotificationsController@index'
));

Route::post('emails/selectedmailSend/', array(
    'middleware' => 'auth.login',
    'as' => 'email.selectedmailSend',
    'uses' => '\App\Http\Controllers\EmailNotificationsController@selectedMailSend'
));

Route::get('emails/show/{id}', array(
    'middleware' => 'auth.login',
    'as' => 'email.show',
    'uses' => '\App\Http\Controllers\EmailNotificationsController@show'
));

Route::get('emails/mailsend/{id}', array(
    'middleware' => 'auth.login',
    'as' => 'email.mailSend',
    'uses' => '\App\Http\Controllers\EmailNotificationsController@mailSend'
));*/


// sended mails details

/*

Route::get('sendedemails/', array(
    'middleware' => 'auth.login',
    'as' => 'email.sendedemails',
    'uses' => '\App\Http\Controllers\EmailNotificationsController@sendedEmails'
));

Route::get('emails/showlog/{id}', array(
    'middleware' => 'auth.login',
    'as' => 'email.showLog',
    'uses' => '\App\Http\Controllers\EmailNotificationsController@showLog'
));



Route::get('emails/mailsendlog/{id}', array(
    'middleware' => 'auth.login',
    'as' => 'email.mailSendLog',
    'uses' => '\App\Http\Controllers\EmailNotificationsController@mailSendLog'
));
*/

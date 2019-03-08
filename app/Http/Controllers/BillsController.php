<?php

namespace App\Http\Controllers;

use App\Bills;
use App\BillsDoc;
use App\BillsEffort;
use App\CandidateBasicInfo;
use App\ClientBasicinfo;
use App\JobOpen;
use App\JobCandidateJoiningdate;
use Illuminate\Http\Request;
use App\Date;
use App\User;
use Illuminate\Support\Facades\Input;
use Excel;
use App\Utils;
use App\Events\NotificationMail;
use App\BillsLeadEfforts;

class BillsController extends Controller
{
    public function index()
    {
        $cancel_bill = 0;

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $accountant_role_id = env('ACCOUNTANT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $count = Bills::getAllBillsCount(0,1,$user_id);
            $access = true;
        }
        else{
            $count = Bills::getAllBillsCount(0,0,$user_id);
            $access = false;
        }

        $title = "Forecasting";
        return view('adminlte::bills.index', compact('bnm','access','user_id','title','isSuperAdmin','isAccountant','count','cancel_bill'));
    }

    public function getForecastingOrderColumnName($order,$admin){
        $order_column_name = '';
        if($admin){
            if (isset($order) && $order >= 0) {
                if ($order == 2) {
                    $order_column_name = "bills.id";
                }
                else if ($order == 3) {
                    $order_column_name = "users.name";
                }
                else if ($order == 4) {
                    $order_column_name = "bills.company_name";
                }
                else if ($order == 5) {
                    $order_column_name = "candidate_basicinfo.full_name";
                }
                else if ($order == 6) {
                    $order_column_name = "bills.date_of_joining";
                }
                else if ($order == 7) {
                    $order_column_name = "bills.fixed_salary";
                }
                else if ($order == 8) {
                    $order_column_name = "users.name";
                }
                else if ($order == 9) {
                    $order_column_name = "candidate_basicinfo.mobile";
                }
            }
        }
        else{
            if (isset($order) && $order >= 0) {
                if ($order == 2) {
                    $order_column_name = "bills.id";
                }
                else if ($order == 3) {
                    $order_column_name = "bills.company_name";
                }
                else if ($order == 4) {
                    $order_column_name = "candidate_basicinfo.full_name";
                }
                else if ($order == 5) {
                    $order_column_name = "bills.date_of_joining";
                }
                else if ($order == 6) {
                    $order_column_name = "bills.fixed_salary";
                }
                else if ($order == 7) {
                    $order_column_name = "users.name";
                }
                else if ($order == 8) {
                    $order_column_name = "candidate_basicinfo.mobile";
                }
            }
        }

        return $order_column_name;
    }

    // Index using ajax call
    public function getAllBillsDetails(){

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        $title = $_GET['title'];

        $cancel_bill = 0;

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $accountant_role_id = env('ACCOUNTANT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);
        $isManager = $user_obj::isManager($role_id);

        if ($title == 'Forecasting') {
            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
            if(in_array($user_role_id,$access_roles_id)){
                $order_column_name = self::getForecastingOrderColumnName($order,1);
                $bnm = Bills::getAllBills(0,1,$user_id,$limit,$offset,$search,$order_column_name,$type);
                $count = Bills::getAllBillsCount(0,1,$user_id,$search);
                $access = true;
            }
            else{
                $order_column_name = self::getForecastingOrderColumnName($order,0);
                $bnm = Bills::getAllBills(0,0,$user_id,$limit,$offset,$search,$order_column_name,$type);
                $count = Bills::getAllBillsCount(0,0,$user_id,$search);
                $access = false;
            }
        }
        else if($title == 'Recovery'){
            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
            if(in_array($user_role_id,$access_roles_id)){
                $order_column_name = self::getForecastingOrderColumnName($order,1);
                $bnm = Bills::getAllBills(1,1,$user_id,$limit,$offset,$search,$order_column_name,$type);
                $count = Bills::getAllBillsCount(1,1,$user_id,$search);
                $access = true;
            }
            else{
                $order_column_name = self::getForecastingOrderColumnName($order,0);
                $bnm = Bills::getAllBills(1,0,$user_id,$limit,$offset,$search,$order_column_name,$type);
                $count = Bills::getAllBillsCount(1,0,$user_id,$search);
                $access = false;
            }
        }

        $forecasting = array();
        $i = 0;$j = 0;
        foreach ($bnm as $key => $value) {
            $action = '';
            $checkbox = '';
            if ($title == 'Forecasting') {
                if($access || ($user_id==$value['uploaded_by'])) {
                    $action .= '<a title="Edit" class="fa fa-edit" href="'.route('forecasting.edit',$value['id']).'" style="margin:2px;"></a>';
                    $action .= '<a title="show" class="fa fa-circle" href="'.route('forecasting.show',$value['id']).'" style="margin:2px;"></a>';
                    if($isSuperAdmin) {
                        $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $delete = $delete_view->render();
                        $action .= $delete;
                    }
                    if($value['cancel_bill']==0) {
                        $cancel_view = \View::make('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $cancel = $cancel_view->render();
                        $action .= $cancel;
                    }
                    if($value['status']==0 && $value['cancel_bill']!=1){
                        //BM will be generated after date of joining
                        if(date("Y-m-d")>= date("Y-m-d",strtotime($value['date_of_joining']))) {
                            $action .= '<a title="Generate Recovery" class="fa fa-square" href="'.route('bills.generaterecovery',$value['id']).'" style="margin:2px;"></a>';
                        }
                    }
                }
                if($isSuperAdmin || $isAccountant) {
                    if($value['cancel_bill']==1){
                        $relive_view = \View::make('adminlte::partials.relivebill', ['data' => $value, 'name' => 'recovery','display_name'=>'Forcasting']);
                        $relive = $relive_view->render();
                        $action .= $relive;
                    }
                }
            }
            else if ($title == 'Recovery') {
                if($access || ($user_id==$value['uploaded_by'])) {
                    $action .= '<a title="Edit" class="fa fa-edit" href="'.route('forecasting.edit',$value['id']).'" style="margin:2px;"></a>';
                    if($isSuperAdmin) {
                        $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $delete = $delete_view->render();
                        $action .= $delete;
                    }
                    if($value['cancel_bill']==0) {
                        $cancel_view = \View::make('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $cancel = $cancel_view->render();
                        $action .= $cancel;
                    }
                    if($isSuperAdmin || $isAccountant){
                        if($value['job_confirmation'] == 0 && $value['cancel_bill']==0){
                            $job_confirmation = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.sendconfirmationmail', 'class' => 'fa fa-send', 'title' => 'Send Confirmation Mail', 'model_title' => 'Send Confirmation Mail', 'model_body' => 'want to Send Confirmation Mail?']);
                            $job_con = $job_confirmation->render();
                            $action .= $job_con;
                        }
                        else if($value['job_confirmation'] == 1 && $value['cancel_bill']==0){
                            $got_confirmation = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.gotconfirmation', 'class' => 'fa fa-check-circle', 'title' => 'Got Confirmation', 'model_title' => 'Got Confirmation Mail', 'model_body' => 'you Got Confirmation Mail?']);
                            $got_con = $got_confirmation->render();
                            $action .= $got_con;
                        }
                        else if($value['job_confirmation'] == 2 && $value['cancel_bill']==0){
                            $invoice_generate = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.invoicegenerate', 'class' => 'fa fa-file', 'title' => 'Generate Invoice', 'model_title' => 'Generate Invoice', 'model_body' => 'want to Generate Invoice?']);
                            $invoice = $invoice_generate->render();
                            $action .= $invoice;
                        }
                        else if($value['job_confirmation'] == 3 && $value['cancel_bill']==0){
                            $payment_received = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.paymentreceived', 'class' => 'fa fa-money', 'title' => 'Payment Received', 'model_title' => 'Payment Received', 'model_body' => 'received Payment?']);
                            $payment = $payment_received->render();
                            $action .= $payment;
                        }
                        if(isset($value['invoice_url']) && $value['invoice_url'] != NULL){
                            $action .= '<a target="_blank" href="'.$value['invoice_url'].'" style="margin:2px;"><i  class="fa fa-fw fa-download"></i></a>';
                        }
                    }
                }
                if($isSuperAdmin || $isAccountant) {
                    if($value['cancel_bill']==1){
                        $relive_view = \View::make('adminlte::partials.relivebill', ['data' => $value, 'name' => 'recovery','display_name'=>'Forcasting']);
                        $relive = $relive_view->render();
                        $action .= $relive;
                    }
                }
            }
            $checkbox .= '<input type=checkbox name=id[] value='.$value['id'].'/>';

            if($access=='true'){
                $user_name = '<a style="color:black; text-decoration:none;">'.$value['user_name'].'</a>';
            }
            $job_opening = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'-'.$value['posting_title'].','.$value['city'].'</a>';
            $joining_date = '<a style="color:black; text-decoration:none; data-th=Lastrun data-order='.$value['date_of_joining_ts'].'">'.$value['date_of_joining'].'</a>';
            if($isSuperAdmin || $isAccountant || $isManager) {
                $percentage_charged = '<a style="color:black; text-decoration:none;">'.$value['percentage_charged'].'</a>';
                $lead_efforts = '<a style="color:black; text-decoration:none;">'.$value['lead_efforts'].'</a>';

                $data = array($checkbox,$action,++$j,$user_name,$job_opening,$value['cname'],$joining_date,$value['fixed_salary'],$value['efforts'],$value['candidate_contact_number'],$value['job_location'],$percentage_charged,$value['source'],$value['client_name'],$value['client_contact_number'],$value['client_email_id'],$lead_efforts,$value['job_confirmation']);
            }
            else {
                $data = array($checkbox,$action,++$j,$job_opening,$value['cname'],$joining_date,$value['fixed_salary'],$value['efforts'],$value['candidate_contact_number'],$value['job_location'],$value['source'],$value['client_name'],$value['client_contact_number'],$value['client_email_id']);
            }

            $forecasting[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $forecasting
        );

        echo json_encode($json_data);exit;
    }

    public function cancelbnm(){

        $cancel_bill = 1;
        $cancel_bnm = 1;
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $accountant_role_id = env('ACCOUNTANT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $count = Bills::getAllCancelBillsCount(0,1,$user_id);
            $access = true;
        }
        else{
            $count = Bills::getAllCancelBillsCount(0,0,$user_id);
            $access = false;
        }

        $title = "Cancel Forecasting";
        return view('adminlte::bills.index', compact('bnm','access','user_id','title','isSuperAdmin','isAccountant','count','cancel_bill','cancel_bnm'));
    }
    // for cancel bills get using ajax
    public function getAllCancelBillsDetails(){

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        $title = $_GET['title'];

        $cancel_bill = 0;

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $accountant_role_id = env('ACCOUNTANT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        if ($title == 'Cancel Forecasting') {
            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
            if(in_array($user_role_id,$access_roles_id)){
                $order_column_name = self::getForecastingOrderColumnName($order,1);
                $bnm = Bills::getCancelBills(0,1,$user_id,$limit,$offset,$search,$order_column_name,$type);
                $count = Bills::getAllCancelBillsCount(0,1,$user_id,$search);
                $access = true;
            }
            else{
                $order_column_name = self::getForecastingOrderColumnName($order,0);
                $bnm = Bills::getCancelBills(0,0,$user_id,$limit,$offset,$search,$order_column_name,$type);
                $count = Bills::getAllCancelBillsCount(0,0,$user_id,$search);
                $access = false;
            }
        }
        else if($title == 'Cancel Recovery'){
            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
            if(in_array($user_role_id,$access_roles_id)){
                $order_column_name = self::getForecastingOrderColumnName($order,1);
                $bnm = Bills::getCancelBills(1,1,$user_id,$limit,$offset,$search,$order_column_name,$type);
                $count = Bills::getAllCancelBillsCount(1,1,$user_id,$search);
                $access = true;
            }
            else{
                $order_column_name = self::getForecastingOrderColumnName($order,0);
                $bnm = Bills::getCancelBills(1,0,$user_id,$limit,$offset,$search,$order_column_name,$type);
                $count = Bills::getAllCancelBillsCount(1,0,$user_id,$search);
                $access = false;
            }
        }

        $forecasting = array();
        $i = 0;$j = 0;
        foreach ($bnm as $key => $value) {
            $action = '';
            $checkbox = '';
            if ($title == 'Cancel Forecasting') {
                if($access || ($user_id==$value['uploaded_by'])) {
                    $action .= '<a title="Edit" class="fa fa-edit" href="'.route('forecasting.edit',$value['id']).'" style="margin:2px;"></a>';
                    $action .= '<a title="show" class="fa fa-circle" href="'.route('forecasting.show',$value['id']).'" style="margin:2px;"></a>';
                    if($isSuperAdmin) {
                        $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $delete = $delete_view->render();
                        $action .= $delete;
                    }
                    if($value['cancel_bill']==0) {
                        $cancel_view = \View::make('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $cancel = $cancel_view->render();
                        $action .= $cancel;
                    }
                    if($value['status']==0 && $value['cancel_bill']!=1){
                        //BM will be generated after date of joining
                        if(date("Y-m-d")>= date("Y-m-d",strtotime($value['date_of_joining']))) {
                            $action .= '<a title="Generate Recovery" class="fa fa-square" href="'.route('bills.generaterecovery',$value['id']).'" style="margin:2px;"></a>';
                        }
                    }
                }
                if($isSuperAdmin || $isAccountant) {
                    if($value['cancel_bill']==1){
                        $relive_view = \View::make('adminlte::partials.relivebill', ['data' => $value, 'name' => 'recovery','display_name'=>'Forcasting']);
                        $relive = $relive_view->render();
                        $action .= $relive;
                    }
                }
            }
            else if ($title == 'Cancel Recovery') {
                if($access || ($user_id==$value['uploaded_by'])) {
                    $action .= '<a title="Edit" class="fa fa-edit" href="'.route('forecasting.edit',$value['id']).'" style="margin:2px;"></a>';
                    if($isSuperAdmin) {
                        $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $delete = $delete_view->render();
                        $action .= $delete;
                    }
                    if($value['cancel_bill']==0) {
                        $cancel_view = \View::make('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $cancel = $cancel_view->render();
                        $action .= $cancel;
                    }
                    if($isSuperAdmin || $isAccountant){
                        if($value['job_confirmation'] == 0 && $value['cancel_bill']==0){
                            $job_confirmation = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.sendconfirmationmail', 'class' => 'fa fa-send', 'title' => 'Send Confirmation Mail', 'model_title' => 'Send Confirmation Mail', 'model_body' => 'want to Send Confirmation Mail?']);
                            $job_con = $job_confirmation->render();
                            $action .= $job_con;
                        }
                        else if($value['job_confirmation'] == 1 && $value['cancel_bill']==0){
                            $got_confirmation = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.gotconfirmation', 'class' => 'fa fa-check-circle', 'title' => 'Got Confirmation', 'model_title' => 'Got Confirmation Mail', 'model_body' => 'you Got Confirmation Mail?']);
                            $got_con = $got_confirmation->render();
                            $action .= $got_con;
                        }
                        else if($value['job_confirmation'] == 2 && $value['cancel_bill']==0){
                            $invoice_generate = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.invoicegenerate', 'class' => 'fa fa-file', 'title' => 'Generate Invoice', 'model_title' => 'Generate Invoice', 'model_body' => 'want to Generate Invoice?']);
                            $invoice = $invoice_generate->render();
                            $action .= $invoice;
                        }
                        else if($value['job_confirmation'] == 3 && $value['cancel_bill']==0){
                            $payment_received = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.paymentreceived', 'class' => 'fa fa-money', 'title' => 'Payment Received', 'model_title' => 'Payment Received', 'model_body' => 'received Payment?']);
                            $payment = $payment_received->render();
                            $action .= $payment;
                        }
                        if(isset($value['invoice_url']) && $value['invoice_url'] != NULL){
                            $action .= '<a target="_blank" href="'.$value['invoice_url'].'" style="margin:2px;"><i  class="fa fa-fw fa-download"></i></a>';
                        }
                    }
                }
                if($isSuperAdmin || $isAccountant) {
                    if($value['cancel_bill']==1){
                        $relive_view = \View::make('adminlte::partials.relivebill', ['data' => $value, 'name' => 'recovery','display_name'=>'Forcasting']);
                        $relive = $relive_view->render();
                        $action .= $relive;
                    }
                }
            }
            $checkbox .= '<input type=checkbox name=id[] value='.$value['id'].'/>';

            if($access=='true'){
                $user_name = '<a style="color:black; text-decoration:none;">'.$value['user_name'].'</a>';
            }
            $job_opening = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'-'.$value['posting_title'].','.$value['city'].'</a>';
            $joining_date = '<a style="color:black; text-decoration:none; data-th=Lastrun data-order='.$value['date_of_joining_ts'].'">'.$value['date_of_joining'].'</a>';
            if($isSuperAdmin || $isAccountant) {
                $percentage_charged = '<a style="color:black; text-decoration:none;">'.$value['percentage_charged'].'</a>';
                $lead_efforts = '<a style="color:black; text-decoration:none;">'.$value['lead_efforts'].'</a>';

                $data = array($checkbox,$action,++$j,$user_name,$job_opening,$value['cname'],$joining_date,$value['fixed_salary'],$value['efforts'],$value['candidate_contact_number'],$value['job_location'],$percentage_charged,$value['source'],$value['client_name'],$value['client_contact_number'],$value['client_email_id'],$lead_efforts,$value['job_confirmation']);
            }
            else {
                $data = array($checkbox,$action,++$j,$job_opening,$value['cname'],$joining_date,$value['fixed_salary'],$value['efforts'],$value['candidate_contact_number'],$value['job_location'],$value['source'],$value['client_name'],$value['client_contact_number'],$value['client_email_id']);
            }

            $forecasting[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $forecasting
        );

        echo json_encode($json_data);exit;
    }

    public function billsMade(){
       // $bnm = Bills::getAllBills(1);
        $cancel_bill = 0;
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $accountant_role_id = env('ACCOUNTANT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $count = Bills::getAllBillsCount(1,1,$user_id);
            $access = true;
        }
        else{
            $count = Bills::getAllBillsCount(1,0,$user_id);
            $access = false;
        }

        $title = "Recovery";
        return view('adminlte::bills.index', compact('bnm','access','user_id','title','isSuperAdmin','isAccountant','count','cancel_bill'));

    }

    public function cancelbm(){
       // $bnm = Bills::getAllBills(1);
        $cancel_bill = 1;
        $cancel_bnm = 0;
        $cancel_bn = 1;
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $accountant_role_id = env('ACCOUNTANT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $count = Bills::getAllCancelBillsCount(1,1,$user_id);
            $access = true;
        }
        else{
            $count = Bills::getAllCancelBillsCount(1,0,$user_id);
            $access = false;
        }

        $title = "Cancel Recovery";
        return view('adminlte::bills.index', compact('bnm','access','user_id','title','isSuperAdmin','isAccountant','count','cancel_bill','cancel_bnm','cancel_bn'));

    }

    public function create()
    {
        $action = 'add';
        $generate_bm = '0';
        $status = '0';

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllBillsJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllBillsJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['posting_title']." - ".$v['company_name']." ,".$v['location'];
        }
        $job_id = 0;

        $users = User::getAllUsersCopy('recruiter');
        //print_r($users);exit;

        $employee_name = array();
        $employee_percentage = array();

        $employee_name[0] = $user_id;
        $employee_percentage[0] = '0';
        for ($i = 1; $i < 5; $i++) {
            $employee_name[$i] = '';
            $employee_percentage[$i] = '';
        }
        $lead_name = $user_id;
        $lead_percentage = 0;

        $candidate_id = '';
        $candidateSource = CandidateBasicInfo::getCandidateSourceArrayByName();
        return view('adminlte::bills.create', compact('action','generate_bm','jobopen','job_id','users','employee_name','employee_percentage','candidate_id','candidateSource','status','isSuperAdmin','isAccountant','lead_name','lead_percentage'));
    }

    public function store(Request $request)
    {

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        $upload_documents = $request->file('upload_documents');

        $input = $request->all();

        $job_id = $input['jobopen'];
        $candidate_id = $input['candidate_name'];
        $company_name = $input['company_name'];
        $candidate_contact_number = $input['candidate_contact_number'];
        $date_of_joining = $input['date_of_joining'];
        $fixed_salary = $input['fixed_salary'];
        $source = $input['source'];
        $client_contact_number = $input['client_contact_number'];
        $candidate_name = $input['candidate_name'];
        $designation_offered = $input['designation_offered'];
        $job_location = $input['job_location'];
        //$percentage_charged = $input['percentage_charged'];
        $client_name = $input['client_name'];
        $client_email_id = $input['client_email_id'];
        $address_of_communication = $input['address_of_communication'];

        if(isset($input['percentage_charged']) && $input['percentage_charged']!='')
            $percentage_charged = $input['percentage_charged'];
        else
            $percentage_charged = '';

        $employee_name = array();
        $employee_final = array();
        $employee_percentage = array();

        $employee_name[] = $input['employee_name_1'];
        $employee_name[] = $input['employee_name_2'];
        $employee_name[] = $input['employee_name_3'];
        $employee_name[] = $input['employee_name_4'];
        $employee_name[] = $input['employee_name_5'];

        $employee_percentage[] = $input['employee_percentage_1'];
        $employee_percentage[] = $input['employee_percentage_2'];
        $employee_percentage[] = $input['employee_percentage_3'];
        $employee_percentage[] = $input['employee_percentage_4'];
        $employee_percentage[] = $input['employee_percentage_5'];
        $total = 0;
        foreach ($employee_name as $k => $v) {
            if ($v != '' && $v!=0) {
                $employee_final[$v] = $employee_percentage[$k];
                $total += $employee_percentage[$k];
            }
        }

       if($total>100){
           return redirect('forecasting/create')->withInput(Input::all())->with('error','Total percentage of efforts should be less than or equal to 100');
       }

        if(isset($input['lead_name']) && $input['lead_name']!=''){
            $lead_name = $input['lead_name'];
        }
        else{
            $lead_name = '';
        }
        if (isset($input['lead_percentage']) && $input['lead_percentage']!='') {
            $lead_percentage = $input['lead_percentage'];
        }
        else {
            $lead_percentage = '';
        }

        //echo $dateClass->changeDMYtoYMD($date_of_joining);exit;
        $bill = new Bills();

        $bill->receipt_no = 'xyz';
        $bill->company_name = $company_name;
        $bill->candidate_contact_number = $candidate_contact_number;
        $bill->date_of_joining = $dateClass->changeDMYtoYMD($date_of_joining);
        $bill->fixed_salary = $fixed_salary;
        $bill->source = $source;
        $bill->client_contact_number = $client_contact_number;
        $bill->candidate_name = $candidate_name;
        $bill->designation_offered = $designation_offered;
        $bill->job_location = $job_location;
        if(isset($percentage_charged) && $percentage_charged!='')
            $bill->percentage_charged = $percentage_charged;
        else
            $bill->percentage_charged = 0;

        $bill->client_name = $client_name;
        $bill->client_email_id = $client_email_id;
        $bill->address_of_communication = $address_of_communication;
        $bill->status = 0; // 0- BNM
        $bill->remarks = '';
        $bill->uploaded_by = $user_id;
        $bill->job_id = $job_id;
        $bill->candidate_id = $candidate_id;

        $validator = \Validator::make(Input::all(),$bill::$rules);

        if($validator->fails()){
            return redirect('forecasting/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $bill_response = $bill->save();

        if ($bill_response) {
            $bill_id = $bill->id;

            foreach ($employee_final as $k => $v) {
                $bill_efforts = new BillsEffort();

                $bill_efforts->bill_id = $bill_id;
                $bill_efforts->employee_name = $k;
                $bill_efforts->employee_percentage = $v;

                $bill_efforts->save();
            }
            if (isset($lead_name) && $lead_name != '' && isset($lead_percentage) && $lead_percentage != '') {
                $bill_lead_efforts = new BillsLeadEfforts();
                $bill_lead_efforts->bill_id = $bill_id;
                $bill_lead_efforts->employee_name = $lead_name;
                $bill_lead_efforts->employee_percentage = $lead_percentage;
                $bill_lead_efforts->save();
            }

            if (isset($upload_documents) && sizeof($upload_documents) > 0) {
                foreach ($upload_documents as $k => $v) {
                    if (isset($v) && $v->isValid()) {
                        // echo "here";
                        $file_name = $v->getClientOriginalName();
                        $file_extension = $v->getClientOriginalExtension();
                        $file_realpath = $v->getRealPath();
                        $file_size = $v->getSize();

                        //$extention = File::extension($file_name);

                        $dir = 'uploads/bills/' . $bill_id . '/';

                        if (!file_exists($dir) && !is_dir($dir)) {
                            mkdir($dir, 0777, true);
                            chmod($dir, 0777);
                        }
                        $v->move($dir, $file_name);

                        $file_path = $dir . $file_name;

                        $bills_doc = new BillsDoc();
                        $bills_doc->bill_id = $bill_id;
                        $bills_doc->file = $file_path;
                        $bills_doc->name = $file_name;
                        $bills_doc->size = $file_size;
                        $bills_doc->created_at = date('Y-m-d');
                        $bills_doc->updated_at = date('Y-m-d');

                        $bills_doc->save();
                    }

                }
            }

        }

        JobCandidateJoiningdate::where('job_id','=',$job_id)->where('candidate_id','=',$candidate_id)->delete();

        $candidatejoindate = new JobCandidateJoiningdate();
        $candidatejoindate->job_id = $job_id;
        $candidatejoindate->candidate_id = $candidate_id;
        $candidatejoindate->joining_date = $dateClass->changeDMYtoYMD($date_of_joining);
        $candidatejoindate->fixed_salary = $fixed_salary;
        $candidatejoindate->save();

        // For forcasting mail [email_notification table entry every minute check]
        $user_email = \Auth::user()->email;
        $superadminuserid = getenv('SUPERADMINUSERID');
        $accountantuserid = getenv('ACCOUNTANTUSERID');

        $superadminemail = User::getUserEmailById($superadminuserid);
        $accountantemail = User::getUserEmailById($accountantuserid);

        $cc_users_array = array($superadminemail,$accountantemail);

        $c_name = CandidateBasicInfo::getCandidateNameById($candidate_name);

        $module = "Forecasting";
        $sender_name = $user_id;
        $to = $user_email;

        $cc_users_array = array_filter($cc_users_array);
        $cc = implode(",",$cc_users_array);
        
        $subject = "Forecasting - " . $c_name;
        $message = "Forecasting - " . $c_name;
        $module_id = $bill_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('forecasting.index')->with('success', 'Bills Created Successfully');
    }

    public function show($id){

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

       $viewVariable = Bills::getShowBill($id);

       return view('adminlte::bills.show', $viewVariable,compact('isSuperAdmin','isAccountant'));
    }

    public function edit($id)
    {
        $action = 'edit';
        $generate_bm ='0';
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllBillsJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllBillsJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['posting_title']." - ".$v['company_name'];
        }
        
        $bnm = Bills::find($id);
        //print_r($bnm);exit;

        $dateClass = new Date();
        $doj = $dateClass->changeYMDtoDMY($bnm->date_of_joining);
        $job_id = $bnm->
        $action = 'edit';
        $status = $bnm->status;

        if($status == 1){
            $generate_bm = '1';
        }


        $employee_name = array();
        $employee_percentage = array();
        for ($i = 0; $i < 5; $i++) {
            $employee_name[$i] = '';
            $employee_percentage[$i] = '';
        }

        $efforts = Bills::getEmployeeEffortsById($id);

        // set employee name and percentage
        $i = 0;
        if (isset($efforts) && sizeof($efforts) > 0) {
            foreach ($efforts as $k => $v) {
                $employee_name[$i] = $k;
                $employee_percentage[$i] = $v;
                $i++;
            }
        }

        $lead_efforts = BillsLeadEfforts::getLeadEmployeeEffortsById($id);
        if (isset($lead_efforts) && sizeof($lead_efforts)>0) {
            foreach ($lead_efforts as $key => $value) {
                $lead_name = $key;
                $lead_percentage = $value;
            }
        }
        else {
            $lead_name = $user_id;
            $lead_percentage = 0;
        }

        $job_id = $bnm->job_id;
        $candidate_id = $bnm->candidate_id;
        $users = User::getAllUsersCopy('recruiter');
        $candidateSource = CandidateBasicInfo::getCandidateSourceArrayByName();

            $i = 0;
            
            $billsdetails['files'] = array();
            $billsFiles = BillsDoc::select('bills_doc.*')
                ->where('bills_doc.bill_id',$id)
                ->get();
            $utils = new Utils();
            if(isset($billsFiles) && sizeof($billsFiles) > 0){
                foreach ($billsFiles as $billfile) {
                    $billsdetails['files'][$i]['id'] = $billfile->id;
                    $billsdetails['files'][$i]['fileName'] = $billfile->file;
                    $billsdetails['files'][$i]['url'] = "../../".$billfile->file;
                    $billsdetails['files'][$i]['name'] = $billfile->name ;
                    $billsdetails['files'][$i]['size'] = $utils->formatSizeUnits($billfile->size);

                    $i++;

                }
            }

        return view('adminlte::bills.edit', compact('bnm', 'action', 'employee_name', 'employee_percentage','generate_bm','doj','jobopen','job_id','users','candidate_id','candidateSource','billsdetails','id','status','isSuperAdmin','isAccountant','lead_name','lead_percentage'));

    }

    public function update(Request $request, $id)
    {
        $dateClass = new Date();
        $user_id = \Auth::user()->id;

        $input = $request->all();

        $job_id = $input['jobopen'];
        $company_name = $input['company_name'];
        $candidate_contact_number = $input['candidate_contact_number'];
        $date_of_joining = $input['date_of_joining'];
        $fixed_salary = $input['fixed_salary'];
        $source = $input['source'];
        $client_contact_number = $input['client_contact_number'];
        $candidate_id = $input['candidate_name'];
        $designation_offered = $input['designation_offered'];
        $job_location = $input['job_location'];
        //$percentage_charged = $input['percentage_charged'];
        $client_name = $input['client_name'];
        $client_email_id = $input['client_email_id'];
        $address_of_communication = $input['address_of_communication'];
        $generateBM = $input['generateBM'];
        $status=0;
        if($generateBM==1){
            $status = 1;
        }

        if(isset($input['percentage_charged']) && $input['percentage_charged']!='')
            $percentage_charged = $input['percentage_charged'];
        else
            $percentage_charged = '';

        $employee_name = array();
        $employee_final = array();
        $employee_percentage = array();

        $employee_name[] = $input['employee_name_1'];
        $employee_name[] = $input['employee_name_2'];
        $employee_name[] = $input['employee_name_3'];
        $employee_name[] = $input['employee_name_4'];
        $employee_name[] = $input['employee_name_5'];

        $employee_percentage[] = $input['employee_percentage_1'];
        $employee_percentage[] = $input['employee_percentage_2'];
        $employee_percentage[] = $input['employee_percentage_3'];
        $employee_percentage[] = $input['employee_percentage_4'];
        $employee_percentage[] = $input['employee_percentage_5'];
        $total = 0;
        foreach ($employee_name as $k => $v) {
            if ($v != '') {
                $employee_final[$v] = $employee_percentage[$k];
                $total += (int)$employee_percentage[$k];
            }
        }

        if($total>100){
            return redirect('forecasting/'.$id.'/edit')->withInput(Input::all())->with('error','Total percentage of efforts should be less than or equal to 100');
        }

        if(isset($input['lead_name']) && $input['lead_name']!=''){
            $lead_name = $input['lead_name'];
        }
        else{
            $lead_name = '';
        }
        if (isset($input['lead_percentage']) && $input['lead_percentage']!='') {
            $lead_percentage = $input['lead_percentage'];
        }
        else {
            $lead_percentage = '';
        }

        $bill = Bills::find($id);

        $uploaded_by = $bill->uploaded_by;
        //print_r($uploaded_by);exit;

        $bill->receipt_no = 'xyz';
        $bill->company_name = $company_name;
        $bill->candidate_contact_number = $candidate_contact_number;
        $bill->date_of_joining = $dateClass->changeDMYtoYMD($date_of_joining);
        $bill->fixed_salary = $fixed_salary;
        $bill->source = $source;
        $bill->client_contact_number = $client_contact_number;
        $bill->candidate_name = $candidate_id;
        $bill->designation_offered = $designation_offered;
        $bill->job_location = $job_location;
        //$bill->percentage_charged = $percentage_charged;
        $bill->client_name = $client_name;
        $bill->client_email_id = $client_email_id;
        $bill->address_of_communication = $address_of_communication;
        $bill->status = $status; // 0- BNM , 1- BM
        $bill->remarks = '';
        $bill->uploaded_by = $uploaded_by;
        $bill->job_id = $job_id;
        $bill->candidate_id = $candidate_id;

        if(isset($percentage_charged) && $percentage_charged!='')
            $bill->percentage_charged = $percentage_charged;
        else
            $bill->percentage_charged = 0;

        $validator = \Validator::make(Input::all(),$bill::$rules);

        if($validator->fails()){
            return redirect('forecasting/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }
        else{

            $bill_response = $bill->save();
            BillsEffort::where('bill_id','=',$id)->delete();
            foreach ($employee_final as $k => $v) {

                if($k>0){
                    $bill_efforts = new BillsEffort();

                    $bill_efforts->bill_id = $id;
                    $bill_efforts->employee_name = $k;
                    $bill_efforts->employee_percentage = $v;

                    $bill_efforts->save();
                }

            }

            if (isset($lead_name) && $lead_name != '' && isset($lead_percentage) && $lead_percentage != '') {

                BillsLeadEfforts::where('bill_id','=',$id)->delete();

                $bill_lead_efforts = new BillsLeadEfforts();
                $bill_lead_efforts->bill_id = $id;
                $bill_lead_efforts->employee_name = $lead_name;
                $bill_lead_efforts->employee_percentage = $lead_percentage;
                $bill_lead_efforts->save();
            }
        }
         $file = $request->file('file');
        if (isset($file) && $file->isValid()) {
            $file_name = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();
            $file_realpath = $file->getRealPath();
            $file_size = $file->getSize();
            $dir = 'uploads/bills/' . $id . '/';

            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $file->move($dir, $file_name);
            $file_path = $dir . $file_name;

            $bills_doc = new BillsDoc();
            $bills_doc->bill_id = $id;
            $bills_doc->file = $file_path;
            $bills_doc->name = $file_name;
            $bills_doc->size = $file_size;
            $bills_doc->created_at = date('Y-m-d');
            $bills_doc->updated_at = date('Y-m-d');

            $bills_doc->save();

            if ($status == 1) {
                return redirect('recovery/'.$id.'/generaterecovery');
            }
            else{
             return redirect('forecasting/'.$id.'/edit');
            }
        }

        JobCandidateJoiningdate::where('job_id','=',$job_id)->where('candidate_id','=',$candidate_id)->delete();

        $candidatejoindate = new JobCandidateJoiningdate();
        $candidatejoindate->job_id = $job_id;
        $candidatejoindate->candidate_id = $candidate_id;
        $candidatejoindate->joining_date = $dateClass->changeDMYtoYMD($date_of_joining);
        $candidatejoindate->fixed_salary = $fixed_salary;
        $candidatejoindate->save();

        if ($status == 1) {
            // For Recovery mail [email_notification table entry every minute check]
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);

            $cc_users_array = array($superadminemail,$accountantemail);

            $c_name = CandidateBasicInfo::getCandidateNameById($candidate_id);

            $module = "Recovery";
            $sender_name = $user_id;
            $to = $user_email;

            $cc_users_array = array_filter($cc_users_array);
            $cc = implode(",",$cc_users_array);
            
            $subject = "Recovery - ". $c_name;
            $message = "Recovery - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if($status == 1){
            return redirect()->route('bills.recovery')->with('success', 'Recovery Updated Successfully');
        }
        else{
            return redirect()->route('forecasting.index')->with('success', 'Forecasting Updated Successfully');
        }
    }

    public function delete($id){

        BillsEffort::where('bill_id',$id)->delete();
        BillsDoc::where('bill_id',$id)->delete();
        $todo = Bills::where('id',$id)->delete();

        return redirect()->route('forecasting.index')->with('success','Bill Deleted Successfully');

    }

    public function cancel($id){
        
        $cancel_bill =1;
        $bills = array();
        $bill = Bills::find($id);
        $bills['status'] = $bill->status;
        $bills['job_id'] = $bill->job_id;
        $bills['candidate_id'] = $bill->candidate_id;
        $bill->cancel_bill = $cancel_bill;
        $bill_cancel = $bill->save();

        //print_r($bill_cancel);exit;
        $candidate_join_delete = JobCandidateJoiningdate::where('job_id',$bills['job_id'])->where('candidate_id',$bills['candidate_id'])->delete();

        if ($bills['status'] == 1) {
            // For Cancel Recovery mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);

            $cc_users_array = array($superadminemail,$accountantemail);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Cancel Recovery";
            $sender_name = $user_id;
            $to = $user_email;

            $cc_users_array = array_filter($cc_users_array);
            $cc = implode(",",$cc_users_array);
            
            $subject = "Cancel Recovery - ". $c_name;
            $message = "Cancel Recovery - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
        else if ($bills['status'] == 0) {
            // For Cancel Forecasting mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);

            $cc_users_array = array($superadminemail,$accountantemail);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Cancel Forecasting";
            $sender_name = $user_id;
            $to = $user_email;

            $cc_users_array = array_filter($cc_users_array);
            $cc = implode(",",$cc_users_array);
            
            $subject = "Cancel Forecasting - ". $c_name;
            $message = "Cancel Forecasting - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if($bills['status'] == 1){
            return redirect()->route('bills.recovery')->with('success', 'Recovery Canceled Successfully');
        }
        else{
            return redirect()->route('forecasting.index')->with('success', 'Forecasting Canceled Successfully');
        }

    }

    public function reliveBill($id){

        $relive_bill = 0;
        $bills = array();
        $bill = Bills::find($id);
        $bills['status'] = $bill->status;
        $bills['job_id'] = $bill->job_id;
        $bills['candidate_id'] = $bill->candidate_id;
        $bills['joining_date'] = $bill->date_of_joining;
        $bills['fixed_salary'] = $bill->fixed_salary;
        $bill->cancel_bill = $relive_bill;
        $bill_cancel = $bill->save();
        //print_r($bills);exit;

        $candidatejoindate = new JobCandidateJoiningdate();
        $candidatejoindate->job_id = $bills['job_id'];
        $candidatejoindate->candidate_id = $bills['candidate_id'];
        $candidatejoindate->joining_date = $bills['joining_date'];
        $candidatejoindate->fixed_salary = $bills['fixed_salary'];
        $candidatejoindate->save();

        if ($bills['status'] == 1) {
            // For Relive Recovery mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);

            $cc_users_array = array($superadminemail,$accountantemail);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Relive Recovery";
            $sender_name = $user_id;

            $cc_users_array = array_filter($cc_users_array);
            $to = implode(",",$cc_users_array);
            $cc = $superadminemail;
            
            $subject = "Relive Recovery - ". $c_name;
            $message = "Relive Recovery - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
        else if ($bills['status'] == 0) {
            // For Relive Forecasting mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);

            $cc_users_array = array($superadminemail,$accountantemail);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Relive Forecasting";
            $sender_name = $user_id;

            $cc_users_array = array_filter($cc_users_array);
            $to = implode(",",$cc_users_array);
            $cc = $superadminemail;
            
            $subject = "Relive Forecasting - ". $c_name;
            $message = "Relive Forecasting - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if($bills['status'] == 1){
            return redirect()->route('bills.recovery')->with('success', 'Recovery Relived Successfully');
        }
        else{
            return redirect()->route('forecasting.index')->with('success', 'Forecasting Relived Successfully');
        }
    }

    public function attachmentsDestroy($id){

        $billFileDetails = BillsDoc::find($id);

        $billId =  $billFileDetails->bill_id;

        unlink($billFileDetails->file);

        $billFileDelete = BillsDoc::where('id',$id)->delete();

        //$billId = $_POST['id'];

        return redirect()->route('forecasting.show',[$billId])->with('success','Attachment deleted Successfully');
    }

    public function upload(Request $request){

        $user_id = \Auth::user()->id;
        $upload_documents = $request->upload_documents;
        $file = $request->file('file');
        $bill_id = $request->id;
       // print_r($bill_id);exit;

        if (isset($file) && $file->isValid()) {
            $file_name = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();
            $file_realpath = $file->getRealPath();
            $file_size = $file->getSize();
            $dir = 'uploads/bills/' . $bill_id . '/';

            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $file->move($dir, $file_name);
            $file_path = $dir . $file_name;

            $bills_doc = new BillsDoc();
            $bills_doc->bill_id = $bill_id;
            $bills_doc->file = $file_path;
            $bills_doc->name = $file_name;
            $bills_doc->size = $file_size;
            $bills_doc->created_at = date('Y-m-d');
            $bills_doc->updated_at = date('Y-m-d');

            $bills_doc->save();
        }
        return redirect()->route('forecasting.show',[$bill_id])->with('success','Attachment uploaded successfully');
    }

    public function generateBM($id){

        $generate_bm = '1';

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllBillsJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllBillsJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['posting_title']." - ".$v['company_name'];
        }

        $bnm = Bills::find($id);
        $status = $bnm->status;

        $action = 'edit';

        $employee_name = array();
        $employee_percentage = array();
        for ($i = 0; $i < 5; $i++) {
            $employee_name[$i] = '';
            $employee_percentage[$i] = '';
        }

        $efforts = Bills::getEmployeeEffortsById($id);

        // set employee name and percentage
        $i = 0;
        if (isset($efforts) && sizeof($efforts) > 0) {
            foreach ($efforts as $k => $v) {
                $employee_name[$i] = $k;
                $employee_percentage[$i] = $v;
                $i++;
            }
        }

        $lead_efforts = BillsLeadEfforts::getLeadEmployeeEffortsById($id);
        if (isset($lead_efforts) && sizeof($lead_efforts)>0) {
            foreach ($lead_efforts as $key => $value) {
                $lead_name = $key;
                $lead_percentage = $value;
            }
        }
        else{
            $lead_name = '';
            $lead_percentage = 0;
        }

        $job_id = $bnm->job_id;
        $candidate_id = $bnm->candidate_id;
        $users = User::getAllUsersCopyWithInactive('recruiter');
        $candidateSource = CandidateBasicInfo::getCandidateSourceArrayByName();

         $i = 0;
            
            $billsdetails['files'] = array();
            $billsFiles = BillsDoc::select('bills_doc.*')
                ->where('bills_doc.bill_id',$id)
                ->get();
            $utils = new Utils();
            if(isset($billsFiles) && sizeof($billsFiles) > 0){
                foreach ($billsFiles as $billfile) {
                    $billsdetails['files'][$i]['id'] = $billfile->id;
                    $billsdetails['files'][$i]['fileName'] = $billfile->file;
                    $billsdetails['files'][$i]['url'] = "../../".$billfile->file;
                    $billsdetails['files'][$i]['name'] = $billfile->name ;
                    $billsdetails['files'][$i]['size'] = $utils->formatSizeUnits($billfile->size);

                    $i++;

                }
            }

        return view('adminlte::bills.edit', compact('bnm', 'action', 'employee_name', 'employee_percentage','generate_bm','jobopen','job_id','candidate_id','users','candidateSource','billsdetails','status','isSuperAdmin','isAccountant','lead_name','lead_percentage'));

    }

    public function downloadExcel(){

        $ids = $_POST['ids'];

        $response = Bills::getBillsByIds($ids);

        ob_end_clean();

        ob_start();

        Excel::create('Laravel Excel', function($excel) use ($response){

            $excel->sheet('Excel sheet', function($sheet) use ($response) {

                $sheet->setOrientation('landscape');

            });

        })->export('xlsx');
        ob_flush();
        exit();

    }

    public function getClientInfo(){

        $job_id = $_GET['job_id'];

        // get client info
        $client = ClientBasicinfo::getClientInfoByJobId($job_id);

        echo json_encode($client);exit;

    }

    public function getCandidateInfo(){

        $job_id = $_GET['job_id'];

        // get candidate Info
        $response = array();
        $response['returnvalue'] = 'invalid';

        $candidate_data = CandidateBasicInfo::getCandidateInfoByJobId($job_id);

        if(isset($candidate_data) && sizeof($candidate_data)>0) {
            $response['returnvalue'] = 'valid';
            $response['data'] = $candidate_data;
        }

        echo json_encode($response);exit;

    }

    // Joining Confirmation Mail to SA & Acc
    public function getSendConfirmationMail($id){
        
        $user_id = \Auth::user()->id;
        //Logged in User Email Id
        $user_email = User::getUserEmailById($user_id);

        $superadmin_userid = getenv('SUPERADMINUSERID');
        $account_userid = getenv('ACCOUNTANTUSERID');
        if ($user_id == $superadmin_userid) {
            $accountantemail = User::getUserEmailById($account_userid);
            $cc_users_array[] = $accountantemail;
        }
        else if ($user_id == $account_userid) {
            $superadminemail = User::getUserEmailById($superadmin_userid);
            $cc_users_array[] = $superadminemail;
        }
        else {
            $superadminemail = User::getUserEmailById($superadmin_userid);
            $cc_users_array[] = $superadminemail;   
        }

        $join_mail = Bills::getJoinConfirmationMail($id);
        $candidate_name = $join_mail['candidate_name'];
        $candidate_id = $join_mail['candidate_id'];

        $cc_users_array = array_filter($cc_users_array);

        $module = "Joining Confirmation";
        $sender_name = $user_id;
        $to = $user_email;
        $cc = implode(",",$cc_users_array);
        $subject = "Joining Confirmation of - ". $candidate_name;
        $message = "Joining Confirmation of - ". $candidate_name;
        $module_id = $id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        \DB::statement("UPDATE bills SET joining_confirmation_mail = '1' where id=$id");

        return redirect('/recovery')->with('success','Joining Confirmation Mail Send Successfully');
    }

    // Got Confirmation Check
    public function getGotConfirmation($id){

        \DB::statement("UPDATE bills SET joining_confirmation_mail = '2' where id=$id");

        return redirect('/recovery')->with('success','Got Confirmation Successfully');
    }

    // Generate Invoice and send mail to SA & Acc
    public function getInvoiceGenerate($id){

        // Generate excel sheet and save at bill id location
        Excel::create($id.'_invoice', function($excel) {
            $excel->sheet('Sheet 1', function($sheet) {

                $bill_id = $_POST['id'];
                $invoice_data = Bills::getJoinConfirmationMail($bill_id);

                $sheet->loadView('adminlte::bills.sheet')->with('invoice_data', $invoice_data);
            });
        })->store('xls', public_path('uploads/bills/'.$id));
        
        $user_id = \Auth::user()->id;
        //Logged in User Email Id
        $user_email = User::getUserEmailById($user_id);

        $superadmin_userid = getenv('SUPERADMINUSERID');
        $account_userid = getenv('ACCOUNTANTUSERID');
        if ($user_id == $superadmin_userid) {
            $accountantemail = User::getUserEmailById($account_userid);
            $cc_users_array[] = $accountantemail;
        }
        else if ($user_id == $account_userid) {
            $superadminemail = User::getUserEmailById($superadmin_userid);
            $cc_users_array[] = $superadminemail;
        }
        else {
            $superadminemail = User::getUserEmailById($superadmin_userid);
            $cc_users_array[] = $superadminemail;   
        }

        $join_mail = Bills::getJoinConfirmationMail($id);
        $candidate_name = $join_mail['candidate_name'];
        $cc_users_array = array_filter($cc_users_array);

        $module = "Invoice Generate";
        $sender_name = $user_id;
        $to = $user_email;
        $cc = implode(",",$cc_users_array);
        $subject = "Generated Invoice of - ". $candidate_name;
        $message = "Generated Invoice of - ". $candidate_name;
        $module_id = $id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        \DB::statement("UPDATE bills SET joining_confirmation_mail = '3' where id=$id");

        return redirect('/recovery')->with('success','Invoice Generated and Mailed Successfully');
    }

    // Payment received or not
    public function getPaymentReceived($id){

        \DB::statement("UPDATE bills SET joining_confirmation_mail = '4' where id=$id");

        return redirect('/recovery')->with('success','Payment Received Successfully');
    }

    public function getExportSheet(){

        $invoice_data = Bills::getJoinConfirmationMail(24);
        return view('adminlte::bills.sheet',compact('invoice_data'));
    }
}
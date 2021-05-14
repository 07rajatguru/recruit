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
use App\BillDate;
//use PDF;

class BillsController extends Controller
{
    public function index() {

        $cancel_bill = 0;

        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-forecasting');
        $loggedin_perm = $user->can('display-forecasting-by-loggedin-user');
        $can_owner_perm = $user->can('display-forecasting-by-candidate-owner');

        if($all_perm) {
            $count = Bills::getAllBillsCount(0,1,$user_id);
            $access = true;
        }
        else if($loggedin_perm || $can_owner_perm) {
            $count = Bills::getAllBillsCount(0,0,$user_id);
            $access = false;
        }

        $title = "Forecasting";
        return view('adminlte::bills.index', compact('access','user_id','title','count','cancel_bill'));
    }

    public function getForecastingOrderColumnName($order,$admin) {

        $order_column_name = '';
        if($admin) {
            if (isset($order) && $order >= 0) {
                if ($order == 2) {
                    $order_column_name = "bills.id";
                }
                else if ($order == 3) {
                    $order_column_name = "users.name";
                }
                else if ($order == 4) {
                    $order_column_name = "client_basicinfo.display_name";
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
                    $order_column_name = "client_basicinfo.display_name";
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

    public function getAllBillsDetails() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        $title = $_GET['title'];

        $cancel_bill = 0;

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+1 year'));
        $year_array = array();
        $year_array[0] = "Select Year";

        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $next = $y+1;
            $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            
            $year = $_GET['year'];

            if (isset($year) && $year != 0) {
                $year_data = explode(", ", $year);
                $year1 = $year_data[0];
                $year2 = $year_data[1];
                $current_year = date('Y-m-d',strtotime("first day of $year1"));
                $next_year = date('Y-m-d',strtotime("last day of $year2"));
            }
            else {
                $year = NULL;
                $current_year = NULL;
                $next_year = NULL;    
            }
        }
        else{
            $year = NULL;
            $current_year = NULL;
            $next_year = NULL;
        }

        $user = \Auth::user();
        $user_id = $user->id;
        $all_forecasting_perm = $user->can('display-forecasting');
        $loggedin_forecasting_perm = $user->can('display-forecasting-by-loggedin-user');
        $can_owner_forecasting_perm = $user->can('display-forecasting-by-candidate-owner');
        $forecasting_delete_perm = $user->can('forecasting-delete');

        $all_recovery_perm = $user->can('display-recovery');
        $loggedin_recovery_perm = $user->can('display-recovery-by-loggedin-user');
        $can_owner_recovery_perm = $user->can('display-recovery-by-candidate-owner');
        $recovery_delete_perm = $user->can('recovery-delete');

        $generate_recovery_perm = $user->can('generate-recovery');
        $cancel_bill_perm = $user->can('cancel-bill');
        $joining_confirmation_perm = $user->can('send-joining-confirmation');

        if ($title == 'Forecasting') {
            
            if($all_forecasting_perm) {

                $order_column_name = self::getForecastingOrderColumnName($order,1);
                $bnm = Bills::getAllBills(0,1,$user_id,$limit,$offset,$search,$order_column_name,$type);
                $count = Bills::getAllBillsCount(0,1,$user_id,$search);
                $access = true;
            }
            else if($loggedin_forecasting_perm || $can_owner_forecasting_perm) {

                $order_column_name = self::getForecastingOrderColumnName($order,0);
                $bnm = Bills::getAllBills(0,0,$user_id,$limit,$offset,$search,$order_column_name,$type);
                $count = Bills::getAllBillsCount(0,0,$user_id,$search);
                $access = false;
            }
        }
        else if($title == 'Recovery') {

            if($all_recovery_perm) {

                $order_column_name = self::getForecastingOrderColumnName($order,1);
                $bnm = Bills::getAllBills(1,1,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
                $count = Bills::getAllBillsCount(1,1,$user_id,$search,$current_year,$next_year);

                // For display count in recovery header
                $recovery_bills = Bills::getAllBills(1,1,$user_id,0,0,0,0,'',$current_year,$next_year);
                $access = true;
            }
            else if($loggedin_recovery_perm || $can_owner_recovery_perm) {

                $order_column_name = self::getForecastingOrderColumnName($order,0);
                $bnm = Bills::getAllBills(1,0,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
                $count = Bills::getAllBillsCount(1,0,$user_id,$search,$current_year,$next_year);

                // For display count in recovery header
                $recovery_bills = Bills::getAllBills(1,0,$user_id,0,0,0,0,'',$current_year,$next_year);
                $access = false;
            }
        }

        $forecasting = array();
        $i = 0;$j = 0;
        foreach ($bnm as $key => $value) {

            $action = '';
            $checkbox = '';

            if ($title == 'Forecasting') {

                $action .= '<a title="show" class="fa fa-circle" href="'.route('forecasting.show',$value['id']).'" style="margin:2px;"></a>';

                if($access || ($user_id == $value['uploaded_by'])) {
                    
                    $action .= '<a title="Edit" class="fa fa-edit" href="'.route('forecasting.edit',$value['id']).'" style="margin:2px;"></a>';

                    if($generate_recovery_perm) {

                        if($value['status']==0 && $value['cancel_bill']!=1) {
                            //BM will be generated after date of joining
                            if(date("Y-m-d")>= date("Y-m-d",strtotime($value['date_of_joining']))) {
                                $action .= '<a title="Generate Recovery" class="fa fa-square" href="'.route('bills.generaterecovery',$value['id']).'" style="margin:2px;"></a>';
                            }
                        }
                    }

                    if($cancel_bill_perm) {

                        if($value['cancel_bill']==0) {
                            $cancel_view = \View::make('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                            $cancel = $cancel_view->render();
                            $action .= $cancel;
                        }
                    }

                    if($forecasting_delete_perm) {

                        $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $delete = $delete_view->render();
                        $action .= $delete;
                    }
                }
                if($cancel_bill_perm) {

                    if($value['cancel_bill'] == 1) {
                        $relive_view = \View::make('adminlte::partials.relivebill', ['data' => $value, 'name' => 'recovery','display_name'=>'Forcasting']);
                        $relive = $relive_view->render();
                        $action .= $relive;
                    }
                }
            }
            else if ($title == 'Recovery') {

                if($access || ($user_id == $value['uploaded_by'])) {

                    $action .= '<a title="Edit" class="fa fa-edit" href="'.route('forecasting.edit',$value['id']).'" style="margin:2px;"></a>';

                    if($cancel_bill_perm) {

                        if($value['cancel_bill'] == 0) {

                            $cancel_view = \View::make('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill','year' => $year]);
                            $cancel = $cancel_view->render();
                            $action .= $cancel;
                        }
                    }

                    if($recovery_delete_perm) {

                        $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill','year' => $year]);
                        $delete = $delete_view->render();
                        $action .= $delete;
                    }
                    
                    if($joining_confirmation_perm) {

                        if($value['job_confirmation'] == 0 && $value['cancel_bill']==0){
                            $job_confirmation = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.sendconfirmationmail', 'class' => 'fa fa-send', 'title' => 'Send Confirmation Mail', 'model_title' => 'Send Confirmation Mail', 'model_body' => 'want to Send Confirmation Mail?','year' => $year]);
                            $job_con = $job_confirmation->render();
                            $action .= $job_con;
                        }
                        else if($value['job_confirmation'] == 1 && $value['cancel_bill']==0){
                            $got_confirmation = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.gotconfirmation', 'class' => 'fa fa-check-circle', 'title' => 'Got Confirmation', 'model_title' => 'Got Confirmation Mail', 'model_body' => 'you Got Confirmation Mail?','year' => $year]);
                            $got_con = $got_confirmation->render();
                            $action .= $got_con;
                        }
                        else if($value['job_confirmation'] == 2 && $value['cancel_bill']==0){
                            $invoice_generate = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.invoicegenerate', 'class' => 'fa fa-file', 'title' => 'Generate Invoice', 'model_title' => 'Generate Invoice', 'model_body' => 'want to Generate Invoice?','year' => $year]);
                            $invoice = $invoice_generate->render();
                            $action .= $invoice;
                        }
                        else if($value['job_confirmation'] == 3 && $value['cancel_bill']==0){
                            $payment_received = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.paymentreceived', 'class' => 'fa fa-money', 'title' => 'Payment Received', 'model_title' => 'Payment Received', 'model_body' => 'received Payment?','year' => $year]);
                            $payment = $payment_received->render();
                            $action .= $payment;
                        }
                        /*if(isset($value['invoice_url']) && $value['invoice_url'] != NULL){
                            $action .= '<a target="_blank" href="'.$value['invoice_url'].'" style="margin:2px;"><i  class="fa fa-fw fa-download"></i></a>';
                        }*/
                        if(isset($value['excel_invoice_url']) && $value['excel_invoice_url'] != NULL){

                            $action .= '<a title="Download Invoice" href="'. route('invoice.excel',$value['id']) .'" style="margin:3px;"><i  class="fa fa-download"></i></a>';
                        }
                        /*if(isset($value['pdf_invoice_url']) && $value['pdf_invoice_url'] != NULL){

                           $action .= '<a title="Download PDF" href="'. route('invoice.pdf',$value['id']) .'" style="margin:3px;"><i  class="fa fa-file-pdf-o"></i></a>';
                        }*/
                    }
                }
                if($cancel_bill_perm) {

                    if($value['cancel_bill'] == 1) {

                        $relive_view = \View::make('adminlte::partials.relivebill', ['data' => $value, 'name' => 'recovery','display_name'=>'Recovery']);
                        $relive = $relive_view->render();
                        $action .= $relive;
                    }
                }
            }
            $checkbox .= '<input type=checkbox name=id[] value='.$value['id'].'/>';

            if($access == 'true') {
                $user_name = '<a style="color:black; text-decoration:none;">'.$value['user_name'].'</a>';
            }
            
            /*$job_opening = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'-'.$value['level_name'].'-'.$value['posting_title'].','.$value['city'].'</a>';*/

            $job_opening = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'-'.$value['posting_title'].','.$value['city'].'</a>';

            $joining_date = '<a style="color:black; text-decoration:none; data-th=Lastrun data-order='.$value['date_of_joining_ts'].'">'.$value['date_of_joining'].'</a>';

            if($all_forecasting_perm || $all_recovery_perm) {

                $percentage_charged = '<a style="color:black; text-decoration:none;">'.$value['percentage_charged'].'</a>';
                $lead_efforts = '<a style="color:black; text-decoration:none;">'.$value['lead_efforts'].'</a>';

                $data = array($checkbox,$action,++$j,$user_name,$job_opening,$value['cname'],$joining_date,$value['fixed_salary'],$value['efforts'],$value['candidate_contact_number'],$value['job_location'],$percentage_charged,$value['source'],$value['client_name'],$value['client_contact_number'],$value['client_email_id'],$lead_efforts,$value['job_confirmation']);
            }
            else {

                $data = array($checkbox,$action,++$j,$job_opening,$value['cname'],$joining_date,$value['fixed_salary'],$value['efforts'],$value['candidate_contact_number'],$value['job_location'],$value['source'],$value['client_name'],$value['client_contact_number'],$value['client_email_id'],$value['job_confirmation']);
            }

            $forecasting[$i] = $data;
            $i++;
        }

        if(isset($recovery_bills) && sizeof($recovery_bills) > 0) {

            $jc_sent = 0;
            $got_con = 0;
            $invoice_gen = 0;
            $pymnt_rcv = 0;

            foreach($recovery_bills as $bills) {

                if($bills['job_confirmation'] == '1') {
                    $jc_sent++;
                }
                else if ($bills['job_confirmation'] == '2') {
                    $got_con++;
                }
                else if($bills['job_confirmation'] == '3') {
                    $invoice_gen++;
                }
                else if($bills['job_confirmation'] == '4') {
                    $pymnt_rcv++;
                }
            }
            $bills = array();
            $bills['jc_sent'] = $jc_sent;
            $bills['got_con'] = $got_con;
            $bills['invoice_gen'] = $invoice_gen;
            $bills['pymnt_rcv'] = $pymnt_rcv;
        }
        else {
            $bills = array();
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $forecasting,
            "bills" => $bills,
        );

        echo json_encode($json_data);exit;
    }

    public function cancelbnm() {

        $cancel_bill = 1;
        $cancel_bnm = 1;
        $user = \Auth::user();
        $user_id = $user->id;

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+1 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $next = $y+1;
            $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
        }

        if (isset($_POST['year']) && $_POST['year'] != '') {
            $year = $_POST['year'];
        }
        else{
            $y = date('Y');
            $m = date('m');
            if ($m > 3) {
                $n = $y + 1;
                $year = $y.'-4, '.$n.'-3';
            }
            else{
                $n = $y-1;
                $year = $n.'-4, '.$y.'-3';
            }
        }

        $year_data = explode(", ", $year);
        $year1 = $year_data[0];
        $year2 = $year_data[1];
        $current_year = date('Y-m-d',strtotime("first day of $year1"));
        $next_year = date('Y-m-d',strtotime("last day of $year2"));

        $all_forecasting_perm = $user->can('display-forecasting');
        $loggedin_forecasting_perm = $user->can('display-forecasting-by-loggedin-user');
        $can_owner_forecasting_perm = $user->can('display-forecasting-by-candidate-owner');
        $cancel_bill_perm = $user->can('cancel-bill');
        
        if($all_forecasting_perm && $cancel_bill_perm) {
            $count = Bills::getAllCancelBillsCount(0,1,$user_id,0,$current_year,$next_year);
            $access = true;
        }
        else if(($loggedin_forecasting_perm && $cancel_bill_perm) || ($can_owner_forecasting_perm && $cancel_bill_perm)) {
            $count = Bills::getAllCancelBillsCount(0,0,$user_id,0,$current_year,$next_year);
            $access = false;
        }

        $title = "Cancel Forecasting";
        return view('adminlte::bills.index', compact('access','user_id','title','count','cancel_bill','cancel_bnm','year','year_array'));
    }

    // for cancel bills get using ajax
    public function getAllCancelBillsDetails() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        $title = $_GET['title'];

        $cancel_bill = 0;

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+1 year'));
        $year_array = array();
        $year_array[0] = "Select Year";

        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $next = $y+1;
            $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            
            $year = $_GET['year'];

            if (isset($year) && $year != 0) {
                $year_data = explode(", ", $year);
                $year1 = $year_data[0];
                $year2 = $year_data[1];
                $current_year = date('Y-m-d',strtotime("first day of $year1"));
                $next_year = date('Y-m-d',strtotime("last day of $year2"));
            }
            else {
                $year = NULL;
                $current_year = NULL;
                $next_year = NULL;    
            }
        }
        else{
            $year = NULL;
            $current_year = NULL;
            $next_year = NULL;
        }

        $user = \Auth::user();
        $user_id = $user->id;
        $all_forecasting_perm = $user->can('display-forecasting');
        $loggedin_forecasting_perm = $user->can('display-forecasting-by-loggedin-user');
        $can_owner_forecasting_perm = $user->can('display-forecasting-by-candidate-owner');

        $all_recovery_perm = $user->can('display-recovery');
        $loggedin_recovery_perm = $user->can('display-recovery-by-loggedin-user');
        $can_owner_recovery_perm = $user->can('display-recovery-by-candidate-owner');
        
        $forecasting_delete_perm = $user->can('forecasting-delete');
        $recovery_delete_perm = $user->can('recovery-delete');

        $cancel_bill_perm = $user->can('cancel-bill');
        $generate_recovery_perm = $user->can('generate-recovery');
        $joining_confirmation_perm = $user->can('send-joining-confirmation');

        if ($title == 'Cancel Forecasting') {

            if($all_forecasting_perm && $cancel_bill_perm) {
                $order_column_name = self::getForecastingOrderColumnName($order,1);
                $bnm = Bills::getCancelBills(0,1,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
                $count = Bills::getAllCancelBillsCount(0,1,$user_id,$search,$current_year,$next_year);
                $access = true;
            }
            else if(($loggedin_forecasting_perm && $cancel_bill_perm) || ($can_owner_forecasting_perm && $cancel_bill_perm)) {
                $order_column_name = self::getForecastingOrderColumnName($order,0);
                $bnm = Bills::getCancelBills(0,0,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
                $count = Bills::getAllCancelBillsCount(0,0,$user_id,$search,$current_year,$next_year);
                $access = false;
            }
        }
        else if($title == 'Cancel Recovery') {

            if($all_recovery_perm && $cancel_bill_perm) {
                $order_column_name = self::getForecastingOrderColumnName($order,1);
                $bnm = Bills::getCancelBills(1,1,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
                $count = Bills::getAllCancelBillsCount(1,1,$user_id,$search,$current_year,$next_year);
                $access = true;

                // For display count in recovery header
                $cancel_recovery_bills = Bills::getCancelBills(1,1,$user_id,0,0,0,0,'',$current_year,$next_year);
            }
            else if(($loggedin_recovery_perm && $cancel_bill_perm) || ($can_owner_recovery_perm && $cancel_bill_perm)) {
                $order_column_name = self::getForecastingOrderColumnName($order,0);
                $bnm = Bills::getCancelBills(1,0,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
                $count = Bills::getAllCancelBillsCount(1,0,$user_id,$search,$current_year,$next_year);
                $access = false;

                // For display count in recovery header
                $cancel_recovery_bills = Bills::getCancelBills(1,0,$user_id,0,0,0,0,'',$current_year,$next_year);
            }
        }

        $forecasting = array();
        $i = 0;$j = 0;
        foreach ($bnm as $key => $value) {

            $action = '';
            $checkbox = '';

            if ($title == 'Cancel Forecasting') {

                $action .= '<a title="show" class="fa fa-circle" href="'.route('forecasting.show',$value['id']).'" style="margin:2px;"></a>';

                if($access || ($user_id == $value['uploaded_by'])) {
                    
                    $action .= '<a title="Edit" class="fa fa-edit" href="'.route('forecasting.edit',$value['id']).'" style="margin:2px;"></a>';

                    if($cancel_bill_perm) {

                        if($value['cancel_bill'] == 0) {

                            $cancel_view = \View::make('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                            $cancel = $cancel_view->render();
                            $action .= $cancel;
                        }
                    }

                    if($forecasting_delete_perm) {
                        $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $delete = $delete_view->render();
                        $action .= $delete;
                    }

                    if($generate_recovery_perm) {
                        if($value['status']==0 && $value['cancel_bill']!=1){
                            //BM will be generated after date of joining
                            if(date("Y-m-d")>= date("Y-m-d",strtotime($value['date_of_joining']))) {
                                $action .= '<a title="Generate Recovery" class="fa fa-square" href="'.route('bills.generaterecovery',$value['id']).'" style="margin:2px;"></a>';
                            }
                        }
                    }
                }
                if($cancel_bill_perm) {

                    if($value['cancel_bill']==1) {
                        $relive_view = \View::make('adminlte::partials.relivebill', ['data' => $value, 'name' => 'recovery','display_name'=>'Forcasting']);
                        $relive = $relive_view->render();
                        $action .= $relive;
                    }
                }
            }
            else if ($title == 'Cancel Recovery') {

                if($access || ($user_id == $value['uploaded_by'])) {

                    $action .= '<a title="Edit" class="fa fa-edit" href="'.route('forecasting.edit',$value['id']).'" style="margin:2px;"></a>';

                    if($cancel_bill_perm) {
                        if($value['cancel_bill']==0) {
                            $cancel_view = \View::make('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                            $cancel = $cancel_view->render();
                            $action .= $cancel;
                        }
                    }

                    if($recovery_delete_perm) {
                        $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill']);
                        $delete = $delete_view->render();
                        $action .= $delete;
                    }

                    if($joining_confirmation_perm) {

                        if($value['job_confirmation'] == 0 && $value['cancel_bill']==0) {
                            $job_confirmation = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.sendconfirmationmail', 'class' => 'fa fa-send', 'title' => 'Send Confirmation Mail', 'model_title' => 'Send Confirmation Mail', 'model_body' => 'want to Send Confirmation Mail?']);
                            $job_con = $job_confirmation->render();
                            $action .= $job_con;
                        }
                        else if($value['job_confirmation'] == 1 && $value['cancel_bill']==0) {
                            $got_confirmation = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.gotconfirmation', 'class' => 'fa fa-check-circle', 'title' => 'Got Confirmation', 'model_title' => 'Got Confirmation Mail', 'model_body' => 'you Got Confirmation Mail?']);
                            $got_con = $got_confirmation->render();
                            $action .= $got_con;
                        }
                        else if($value['job_confirmation'] == 2 && $value['cancel_bill']==0) {
                            $invoice_generate = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.invoicegenerate', 'class' => 'fa fa-file', 'title' => 'Generate Invoice', 'model_title' => 'Generate Invoice', 'model_body' => 'want to Generate Invoice?']);
                            $invoice = $invoice_generate->render();
                            $action .= $invoice;
                        }
                        else if($value['job_confirmation'] == 3 && $value['cancel_bill']==0){
                            $payment_received = \View::make('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.paymentreceived', 'class' => 'fa fa-money', 'title' => 'Payment Received', 'model_title' => 'Payment Received', 'model_body' => 'received Payment?']);
                            $payment = $payment_received->render();
                            $action .= $payment;
                        }
                        /*if(isset($value['invoice_url']) && $value['invoice_url'] != NULL){
                            $action .= '<a target="_blank" href="'.$value['invoice_url'].'" style="margin:2px;"><i  class="fa fa-fw fa-download"></i></a>';
                        }*/
                        if(isset($value['excel_invoice_url']) && $value['excel_invoice_url'] != NULL){

                            $action .= '<a title="Download Invoice" href="'. route('invoice.excel',$value['id']) .'" style="margin:3px;"><i class="fa fa-download"></i></a>';
                        }
                        /*if(isset($value['pdf_invoice_url']) && $value['pdf_invoice_url'] != NULL){

                           $action .= '<a title="Download PDF" href="'. route('invoice.pdf',$value['id']) .'" style="margin:3px;"><i class="fa fa-file-pdf-o"></i></a>';
                        }*/
                    }
                }
                if($cancel_bill_perm) {
                    if($value['cancel_bill']==1){
                        $relive_view = \View::make('adminlte::partials.relivebill', ['data' => $value, 'name' => 'recovery','display_name'=>'Recovery']);
                        $relive = $relive_view->render();
                        $action .= $relive;
                    }
                }
            }
            $checkbox .= '<input type=checkbox name=id[] value='.$value['id'].'/>';

            if($access=='true') {
                $user_name = '<a style="color:black; text-decoration:none;">'.$value['user_name'].'</a>';
            }
            //$job_opening = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'-'.$value['posting_title'].','.$value['city'].'</a>';

            /*$job_opening = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'-'.$value['level_name'].'-'.$value['posting_title'].','.$value['city'].'</a>';*/

            $job_opening = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'-'.$value['posting_title'].','.$value['city'].'</a>';

            $joining_date = '<a style="color:black; text-decoration:none; data-th=Lastrun data-order='.$value['date_of_joining_ts'].'">'.$value['date_of_joining'].'</a>';

            if($all_forecasting_perm || $all_recovery_perm) {

                $percentage_charged = '<a style="color:black; text-decoration:none;">'.$value['percentage_charged'].'</a>';
                $lead_efforts = '<a style="color:black; text-decoration:none;">'.$value['lead_efforts'].'</a>';

                $data = array($checkbox,$action,++$j,$user_name,$job_opening,$value['cname'],$joining_date,$value['fixed_salary'],$value['efforts'],$value['candidate_contact_number'],$value['job_location'],$percentage_charged,$value['source'],$value['client_name'],$value['client_contact_number'],$value['client_email_id'],$lead_efforts,$value['job_confirmation']);
            }
            else {

                $data = array($checkbox,$action,++$j,$job_opening,$value['cname'],$joining_date,$value['fixed_salary'],$value['efforts'],$value['candidate_contact_number'],$value['job_location'],$value['source'],$value['client_name'],$value['client_contact_number'],$value['client_email_id'],$value['job_confirmation']);
            }

            $forecasting[$i] = $data;
            $i++;
        }

        if(isset($cancel_recovery_bills) && sizeof($cancel_recovery_bills) > 0) {

            $jc_sent = 0;
            $got_con = 0;
            $invoice_gen = 0;
            $pymnt_rcv = 0;

            foreach($cancel_recovery_bills as $bills) {

                if($bills['job_confirmation'] == '1') {
                    $jc_sent++;
                }
                else if ($bills['job_confirmation'] == '2') {
                    $got_con++;
                }
                else if($bills['job_confirmation'] == '3') {
                    $invoice_gen++;
                }
                else if($bills['job_confirmation'] == '4') {
                    $pymnt_rcv++;
                }
            }

            $bills = array();
            $bills['jc_sent'] = $jc_sent;
            $bills['got_con'] = $got_con;
            $bills['invoice_gen'] = $invoice_gen;
            $bills['pymnt_rcv'] = $pymnt_rcv;
        }
        else {
            $bills = array();
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $forecasting,
            "bills" => $bills,
        );

        echo json_encode($json_data);exit;
    }

    public function billsMade() {

        $cancel_bill = 0;
        
        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+1 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $next = $y+1;
            $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
        }

        if (isset($_POST['year']) && $_POST['year'] != '') {
            $year = $_POST['year'];
        }
        else{
            $y = date('Y');
            $m = date('m');
            if ($m > 3) {
                $n = $y + 1;
                $year = $y.'-4, '.$n.'-3';
            }
            else{
                $n = $y-1;
                $year = $n.'-4, '.$y.'-3';
            }
        }

        $year_data = explode(", ", $year);
        $year1 = $year_data[0];
        $year2 = $year_data[1];
        $current_year = date('Y-m-d',strtotime("first day of $year1"));
        $next_year = date('Y-m-d',strtotime("last day of $year2"));

        $user = \Auth::user();
        $user_id = $user->id;
        $all_recovery_perm = $user->can('display-recovery');
        $loggedin_recovery_perm = $user->can('display-recovery-by-loggedin-user');
        $can_owner_recovery_perm = $user->can('display-recovery-by-candidate-owner');

        if($all_recovery_perm) {

            $recovery_bills = Bills::getAllBills(1,1,$user_id,0,0,0,0,'',$current_year,$next_year);
            $count = Bills::getAllBillsCount(1,1,$user_id,0,$current_year,$next_year);
            $access = true;
        }

        else if($loggedin_recovery_perm || $can_owner_recovery_perm) {

            $recovery_bills = Bills::getAllBills(1,0,$user_id,0,0,0,0,'',$current_year,$next_year);
            $count = Bills::getAllBillsCount(1,0,$user_id,0,$current_year,$next_year);
            $access = false;
        }

        $jc_sent = 0;
        $got_con = 0;
        $invoice_gen = 0;
        $pymnt_rcv = 0;

        foreach($recovery_bills as $bills) {

            if($bills['job_confirmation'] == '1') {
                $jc_sent++;
            }
            else if ($bills['job_confirmation'] == '2') {
                $got_con++;
            }
            else if($bills['job_confirmation'] == '3') {
                $invoice_gen++;
            }
            else if($bills['job_confirmation'] == '4') {
                $pymnt_rcv++;
            }
        }

        $title = "Recovery";
        return view('adminlte::bills.index', compact('access','user_id','title','count','cancel_bill','year_array','year','jc_sent','got_con','invoice_gen','pymnt_rcv'));
    }

    public function billsMade2() {

        $user = \Auth::user();
        $user_id = $user->id;

        $bnm = Bills::getAllBills2(1,1,$user_id,0,0,0,0,'','','');
    }

    public function cancelbm() {

        $cancel_bill = 1;
        $cancel_bnm = 0;
        $cancel_bn = 1;
        $user = \Auth::user();
        $user_id = $user->id;

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+1 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $next = $y+1;
            $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
        }

        if (isset($_POST['year']) && $_POST['year'] != '') {
            $year = $_POST['year'];
        }
        else{
            $y = date('Y');
            $m = date('m');
            if ($m > 3) {
                $n = $y + 1;
                $year = $y.'-4, '.$n.'-3';
            }
            else{
                $n = $y-1;
                $year = $n.'-4, '.$y.'-3';
            }
        }

        $year_data = explode(", ", $year);
        $year1 = $year_data[0];
        $year2 = $year_data[1];
        $current_year = date('Y-m-d',strtotime("first day of $year1"));
        $next_year = date('Y-m-d',strtotime("last day of $year2"));
        
        $all_recovery_perm = $user->can('display-recovery');
        $loggedin_recovery_perm = $user->can('display-recovery-by-loggedin-user');
        $can_owner_recovery_perm = $user->can('display-recovery-by-candidate-owner');
        $cancel_bill_perm = $user->can('cancel-bill');

        if($all_recovery_perm && $cancel_bill_perm) {

            $count = Bills::getAllCancelBillsCount(1,1,$user_id,0,$current_year,$next_year);
            $bnm = Bills::getCancelBills(1,1,$user_id,0,0,0,0,'',$current_year,$next_year);

            $access = true;
        }
        else if(($loggedin_recovery_perm && $cancel_bill_perm) || ($can_owner_recovery_perm && $cancel_bill_perm)) {

            $count = Bills::getAllCancelBillsCount(1,0,$user_id,0,$current_year,$next_year);
            $bnm = Bills::getCancelBills(1,0,$user_id,0,0,0,0,'',$current_year,$next_year);
            $access = false;
        }

        $jc_sent = 0;
        $got_con = 0;
        $invoice_gen = 0;
        $pymnt_rcv = 0;

        foreach($bnm as $bills) {

            if($bills['job_confirmation'] == '1') {
                $jc_sent++;
            }
            else if ($bills['job_confirmation'] == '2') {
                $got_con++;
            }
            else if($bills['job_confirmation'] == '3') {
                $invoice_gen++;
            }
            else if($bills['job_confirmation'] == '4') {
                $pymnt_rcv++;
            }
        }
        $title = "Cancel Recovery";
        return view('adminlte::bills.index', compact('access','user_id','title','count','cancel_bill','cancel_bnm','cancel_bn','jc_sent','got_con','invoice_gen','pymnt_rcv','year_array','year'));

    }

    public function create() {

        $action = 'add';
        $generate_bm = '0';
        $status = '0';

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        if($all_jobs_perm) {
            $job_response = JobOpen::getAllBillsJobs(1,$user_id);
        }
        else if($user_jobs_perm) {
            $job_response = JobOpen::getAllBillsJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
           
            $jobopen[$v['id']] = $v['company_name']." - ".$v['posting_title']." ,".$v['location'];
        }

        $job_id = 0;

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $type_array = array($recruitment,$hr_advisory);

        $users_array = User::getAllUsers($type_array);
        $users = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $key => $value) {
               
               $user_details = User::getAllDetailsByUserID($key);

               if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $users[$key] = $value;
                    }
               }
               else {
                    $users[$key] = $value;
               }    
            }
        }

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

        return view('adminlte::bills.create', compact('action','generate_bm','jobopen','job_id','users','employee_name','employee_percentage','candidate_id','candidateSource','status','lead_name','lead_percentage'));
    }

    public function store(Request $request) {

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

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
        $client_name = $input['client_name'];
        $client_email_id = $input['client_email_id'];
        $address_of_communication = $input['address_of_communication'];

        if(isset($input['percentage_charged']) && $input['percentage_charged']!='') {
            $percentage_charged = $input['percentage_charged'];
        }
        else{
            $percentage_charged = '8.33';
        }

        $employee_name = array();
        $employee_final = array();
        $employee_percentage = array();

        if(isset($input['employee_name_1']) && $input['employee_name_1'] != '') {
            $employee_name[] = $input['employee_name_1'];
        }

        if(isset($input['employee_name_2']) && $input['employee_name_2'] != '') {
            $employee_name[] = $input['employee_name_2'];
        }

        if(isset($input['employee_name_3']) && $input['employee_name_3'] != '') {
            $employee_name[] = $input['employee_name_3'];
        }

        if(isset($input['employee_name_4']) && $input['employee_name_4'] != '') {
            $employee_name[] = $input['employee_name_4'];
        }

        if(isset($input['employee_name_5']) && $input['employee_name_5'] != '') {
            $employee_name[] = $input['employee_name_5'];
        }

        $employee_percentage[] = $input['employee_percentage_1'];
        $employee_percentage[] = $input['employee_percentage_2'];
        $employee_percentage[] = $input['employee_percentage_3'];
        $employee_percentage[] = $input['employee_percentage_4'];
        $employee_percentage[] = $input['employee_percentage_5'];
        $total = 0;

        foreach ($employee_name as $k => $v) {

            if ($v != '') {

                $employee_final[$v] = $employee_percentage[$k];
                $total += $employee_percentage[$k];
            }   
        }

        if($total>100) {
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
            $bill->percentage_charged = '8.33';

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

            // Get all documents

            $unedited_resume = $request->file('unedited_resume');
            $offer_letter = $request->file('offer_letter');
            $upload_documents = $request->file('upload_documents');

            // Save unedited resume

            if (isset($unedited_resume) && $unedited_resume->isValid()) {

                $file_name = $unedited_resume->getClientOriginalName();
                $file_size = fileSize($unedited_resume);

                $dir = "uploads/bills/" . $bill_id . '/';
                $file_path = $dir . $file_name;

                if (!file_exists($dir) && !is_dir($dir)) {
                    mkdir("uploads/bills/$bill_id", 0777, true);
                    chmod($dir, 0777);
                }

                if(!$unedited_resume->move($dir, $file_name)) {
                    return false;
                }
                else {
                    $bills_doc = new BillsDoc();
                    $bills_doc->bill_id = $bill_id;
                    $bills_doc->category = "Unedited Resume";
                    $bills_doc->file = $file_path;
                    $bills_doc->name = $file_name;
                    $bills_doc->size = $file_size;
                    $bills_doc->save();
                }
            }

            // Save offer letter

            if (isset($offer_letter) && $offer_letter->isValid()) {

                $file_name = $offer_letter->getClientOriginalName();
                $file_size = fileSize($offer_letter);

                $dir = "uploads/bills/" . $bill_id . '/';
                $file_path = $dir . $file_name;

                if (!file_exists($dir) && !is_dir($dir)) {
                    mkdir("uploads/bills/$bill_id", 0777, true);
                    chmod($dir, 0777);
                }

                if(!$offer_letter->move($dir, $file_name)) {
                    return false;
                }
                else {
                    $bills_doc = new BillsDoc();
                    $bills_doc->bill_id = $bill_id;
                    $bills_doc->category = "Offer Letter";
                    $bills_doc->file = $file_path;
                    $bills_doc->name = $file_name;
                    $bills_doc->size = $file_size;
                    $bills_doc->save();
                }
            }

            // Save other documents

            if (isset($upload_documents) && sizeof($upload_documents) > 0) {

                foreach ($upload_documents as $k => $v) {

                    if (isset($v) && $v->isValid()) {
                        
                        $file_name = $v->getClientOriginalName();
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
                        $bills_doc->category = "Others";
                        $bills_doc->file = $file_path;
                        $bills_doc->name = $file_name;
                        $bills_doc->size = $file_size;
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

        // Add forecasting date in table

        $bill_id = $bill->id;

        $bill_date = new BillDate();
        $bill_date->bills_id = $bill_id;
        $bill_date->forecasting_date = date('Y-m-d');
        $bill_date->save();

        // For forcasting mail [email_notification table entry every minute check]
        $user_email = \Auth::user()->email;
        $superadminuserid = getenv('SUPERADMINUSERID');
        $accountantuserid = getenv('ACCOUNTANTUSERID');
        //$operationsexecutiveuserid = getenv('OPERATIONSEXECUTIVEUSERID');

        $superadminemail = User::getUserEmailById($superadminuserid);
        $accountantemail = User::getUserEmailById($accountantuserid);
        //$operationsexecutivemail = User::getUserEmailById($operationsexecutiveuserid);

        //$cc_users_array = array($superadminemail,$accountantemail,$operationsexecutivemail);
        $cc_users_array = array($superadminemail,$accountantemail);
        $cc_users_array = array_filter($cc_users_array);

        $c_name = CandidateBasicInfo::getCandidateNameById($candidate_name);

        $module = "Forecasting";
        $sender_name = $user_id;
        $to = $user_email;
        $cc = implode(",",$cc_users_array);
        $subject = "Forecasting - " . $c_name;
        $message = "Forecasting - " . $c_name;
        $module_id = $bill_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('forecasting.index')->with('success', 'Bills Created Successfully.');
    }

    public function show($id) {

        $viewVariable = Bills::getShowBill($id);

       return view('adminlte::bills.show', $viewVariable);
    }

    public function edit($id) {

        $action = 'edit';
        $generate_bm ='0';
        $user = \Auth::user();
        $user_id = $user->id;
        
        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        if($all_jobs_perm) {
            $job_response = JobOpen::getAllBillsJobs(1,$user_id);
        }
        else if($user_jobs_perm) {
            $job_response = JobOpen::getAllBillsJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){

            $jobopen[$v['id']] = $v['company_name']." - ".$v['posting_title']." ,".$v['location'];
        }

        $dateClass = new Date();

        $bnm = Bills::find($id);
        $doj = $dateClass->changeYMDtoDMY($bnm->date_of_joining);
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
            $lead_name = '';
            $lead_percentage = 0;
        }

        $job_id = $bnm->job_id;
        $candidate_id = $bnm->candidate_id;
        
        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $type_array = array($recruitment,$hr_advisory);

        $users_array = User::getAllUsers($type_array);
        $users = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $key => $value) {
               
               $user_details = User::getAllDetailsByUserID($key);

               if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $users[$key] = $value;
                    }
               }
               else {
                    $users[$key] = $value;
               }    
            }
        }

        $candidateSource = CandidateBasicInfo::getCandidateSourceArrayByName();

        $billModel = new Bills();
        $upload_type = $billModel->upload_type;

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
                $billsdetails['files'][$i]['name'] = $billfile->name;
                $billsdetails['files'][$i]['size'] = $utils->formatSizeUnits($billfile->size);
                $billsdetails['files'][$i]['category'] = $billfile->category;

                if (array_search($billfile->category, $upload_type)) {
                    unset($upload_type[array_search($billfile->category, $upload_type)]);
                }

                $i++;
            }
        }

        $upload_type['Others'] = 'Others';

        return view('adminlte::bills.edit', compact('bnm', 'action', 'employee_name', 'employee_percentage','generate_bm','doj','jobopen','job_id','users','candidate_id','candidateSource','billsdetails','id','status','lead_name','lead_percentage','upload_type'));
    }

    public function update(Request $request, $id) {
        
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
        $client_name = $input['client_name'];
        $client_email_id = $input['client_email_id'];
        $address_of_communication = $input['address_of_communication'];
        $generateBM = $input['generateBM'];

        $status = 0;

        if($generateBM == 1) {

            $status = 1;

            // Add Recovery Date

            $current_dt = date('Y-m-d');

            \DB::statement("UPDATE bills_date SET recovery_date = '$current_dt' where bills_id = $id");
        }

        if(isset($input['percentage_charged']) && $input['percentage_charged']!='')
            $percentage_charged = $input['percentage_charged'];
        else
            $percentage_charged = '8.33';

        $employee_name = array();
        $employee_final = array();
        $employee_percentage = array();

        if(isset($input['employee_name_1']) && $input['employee_name_1'] != '') {
            $employee_name[] = $input['employee_name_1'];
        }

        if(isset($input['employee_name_2']) && $input['employee_name_2'] != '') {
            $employee_name[] = $input['employee_name_2'];
        }

        if(isset($input['employee_name_3']) && $input['employee_name_3'] != '') {
            $employee_name[] = $input['employee_name_3'];
        }

        if(isset($input['employee_name_4']) && $input['employee_name_4'] != '') {
            $employee_name[] = $input['employee_name_4'];
        }

        if(isset($input['employee_name_5']) && $input['employee_name_5'] != '') {
            $employee_name[] = $input['employee_name_5'];
        }

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

        if($total>100) {
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

        $prev_fixed_salary = $bill->fixed_salary;
        $prev_percentage_charged = $bill->percentage_charged;
        $prev_date_of_joining = $bill->date_of_joining;
        $prev_job_location = $bill->job_location;
   
        $uploaded_by = $bill->uploaded_by;
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
            $bill->percentage_charged = '8.33';

        // for set again job confirmation icon if deatils are changed
        if($prev_percentage_charged != $bill->percentage_charged) {
            \DB::statement("UPDATE bills SET joining_confirmation_mail = '0' where id=$id");
        }
        else if($prev_fixed_salary != $bill->fixed_salary) {
            \DB::statement("UPDATE bills SET joining_confirmation_mail = '0' where id=$id");
        }
        else if($prev_date_of_joining != $bill->date_of_joining) {
            \DB::statement("UPDATE bills SET joining_confirmation_mail = '0' where id=$id");
        }
        else if($prev_job_location != $bill->job_location) {
            \DB::statement("UPDATE bills SET joining_confirmation_mail = '0' where id=$id");
        }

        $validator = \Validator::make(Input::all(),$bill::$rules);

        if($validator->fails()) {
            return redirect('forecasting/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }
        else {

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

            $upload_type = $request->upload_type;
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
            $bills_doc->category = $upload_type;
            $bills_doc->file = $file_path;
            $bills_doc->name = $file_name;
            $bills_doc->size = $file_size;
            $bills_doc->created_at = date('Y-m-d');
            $bills_doc->updated_at = date('Y-m-d');

            $bills_doc->save();

            if ($status == 1) {

                return redirect()->route('bills.generaterecovery',$id)->with('success', 'Attchment Upload Successfully.');
            }
            else{
           
            return redirect()->route('forecasting.edit',$id)->with('success', 'Attchment Upload Successfully.');
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
            //$operationsexecutiveuserid = getenv('OPERATIONSEXECUTIVEUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);
            //$operationsexecutivemail = User::getUserEmailById($operationsexecutiveuserid);

            //$cc_users_array = array($superadminemail,$accountantemail,$operationsexecutivemail);
            $cc_users_array = array($superadminemail,$accountantemail);
            $cc_users_array = array_filter($cc_users_array);

            $c_name = CandidateBasicInfo::getCandidateNameById($candidate_id);

            $module = "Recovery";
            $sender_name = $user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = "Recovery - ". $c_name;
            $message = "Recovery - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if($status == 1) {

            return redirect()->route('bills.recovery')
            ->with('success', 'Recovery Updated Successfully.');
        }
        else{
            return redirect()->route('forecasting.index')->with('success', 'Forecasting Updated Successfully.');
        }
    }

    public function delete(Request $request,$id) {

        $year = $request->input('year');

        // Destroy Attchments
        $bills_attach = \DB::table('bills_doc')->select('bills_doc.*')->where('bill_id','=',$id)->get();

        // Delete all atttchments
        if(isset($bills_attach) && sizeof($bills_attach)>0) {

            foreach ($bills_attach as $key => $value) {
                $path = "uploads/bills/".$id . "/" . $value->name;
                unlink($path);
           }
        }

        // Delete Empty Directory
        $dir_path = "uploads/bills/".$id;
        if(is_dir($dir_path)) {
            rmdir($dir_path);
        }

        BillsDoc::where('bill_id',$id)->delete();
        BillsEffort::where('bill_id',$id)->delete();
        BillsLeadEfforts::where('bill_id',$id)->delete();
        BillDate::where('bills_id',$id)->delete();
        Bills::where('id',$id)->delete();

        if(isset($year) && $year != '') {

            return redirect()->route('bills.recovery')->with('success', 'Recovery Deleted Successfully.')
            ->with('selected_year',$year);
        }
        else {

            return redirect()->route('forecasting.index')->with('success','Bill Deleted Successfully.');
        }
    }

    public function cancel($id) {

        // Get Selected Year
        if(isset($_GET['year']) && $_GET['year'] != ''){    
            $year = $_GET['year'];
        }
        
        $cancel_bill =1;
        $bills = array();
        $bill = Bills::find($id);
        $bills['status'] = $bill->status;
        $bills['job_id'] = $bill->job_id;
        $bills['candidate_id'] = $bill->candidate_id;
        $bill->cancel_bill = $cancel_bill;
        $bill_cancel = $bill->save();
        
        $candidate_join_delete = JobCandidateJoiningdate::where('job_id',$bills['job_id'])->where('candidate_id',$bills['candidate_id'])->delete();

        if ($bills['status'] == 1) {

            // Set Bill Forecating date to NULL
            \DB::statement("UPDATE bills_date SET recovery_date = NULL where bills_id = $id");

            // For Cancel Recovery mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');
            //$operationsexecutiveuserid = getenv('OPERATIONSEXECUTIVEUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);
            //$operationsexecutivemail = User::getUserEmailById($operationsexecutiveuserid);

            //$cc_users_array = array($superadminemail,$accountantemail,$operationsexecutivemail);
            $cc_users_array = array($superadminemail,$accountantemail);
            $cc_users_array = array_filter($cc_users_array);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Cancel Recovery";
            $sender_name = $user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = "Cancel Recovery - ". $c_name;
            $message = "Cancel Recovery - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
        else if ($bills['status'] == 0) {

            // Set Bill Forecating date to NULL
            \DB::statement("UPDATE bills_date SET forecasting_date = NULL where bills_id = $id");

            // For Cancel Forecasting mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');
            //$operationsexecutiveuserid = getenv('OPERATIONSEXECUTIVEUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);
            //$operationsexecutivemail = User::getUserEmailById($operationsexecutiveuserid);

            //$cc_users_array = array($superadminemail,$accountantemail,$operationsexecutivemail);
            $cc_users_array = array($superadminemail,$accountantemail);
            $cc_users_array = array_filter($cc_users_array);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Cancel Forecasting";
            $sender_name = $user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = "Cancel Forecasting - ". $c_name;
            $message = "Cancel Forecasting - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if($bills['status'] == 1) {
            return redirect()->route('bills.recovery')->with('success', 'Recovery Canceled Successfully.')
            ->with('selected_year',$year);
        }
        else {
            return redirect()->route('forecasting.index')->with('success', 'Forecasting Canceled Successfully.');
        }
    }

    public function reliveBill($id) {

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

        $candidatejoindate = new JobCandidateJoiningdate();
        $candidatejoindate->job_id = $bills['job_id'];
        $candidatejoindate->candidate_id = $bills['candidate_id'];
        $candidatejoindate->joining_date = $bills['joining_date'];
        $candidatejoindate->fixed_salary = $bills['fixed_salary'];
        $candidatejoindate->save();

        if ($bills['status'] == 1) {

            // Set Bill Recovery date to current date
            $current_dt = date('Y-m-d');
            \DB::statement("UPDATE bills_date SET recovery_date = '$current_dt' where bills_id = $id");

            // For Relive Recovery mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');
            //$operationsexecutiveuserid = getenv('OPERATIONSEXECUTIVEUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);
            //$operationsexecutivemail = User::getUserEmailById($operationsexecutiveuserid);

            //$cc_users_array = array($superadminemail,$accountantemail,$operationsexecutivemail);
            $cc_users_array = array($superadminemail,$accountantemail);
            $cc_users_array = array_filter($cc_users_array);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Relive Recovery";
            $sender_name = $user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = "Relive Recovery - ". $c_name;
            $message = "Relive Recovery - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
        else if ($bills['status'] == 0) {

            // Set Bill Forecasting date to current date
            $current_dt = date('Y-m-d');
            \DB::statement("UPDATE bills_date SET forecasting_date = '$current_dt' where bills_id = $id");

            // For Relive Forecasting mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');
            //$operationsexecutiveuserid = getenv('OPERATIONSEXECUTIVEUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);
            //$operationsexecutivemail = User::getUserEmailById($operationsexecutiveuserid);

            //$cc_users_array = array($superadminemail,$accountantemail,$operationsexecutivemail);
            $cc_users_array = array($superadminemail,$accountantemail);
            $cc_users_array = array_filter($cc_users_array);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Relive Forecasting";
            $sender_name = $user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = "Relive Forecasting - ". $c_name;
            $message = "Relive Forecasting - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if($bills['status'] == 1){
            return redirect()->route('bills.recovery')->with('success', 'Recovery Relived Successfully.');
        }
        else{
            return redirect()->route('forecasting.index')->with('success', 'Forecasting Relived Successfully.');
        }
    }

    public function attachmentsDestroy($id) {

        $billFileDetails = BillsDoc::find($id);

        $billId = $billFileDetails->bill_id;

        unlink($billFileDetails->file);

        $billFileDelete = BillsDoc::where('id',$id)->delete();

        return redirect()->route('forecasting.show',[$billId])->with('success','Attachment Deleted Successfully.');
    }

    public function upload(Request $request) {

        $upload_type = $request->upload_type;
        $file = $request->file('file');
        $bill_id = $request->id;

        if (isset($file) && $file->isValid()) {
            $file_name = $file->getClientOriginalName();
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
            $bills_doc->category = $upload_type;
            $bills_doc->file = $file_path;
            $bills_doc->name = $file_name;
            $bills_doc->size = $file_size;
            $bills_doc->save();
        }
        return redirect()->route('forecasting.show',[$bill_id])->with('success','Attachment uploaded successfully.');
    }

    public function generateBM($id) {

        $generate_bm = '1';
        $action = 'edit';

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        if($all_jobs_perm) {
            $job_response = JobOpen::getAllBillsJobs(1,$user_id);
        }
        else if($user_jobs_perm) {
            $job_response = JobOpen::getAllBillsJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';

        foreach ($job_response as $k=>$v) {
            $jobopen[$v['id']] = $v['company_name']." - ".$v['posting_title']." ,".$v['location'];
        }

        $bnm = Bills::find($id);
        $status = $bnm->status;

        $dateClass = new Date();
        $doj = $dateClass->changeYMDtoDMY($bnm->date_of_joining);

        $percentage_charged = $bnm->percentage_charged;

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

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $type_array = array($recruitment,$hr_advisory);

        $users_array = User::getAllUsers($type_array);
        $users = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $key => $value) {
               
               $user_details = User::getAllDetailsByUserID($key);

               if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $users[$key] = $value;
                    }
               }
               else {
                    $users[$key] = $value;
               }    
            }
        }

        $candidateSource = CandidateBasicInfo::getCandidateSourceArrayByName();

        $billModel = new Bills();
        $upload_type = $billModel->upload_type;

        $i = 0;
            
        $billsdetails['files'] = array();
        $billsFiles = BillsDoc::select('bills_doc.*')->where('bills_doc.bill_id',$id)->get();

        $utils = new Utils();

        if(isset($billsFiles) && sizeof($billsFiles) > 0){
            foreach ($billsFiles as $billfile) {
                $billsdetails['files'][$i]['id'] = $billfile->id;
                $billsdetails['files'][$i]['fileName'] = $billfile->file;
                $billsdetails['files'][$i]['url'] = "../../".$billfile->file;
                $billsdetails['files'][$i]['name'] = $billfile->name ;
                $billsdetails['files'][$i]['size'] = $utils->formatSizeUnits($billfile->size);
                $billsdetails['files'][$i]['category'] = $billfile->category;

                if (array_search($billfile->category, $upload_type)) {
                    unset($upload_type[array_search($billfile->category, $upload_type)]);
                }

                $i++;
            }
        }

        $upload_type['Others'] = 'Others';

        return view('adminlte::bills.edit', compact('bnm', 'action', 'employee_name', 'employee_percentage','generate_bm','jobopen','job_id','candidate_id','users','candidateSource','billsdetails','status','lead_name','lead_percentage','doj','upload_type','percentage_charged'));
    }

    public function downloadExcel() {

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

    public function getClientInfo() {

        $job_id = $_GET['job_id'];

        // get client info
        $client = ClientBasicinfo::getClientInfoByJobId($job_id);

        echo json_encode($client);exit;
    }

    public function getCandidateInfo() {

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

    // Joining Confirmation Mail
    public function getSendConfirmationMail($id) {
        
        $user_id = \Auth::user()->id;

        $account_userid = getenv('ACCOUNTANTUSERID');
        //$operationsexecutiveuserid = getenv('OPERATIONSEXECUTIVEUSERID');
        $superadmin_userid = getenv('SUPERADMINUSERID');

        $accountantemail = User::getUserEmailById($account_userid);
        //$operationsexecutivemail = User::getUserEmailById($operationsexecutiveuserid);
        $superadminemail = User::getUserEmailById($superadmin_userid);
        
        /*$cc_users_array = array($operationsexecutivemail,$superadminemail);
        $cc_users_array = array_filter($cc_users_array);*/

        $join_mail = Bills::getJoinConfirmationMail($id);
        $candidate_name = $join_mail['candidate_name'];
        $candidate_id = $join_mail['candidate_id'];

        $module = "Joining Confirmation";
        $sender_name = $user_id;
        $to = $accountantemail;
        //$cc = implode(",",$cc_users_array);
        $cc = $superadminemail;
        $subject = "Joining Confirmation of - ". $candidate_name;
        $message = "Joining Confirmation of - ". $candidate_name;
        $module_id = $id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        \DB::statement("UPDATE bills SET joining_confirmation_mail = '1' where id=$id");

        // Get Selected Year
        $year = $_POST['year'];

        return redirect('/recovery')->with('success','Joining Confirmation Mail Send Successfully.')
        ->with('selected_year',$year);
    }

    // Got Confirmation Check
    public function getGotConfirmation($id) {

        \DB::statement("UPDATE bills SET joining_confirmation_mail = '2' where id=$id");

        // Get Selected Year
        $year = $_POST['year'];

        return redirect('/recovery')
        ->with('success','Got Confirmation Successfully.')
        ->with('selected_year',$year);
    }

    // Generate Invoice and send mail to SA & Acc
    public function getInvoiceGenerate($id) {

        // Get Selected Year
        $year = $_POST['year'];

        $bill_id = $_POST['id'];
        $invoice_data = Bills::getJoinConfirmationMail($bill_id);

        // Set invoice name
        $date = date('dmY');
        $invoice_name = $invoice_data['client_company_name'] . " - " . $invoice_data['candidate_name'] . " - " . $date;
        
        if(isset($invoice_data['gst_no']) && $invoice_data['gst_no'] == '') {

            return redirect('/recovery')->with('error','Please add GST No. of Client to Generate the Invoice.')
            ->with('selected_year',$year);
        }

        // Generate excel sheet and save at bill id location
        Excel::create($invoice_name, function($excel) use ($invoice_data) {

            $excel->sheet('Sheet 1', function($sheet) use ($invoice_data) {

                $sheet->loadView('adminlte::bills.sheet')->with('invoice_data', $invoice_data)
                ->getStyle('B6')
                ->getAlignment()
                ->setWrapText(true);

                $sheet->getStyle('G6')
                ->getAlignment()
                ->setWrapText(true);
               
                $sheet->getStyle('K5')
                ->getAlignment()
                ->setWrapText(true);

                $sheet->getStyle('C11')
                ->getAlignment()
                ->setWrapText(true);

                $sheet->getStyle('C23')
                ->getAlignment()
                ->setWrapText(true);

            });
        })->store('xls', public_path('uploads/bills/'.$id));


        $file_path = 'uploads/bills/'.$id.'/'.$invoice_name.'.xls';

        $bills_doc = new BillsDoc();
        $bills_doc->bill_id = $id;
        $bills_doc->category = "Invoice";
        $bills_doc->file = $file_path;
        $bills_doc->name = $invoice_name.'.xls';
        $bills_doc->size = '';
        $bills_doc->save();

        // Generate PDF and save at bill id location
        
        /*$pdf = PDF::loadView('adminlte::bills.pdfview', compact('invoice_data'));
        //$customPaper = array(0,0,800,750);
        $pdf->setPaper('A4', 'portrait');
        $pdf->save(public_path('uploads/bills/'.$id.'/'.$id.'_Invoice.pdf'));*/

        $user_id = \Auth::user()->id;

        $account_userid = getenv('ACCOUNTANTUSERID');
        //$operationsexecutiveuserid = getenv('OPERATIONSEXECUTIVEUSERID');
        $superadmin_userid = getenv('SUPERADMINUSERID');
        
        $accountantemail = User::getUserEmailById($account_userid);
        //$operationsexecutivemail = User::getUserEmailById($operationsexecutiveuserid);
        $superadminemail = User::getUserEmailById($superadmin_userid);
        
        /*$cc_users_array = array($operationsexecutivemail,$superadminemail);
        $cc_users_array = array_filter($cc_users_array);*/

        $join_mail = Bills::getJoinConfirmationMail($id);
        $candidate_name = $join_mail['candidate_name'];

        $module = "Invoice Generate";
        $sender_name = $user_id;
        $to = $accountantemail;
        //$cc = implode(",",$cc_users_array);
        $cc = $superadminemail;
        $subject = "Generated Invoice of - ". $candidate_name;
        $message = "Generated Invoice of - ". $candidate_name;
        $module_id = $id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        \DB::statement("UPDATE bills SET joining_confirmation_mail = '3' where id=$id");

        return redirect('/recovery')->with('success','Invoice Generated and Mailed Successfully.')
        ->with('selected_year',$year);
    }

    // Payment received or not
    public function getPaymentReceived($id) {

        \DB::statement("UPDATE bills SET joining_confirmation_mail = '4' where id=$id");

        // Set Bill joining success date to current date
        $current_dt = date('Y-m-d');
        \DB::statement("UPDATE bills_date SET joining_success_date = '$current_dt' where bills_id = $id");

        // Get Selected Year
        $year = $_POST['year'];

        return redirect('/recovery')->with('success','Payment Received Successfully.')->with('selected_year',$year);
    }

    /*public function DownloadInvoicePDF($id) {

        $invoice_data = Bills::getJoinConfirmationMail($id);
        $pdf = PDF::loadView('adminlte::bills.pdfview', compact('invoice_data'));
        //$customPaper = array(0,0,800,750);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download($id.'_Invoice'.'.pdf');
    }*/

    public function DownloadInvoiceExcel($id) {

        $invoice_data = Bills::getJoinConfirmationMail($id);

        // Get invoice name
        $bill_invoice = BillsDoc::getBillInvoice($id,'Invoice');
        $invoice_name = $bill_invoice['name'];

        // Generate excel sheet and save at bill id location
        Excel::create($invoice_name, function($excel) use ($invoice_data) {

            $excel->sheet('Sheet 1', function($sheet) use ($invoice_data) {

                $sheet->loadView('adminlte::bills.sheet')->with('invoice_data', $invoice_data)
                ->getStyle('B6')
                ->getAlignment()
                ->setWrapText(true);

                $sheet->getStyle('G6')
                ->getAlignment()
                ->setWrapText(true);
               
                $sheet->getStyle('K5')
                ->getAlignment()
                ->setWrapText(true);

                $sheet->getStyle('C11')
                ->getAlignment()
                ->setWrapText(true);

                $sheet->getStyle('C23')
                ->getAlignment()
                ->setWrapText(true);

            });
        })->download('xls');
    }

    public function addPercentageCharged() {

        $forecasting = Bills::getAllBills(0,1);
        $recovery = Bills::getAllBills(1,1);
        $today_date = date('Y-m-d');

        $bills = array_merge($forecasting,$recovery);

        if (isset($bills) && sizeof($bills)>0) {

            foreach ($bills as $key => $value) {

                $bill_id = $value['id'];
                $client_id = $value['client_id'];
                $bill_percentage_charged = $value['percentage_charged'];

                $client_data = ClientBasicinfo::getClientDetailsById($client_id);

                if(isset($client_data['percentage_charged']) && $client_data['percentage_charged'] > 0){

                    $percentage_charged = $client_data['percentage_charged'];
                }
                else {

                    $percentage_charged = '0.00';
                }

                if($bill_percentage_charged == '0.00' || $bill_percentage_charged == '' || $bill_percentage_charged == '0') {

                    if (isset($percentage_charged) && $percentage_charged != '' && $percentage_charged != '0.00' && $percentage_charged != '0') {

                        \DB::statement("UPDATE bills SET percentage_charged = '$percentage_charged' where id=$bill_id");
                        \DB::statement("UPDATE bills SET per_chared_date = '$today_date' where id=$bill_id");

                        echo $bill_id . "-> Success";
                    }
                    else {
                        \DB::statement("UPDATE bills SET percentage_charged = '8.33' where id=$bill_id");
                        
                        \DB::statement("UPDATE bills SET per_chared_date = '$today_date' where id=$bill_id");

                        echo $bill_id . "-> Success";
                    }
                }
            }
        }
        exit;
    }
}
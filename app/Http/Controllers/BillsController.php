<?php

namespace App\Http\Controllers;

use App\Bills;
use App\BillsDoc;
use App\BillsEffort;
use Illuminate\Http\Request;
use App\Date;
use Illuminate\Support\Facades\Input;
use Excel;

class BillsController extends Controller
{
    public function index()
    {

        $bnm = Bills::getAllBills(0);
//print_r($bnm);exit;
        return view('adminlte::bills.index', compact('bnm'));
    }

    public function billsMade(){
        $bnm = Bills::getAllBills(1);
        return view('adminlte::bills.index', compact('bnm'));
    }

    public function create()
    {
        $action = 'add';
        $generate_bm = '0';

        $employee_name = array();
        $employee_percentage = array();

        $employee_name[0] = \Auth::user()->name;
        $employee_percentage[0] = '100';
        for ($i = 1; $i < 5; $i++) {
            $employee_name[$i] = '';
            $employee_percentage[$i] = '';
        }

        return view('adminlte::bills.create', compact('action', 'employee_name', 'employee_percentage','generate_bm'));
    }

    public function store(Request $request)
    {

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        $upload_documents = $request->file('upload_documents');

        $input = $request->all();

        $company_name = $input['company_name'];
        $candidate_contact_number = $input['candidate_contact_number'];
        $date_of_joining = $input['date_of_joining'];
        $fixed_salary = $input['fixed_salary'];
        $source = $input['source'];
        $client_contact_number = $input['client_contact_number'];
        $candidate_name = $input['candidate_name'];
        $designation_offered = $input['designation_offered'];
        $job_location = $input['job_location'];
        $percentage_charged = $input['percentage_charged'];
        $client_name = $input['client_name'];
        $client_email_id = $input['client_email_id'];
        $address_of_communication = $input['address_of_communication'];

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
        foreach ($employee_name as $k => $v) {
            if ($v != '') {
                $employee_final[$v] = $employee_percentage[$k];
            }
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
        $bill->percentage_charged = $percentage_charged;
        $bill->client_name = $client_name;
        $bill->client_email_id = $client_email_id;
        $bill->address_of_communication = $address_of_communication;
        $bill->status = 0; // 0- BNM
        $bill->remarks = '';
        $bill->uploaded_by = $user_id;

        $validator = \Validator::make(Input::all(),$bill::$rules);

        if($validator->fails()){
            return redirect('bnm/create')->withInput(Input::all())->withErrors($validator->errors());
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

        return redirect()->route('bnm.index')->with('success', 'Bills Created Successfully');
    }

    public function edit($id)
    {
        $generate_bm ='0';
        $bnm = Bills::find($id);

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

        return view('adminlte::bills.edit', compact('bnm', 'action', 'employee_name', 'employee_percentage','generate_bm'));

    }

    public function update(Request $request, $id)
    {
        $dateClass = new Date();
        $user_id = \Auth::user()->id;

        $input = $request->all();

        $company_name = $input['company_name'];
        $candidate_contact_number = $input['candidate_contact_number'];
        $date_of_joining = $input['date_of_joining'];
        $fixed_salary = $input['fixed_salary'];
        $source = $input['source'];
        $client_contact_number = $input['client_contact_number'];
        $candidate_name = $input['candidate_name'];
        $designation_offered = $input['designation_offered'];
        $job_location = $input['job_location'];
        $percentage_charged = $input['percentage_charged'];
        $client_name = $input['client_name'];
        $client_email_id = $input['client_email_id'];
        $address_of_communication = $input['address_of_communication'];
        $generateBM = $input['generateBM'];
        $status=0;
        if($generateBM==1){
            $status = 1;
        }

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
        foreach ($employee_name as $k => $v) {
            if ($v != '') {
                $employee_final[$v] = $employee_percentage[$k];
            }
        }

        $bill = Bills::find($id);

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
        $bill->percentage_charged = $percentage_charged;
        $bill->client_name = $client_name;
        $bill->client_email_id = $client_email_id;
        $bill->address_of_communication = $address_of_communication;
        $bill->status = $status; // 0- BNM , 1- BM
        $bill->remarks = '';
        $bill->uploaded_by = $user_id;

        $validator = \Validator::make(Input::all(),$bill::$rules);

        if($validator->fails()){
            return redirect('bnm/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }
        else{

            $bill_response = $bill->save();
            foreach ($employee_final as $k => $v) {
                $bill_efforts = new BillsEffort();

                $bill_efforts->bill_id = $id;
                $bill_efforts->employee_name = $k;
                $bill_efforts->employee_percentage = $v;

                $bill_efforts->save();
            }
        }



        return redirect()->route('bnm.index')->with('success', 'BNM Updated Successfully');
    }

    public function generateBM($id){

        $generate_bm = '1';

        $bnm = Bills::find($id);

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

        return view('adminlte::bills.edit', compact('bnm', 'action', 'employee_name', 'employee_percentage','generate_bm'));

    }

    public function downloadExcel(){

        $ids = $_POST['ids'];

        $response = Bills::getBillsByIds($ids);

        Excel::create('Laravel Excel', function($excel) use ($response){

            $excel->sheet('Excel sheet', function($sheet) use ($response) {

                $sheet->setOrientation('landscape');

            });

        })->download('xls');

        return;

    }

}
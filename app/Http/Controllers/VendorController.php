<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\State;
use App\VendorBasicInfo;
use App\VendorBankDetails;
use App\VendorDoc;
use App\Date;
use Illuminate\Support\Facades\Input;
use DB;
use Excel;
use App\Utils;

class VendorController extends Controller
{
    //
    public function index()
    {
        $vendor_array = array();
        $vendors=VendorBasicInfo::select('id','name','mobile','mail','contact_point');

        $vendors=$vendors->orderBy('id','DESC')->get();
        $i = 0;
        foreach($vendors as $vendor){

            $vendor_array[$i]['id'] = $vendor->id;
            $vendor_array[$i]['name'] = $vendor->name;
            $vendor_array[$i]['mobile'] = $vendor->mobile;
            $vendor_array[$i]['mail']= $vendor->mail;
            $vendor_array[$i]['contact_point'] = $vendor->contact_point;

            $i++;
        }

        $count=sizeof($vendors);
        return view('adminlte::vendor.index',compact('vendor_array','count'));
    }
    public function create()
    {

        $generate_lead = '1';

        $state_res = State::orderBy('state_id','ASC')->get();
        $state = array();

        $action = "add" ;

        if(sizeof($state_res)>0){
            foreach($state_res as $r){
                $state[$r->state_id]=$r->state_nm;
            }
        }

        $state_id = '';


        $acc_type = VendorBasicInfo::getTypeArray();

        $gst_charge = VendorBasicInfo::getGSTChargeArray();

       return view('adminlte::vendor.create',compact('state','state_id','generate_lead','action','acc_type','gst_charge'));
    }
    public function edit($id)
    {
        $state_res = State::orderBy('state_id','ASC')->get();
        $state = array();

        if(sizeof($state_res)>0){
            foreach($state_res as $s){
                $state[$s->state_id]=$s->state_nm;
            }
        }

        $acc_type = VendorBasicInfo::getTypeArray();

        $gst_charge = VendorBasicInfo::getGSTChargeArray();

        $vendor = array();
        $vendor_basicinfo  = \DB::table('vendor_basicinfo')
            ->leftjoin('vendor_bank_details', 'vendor_bank_details.vendor_id', '=', 'vendor_basicinfo.id')
            ->leftjoin('state', 'state.state_id', '=', 'vendor_basicinfo.state_id')
            ->select('vendor_basicinfo.*','vendor_basicinfo.address as vendor_address','state.state_nm as state_name','vendor_bank_details.*','vendor_bank_details.address as bank_address')
            ->where('vendor_basicinfo.id','=',$id)
            ->get();

            /*print_r($vendor_basicinfo);
            exit;*/

            foreach ($vendor_basicinfo as $key=>$value){
            $vendor['name'] = $value->name;
            $vendor['mobile'] = $value->mobile;
            $vendor['landline'] = $value->landline;
            $vendor['email'] = $value->mail;
            $vendor['contact'] = $value->contact_point;
            $vendor['designation'] = $value->designation;
            $vendor['organization'] = $value->organization_type;
            $vendor['website'] = $value->website;
            $vendor['vendor_address'] = $value->vendor_address;
            $vendor['pincode'] = $value->pincode;
            $vendor['gst_no'] = $value->gst_in;
            $vendor['gst_charge']=$value->gst_charge;
            $vendor['pan_no'] = $value->pan_no;
            $state_id=$value->state_id;
            $vendor['bank_name']=$value->bank_name;
            $vendor['bank_address']=$value->bank_address;
            $vendor['account']=$value->acc_no;
            $vendor['ifsc']=$value->ifsc_code;
            $vendor['acc_type']=$value->acc_type;
            $vendor['nicr']=$value->nicr_no;      

        }

        $vendor['id'] = $id;
        $vendor = (object)$vendor;
        $action = "edit" ;

        return view('adminlte::vendor.edit',compact('action','state','vendor','state_id','acc_type','gst_charge'));
    }
     public function store(Request $request)
    {

        $vendor = new VendorBasicInfo;
        $vendor->name = Input::get('name');
        $vendor->address = Input::get('vendor_address');

        $pincode = Input::get('pincode');
    
        if(isset($pincode) && $pincode!='')
        {
            $vendor->pincode = $pincode;
        }
        else
        {
            $vendor->pincode = '0';
        }
        
        $vendor->mobile = Input::get('mobile');
        $vendor->landline = Input::get('landline');
        $vendor->mail = Input::get('email');
        $vendor->contact_point = Input::get('contact');
        $vendor->designation = Input::get('designation');
        $vendor->organization_type = Input::get('organization');
        $vendor->website = Input::get('website');
        $vendor->gst_in = Input::get('gst_no');

        $gst_charge = Input::get('gst_charge');

        if(isset($gst_charge) && $gst_charge!='')
        {
            $vendor->gst_charge = $gst_charge;
        }
        else
        {
            $vendor->gst_charge = '0';
        }
        
        $vendor->pan_no = Input::get('pan_no');
        $vendor->state_id = Input::get('state_id');
        $vendor->created_at = time();
        $vendor->updated_at = time();
        
        $validator = \Validator::make(Input::all(),$vendor::$rules);

        if($validator->fails())
        {
            return redirect('vendor/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $vendor->save();

        $vendor_id=$vendor->id;

        $vendor_bank= new VendorBankDetails;
        $vendor_bank->bank_name = Input::get('bank_name');
        $vendor_bank->address = Input::get('bank_address');
        $vendor_bank->acc_no = Input::get('account');
        $vendor_bank->acc_type = Input::get('acc_type');
        $vendor_bank->ifsc_code = Input::get('ifsc');

        $nicr_no = Input::get('nicr');

        if(isset($nicr_no) && $nicr_no!='')
        {
            $vendor_bank->nicr_no = $nicr_no;
        }
        else
        {
            $vendor_bank->nicr_no = '0';
        }
        
        $vendor_bank->vendor_id = $vendor_id;

        $validator = \Validator::make(Input::all(),$vendor_bank::$rules);

        if($validator->fails())
        {
            return redirect('vendor/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $vendor_bank->save();

        $vendor_id=$vendor->id;

          $document1=$request->file('document1');
          $document2=$request->file('document2');
          $document3=$request->file('document3');


        if (isset($document1) && $document1->isValid()) 
        {
                    
            $file_name_doc1 = $document1->getClientOriginalName();

      
            $file_size_doc1 = fileSize($document1);

            //$extention = File::extension($file_name);
            $dir = "uploads/vendor/" . $vendor_id . '/';
            $vendor_doc1_key = "uploads/vendor/".$vendor_id."/".$file_name_doc1;

            $file_path = $dir . $file_name_doc1;

            if (!file_exists($dir) && !is_dir($dir)) 
            {
                mkdir("uploads/vendor/$vendor_id", 0777, true);
                chmod($dir, 0777);
            }

            if(!$document1->move($dir, $file_name_doc1))
            {
                return false;
            }
            else
            {
                $vendor_doc = new VendorDoc();
                $vendor_doc->vendor_id = $vendor_id;
                $vendor_doc->file = $file_path;
                $vendor_doc->name = $file_name_doc1;
                $vendor_doc->size = $file_size_doc1;
                $vendor_doc->created_at = date('Y-m-d');
                $vendor_doc->updated_at = date('Y-m-d');
                $vendor_doc->save();
            }

        }

        if (isset($document2) && $document2->isValid()) 
        {
                    
            $file_name_doc2 = $document2->getClientOriginalName();

      
            $file_size_doc2 = fileSize($document2);

            //$extention = File::extension($file_name);
            $dir = "uploads/vendor/" . $vendor_id . '/';
            $vendor_doc2_key = "uploads/vendor/".$vendor_id."/".$file_name_doc2;

            $file_path = $dir . $file_name_doc2;

            if (!file_exists($dir) && !is_dir($dir)) 
            {
                mkdir("uploads/vendor/$vendor_id", 0777, true);
                chmod($dir, 0777);
            }

            if(!$document2->move($dir, $file_name_doc2))
            {
                return false;
            }
            else
            {
                $vendor_doc = new VendorDoc();
                $vendor_doc->vendor_id = $vendor_id;
                $vendor_doc->file = $file_path;
                $vendor_doc->name = $file_name_doc2;
                $vendor_doc->size = $file_size_doc2;
                $vendor_doc->created_at = date('Y-m-d');
                $vendor_doc->updated_at = date('Y-m-d');
                $vendor_doc->save();
            }

        }


        if (isset($document3) && $document3->isValid()) 
        {
                    
            $file_name_doc3 = $document3->getClientOriginalName();

      
            $file_size_doc3 = fileSize($document3);

            //$extention = File::extension($file_name);
            $dir = "uploads/vendor/" . $vendor_id . '/';
            $vendor_doc3_key = "uploads/vendor/".$vendor_id."/".$file_name_doc3;

            $file_path = $dir . $file_name_doc3;

            if (!file_exists($dir) && !is_dir($dir)) 
            {
                mkdir("uploads/vendor/$vendor_id", 0777, true);
                chmod($dir, 0777);
            }

            if(!$document3->move($dir, $file_name_doc3))
            {
                return false;
            }
            else
            {
                $vendor_doc = new VendorDoc();
                $vendor_doc->vendor_id = $vendor_id;
                $vendor_doc->file = $file_path;
                $vendor_doc->name = $file_name_doc3;
                $vendor_doc->size = $file_size_doc3;
                $vendor_doc->created_at = date('Y-m-d');
                $vendor_doc->updated_at = date('Y-m-d');
                $vendor_doc->save();
            }

        }

        return redirect()->route('vendor.index')->with('success','Vendor Created Successfully');
    }

    /*
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response*/
    public function show($id)
    {
        $vendor = array();
        $vendor_basicinfo  = \DB::table('vendor_basicinfo')
            ->leftjoin('vendor_bank_details', 'vendor_bank_details.vendor_id', '=', 'vendor_basicinfo.id')
            ->leftjoin('state', 'state.state_id', '=', 'vendor_basicinfo.state_id')
            ->select('vendor_basicinfo.*','vendor_basicinfo.address as vendor_address','state.state_nm as state_name','vendor_bank_details.*','vendor_bank_details.address as bank_address')
            ->where('vendor_basicinfo.id','=',$id)
            ->get();


        $vendor['id'] = $id;
/*       print_r($vendor_basicinfo);
        exit;*/

        foreach ($vendor_basicinfo as $key=>$value){

            $vendor['name'] = $value->name;
            $vendor['mobile'] = $value->mobile;
            $vendor['landline'] = $value->landline;
            $vendor['mail'] = $value->mail;
            $vendor['contact_point'] = $value->contact_point;
            $vendor['designation'] = $value->designation;
            $vendor['organization_type'] = $value->organization_type;
            $vendor['website'] = $value->website;
            $vendor['vendor_address'] = $value->vendor_address;
            $vendor['pincode'] = $value->pincode;
            $vendor['gst_in'] = $value->gst_in;
            $vendor['gst_charge']=$value->gst_charge;
            $vendor['pan_no'] = $value->pan_no;
            $vendor['state_nm'] = $value->state_name;
            $vendor['bank_name']=$value->bank_name;
            $vendor['bank_address']=$value->bank_address;
            $vendor['acc_no']=$value->acc_no;
            $vendor['ifsc_code']=$value->ifsc_code;
            $vendor['acc_type']=$value->acc_type;
            $vendor['nicr_no']=$value->nicr_no;      

        }

        $i=0;
        $vendor['doc']=array();

        $vendor_doc=\DB::table('vendor_doc')
                    ->select('vendor_doc.*')
                    ->where('vendor_id','=',$id)
                    ->get();


        $utils = new Utils();

        foreach($vendor_doc as $key=>$value)
        {
            $vendor['doc'][$i]['name'] = $value->name ;
            $vendor['doc'][$i]['id'] = $value->id;
            $vendor['doc'][$i]['url'] = "../".$value->file;
            $vendor['doc'][$i]['size'] = $utils->formatSizeUnits($value->size);
            $i++;
        }

        $vendor_upload_type['Others'] = 'Others';

        return view('adminlte::vendor.show',compact('vendor','vendor_upload_type'));
    }   
    public function update(Request $request, $id)
    {     
        $input = $request->all();
        $input = (object)$input;

        $vendor_basicinfo = VendorBasicInfo::find($id);

        $vendor_basicinfo->name=$input->name;
        $vendor_basicinfo->mobile=$input->mobile;
        $vendor_basicinfo->landline=$input->landline;
        $vendor_basicinfo->mail=$input->email;
        $vendor_basicinfo->contact_point=$input->contact;
        $vendor_basicinfo->designation=$input->designation;
        $vendor_basicinfo->organization_type=$input->organization;
        $vendor_basicinfo->website=$input->website;
        $vendor_basicinfo->address=$input->vendor_address;

        $pincode = $input->pincode;
    
        if(isset($pincode) && $pincode!='')
        {
            $vendor_basicinfo->pincode = $pincode;
        }
        else
        {
           $vendor_basicinfo->pincode = '0';
        }

        $vendor_basicinfo->gst_in=$input->gst_no;

        $gst_charge = $input->gst_charge;

        if(isset($gst_charge) && $gst_charge!='')
        {
            $vendor_basicinfo->gst_charge = $gst_charge;
        }
        else
        {
            $vendor_basicinfo->gst_charge = '0';
        }

        $vendor_basicinfo->pan_no=$input->pan_no;
        $vendor_basicinfo->state_id=$input->state_id;

        $validator = \Validator::make(Input::all(),$vendor_basicinfo::$rules);

        if($validator->fails()){
            return redirect('vendor/create')->withInput(Input::all())->withErrors($validator->errors());
        }


        $vendor_basicinfo->save();

        if($vendor_basicinfo)
        {
            $vendor_id=$vendor_basicinfo->id;

            $vendor_bank=VendorBankDetails::where('vendor_id','=',$vendor_id)->first();

            if(!isset($vendor_bank)){
                    $vendor_bank = new VendorBankDetails();
                }
                $vendor_bank->vendor_id=$vendor_id;
                $vendor_bank->bank_name = $input->bank_name;
                $vendor_bank->address = $input->bank_address;
                $vendor_bank->acc_no = $input->account;
                $vendor_bank->acc_type = $input->acc_type;
                $vendor_bank->ifsc_code = $input->ifsc;

                $nicr_no =  $input->nicr;

                if(isset($nicr_no) && $nicr_no!='')
                {
                   $vendor_bank->nicr_no = $nicr_no;
                }
                else
                {
                   $vendor_bank->nicr_no = '0';
                }
                    
                }

        $validator = \Validator::make(Input::all(),$vendor_bank::$rules);

        if($validator->fails()){
            return redirect('vendor/create')->withInput(Input::all())->withErrors($validator->errors());
        }
        $vendor_bank->save();

        return redirect()->route('vendor.index')->with('success','Vendor Updated Successfully');
    }


    public function destroy($id)
    {
        $vendor_expense=\DB::table('vendor_basicinfo')
        ->leftjoin('expense','expense.vendor_id','=','vendor_basicinfo.id')
        ->select('vendor_basicinfo.*','expense.vendor_id as v_id')
        ->where('vendor_id','=',$id)->first();

       /* print_r($vendor_expense);
        exit;*/


        if(isset($vendor_expense))
        {
               return redirect()->route('vendor.index')->with('error','Cannot Delete Vendor Because Expense is Added for the same.');
        }
        else
        {
            $vendor_attach=\DB::table('vendor_doc')->select('file','vendor_id')->where('vendor_id','=',$id)->first();

            if(isset($vendor_attach))
            {
                $path="uploads/vendor/" . $vendor_attach->vendor_id;

                $files=glob($path . "/*");

                foreach($files as $file)
                {
                    if(is_file($file))
                    {
                        unlink($file);
                    }
                }

                $vendor_id=$vendor_attach->vendor_id;
                $path1="uploads/vendor/". $vendor_id . "/";
                rmdir($path1);

                $vendor_bank=VendorBankDetails::where('vendor_id','=',$id)->delete();
                $vendor_doc=VendorDoc::where('vendor_id','=',$id)->delete();
                $vendor = VendorBasicInfo::where('id',$id)->delete();
            }
        
            else
            {
                  $vendor_bank=VendorBankDetails::where('vendor_id','=',$id)->delete();
                 $vendor = VendorBasicInfo::where('id',$id)->delete();
            }
        }
        
        return redirect()->route('vendor.index')->with('success','Vendor Deleted Successfully');
    }


    public function upload(Request $request)
    {
        $vendor_upload_type = $request->vendor_upload_type;
        $file = $request->file('file');
        $id = $request->id;

        if (isset($file) && $file->isValid()) 
        {
            $doc_name = $file->getClientOriginalName();
            $doc_filesize = filesize($file);

            $dir_name = "uploads/vendor/".$id."/";
            $others_doc_key = "uploads/vendor/".$id."/".$doc_name;

            if (!file_exists($dir_name)) 
            {
                mkdir("uploads/vendor/$id", 0777,true);
            }

            if(!$file->move($dir_name, $doc_name))
            {
                return false;
            }
            else
            {
                $vendor_doc = new VendorDoc();
                $vendor_doc->vendor_id = $id;
                $vendor_doc->file = $others_doc_key;
                $vendor_doc->name = $doc_name;
                $vendor_doc->size = $doc_filesize;
                $vendor_doc->created_at = date('Y-m-d');
                $vendor_doc->updated_at = date('Y-m-d');
                $vendor_doc->save();

            }

        }

        return redirect()->route('vendor.show',[$id])->with('success','Attachment Uploaded Successfully');

    }
    public function attachmentsDestroy($docid)
    {
        $vendor_attach=\DB::table('vendor_doc')
        ->select('vendor_doc.*')
        ->where('id','=',$docid)->first();

        if(isset($vendor_attach))
        {
            $path="uploads/vendor/".$vendor_attach->vendor_id . "/" . $vendor_attach->name;

            unlink($path);

            $id=$vendor_attach->vendor_id;
    
            $vendor_doc=VendorDoc::where('id','=',$docid)->delete();
        }

        return redirect()->route('vendor.show',[$id])->with('success','Attachment Deleted Successfully');
    }

    public function importExport(){

        return view('adminlte::vendor.import');
    }

     public function importExcel(Request $request){

        if($request->hasFile('import_file')){

            $path = $request->file('import_file')->getRealPath();

            $data = Excel::load($path, function ($reader) {})->get();

            $messages = array();

            if(!empty($data) && $data->count()){

                foreach($data->toArray() as $key => $value){

                    if(!empty($value)){

                        foreach($value as $v){

                            $srno=$v['srno'];
                            $name=$v['name'];
                            $add=$v['address'];
                            $pin=$v['pincode'];
                            $mobile=$v['mobile'];
                            $landline=$v['landline'];
                            $email=$v['email'];
                            $con_point=$v['contactpoint'];
                            $designation=$v['designation'];
                            $org_type=$v['organization'];
                            $gst=$v['gst'];
                            $gstcharge=$v['gstcharge'];
                            $pan=$v['pan'];
                            $state=$v['state'];
                            $bank_nm=$v['bank'];
                            $bank_addr=$v['bankaddress'];
                            $acc_no=$v['accountno'];
                            $acc_type=$v['acctype'];
                            $ifsc_code=$v['ifsc'];
                            $nicr_no=$v['nicr'];

                            $vendor_cnt = VendorBasicInfo::checkVendorByEmail($email);

                            if($vendor_cnt>0){
                                $messages[] = "Record $srno Already Present ";
                            }
                            else{

                                $vendor_basicinfo=new VendorBasicInfo();
                                $vendor_basicinfo->name=$name;
                                $vendor_basicinfo->address=$add;
                                $vendor_basicinfo->pincode=$pin;
                                $vendor_basicinfo->mobile=$mobile;
                                $vendor_basicinfo->landline=$landline;
                                $vendor_basicinfo->mail=$email;
                                $vendor_basicinfo->contact_point=$con_point;
                                $vendor_basicinfo->designation=$designation;
                                $vendor_basicinfo->organization_type=$org_type;
                                $vendor_basicinfo->gst_in=$gst;
                                $vendor_basicinfo->gst_charge=$gstcharge;
                                $vendor_basicinfo->pan_no=$pan;

                                $state_id=State::getState($state);
                                $vendor_basicinfo->state_id=$state_id;
                                $vendor_basicinfo->save();


                                $vendor_id=$vendor_basicinfo->id;
                                $vendor_bank_details= new VendorBankDetails();

                                $vendor_bank_details->bank_name=$bank_nm;
                                $vendor_bank_details->address=$bank_addr;
                                $vendor_bank_details->acc_no=$acc_no;
                                $vendor_bank_details->acc_type=$acc_type;
                                $vendor_bank_details->ifsc_code=$ifsc_code;
                                $vendor_bank_details->nicr_no=$nicr_no;
                                $vendor_bank_details->vendor_id=$vendor_id;

                                if($vendor_bank_details->save())
                                {
                                    $messages[] = "Record $srno inserted successfully";
                                }
                                else
                                {
                                     $messages[] = "Error while inserting record $srno";  
                                }
                                
                                }
                        }
                    }
                    else
                    {
                        $messages[] = "No Data in file";
                    }
                }
            }
            return view('adminlte::vendor.import',compact('messages'));
       }
   }
}

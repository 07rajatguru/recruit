<?php

namespace App\Http\Controllers;

use App\CandidateBasicInfo;
use App\CandidateOtherInfo;
use App\CandidateUploadedResume;
use App\docParser;
use App\pdfParser;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;

class CandidateCreateFormController extends Controller
{
    //

 
    public function createf(){

        $candidateSex = CandidateBasicInfo::getTypeArray();
        $maritalStatus = CandidateBasicInfo::getMaritalStatusArray();
        $candidateSource = CandidateBasicInfo::getCandidateSourceArray();
        $candidateStatus = CandidateBasicInfo::getCandidateStatusArray();

        $viewVariable = array();
        $viewVariable['candidateSex'] = $candidateSex;
        $viewVariable['maritalStatus'] = $maritalStatus;
        $viewVariable['candidateSource'] = $candidateSource;
        $viewVariable['candidateStatus'] = $candidateStatus;
        $viewVariable['emailDisabled'] = '';
        $viewVariable['action'] = 'add';

        return view('adminlte::candidate.createform',$viewVariable);
    }

    public function storef(Request $request){


        $candidateSex = $request->input('candidateSex');
        $candiateMaritalStatus = $request->input('maritalStatus');
        $candiateFname = $request->input('fname');
        $candiateLname = $request->input('lname');
        $candiateMobile = $request->input('mobile');
        $candiatePhone = $request->input('phone');
        $candiateFAX = $request->input('fax');
        $candiateStreet1 = $request->input('street1');
        $candiateStreet2 = $request->input('street2');
        $candiateCity = $request->input('city');
        $candiateState = $request->input('state');
        $candiateCountry = $request->input('country');
        $candiateZipCode = $request->input('zipcode');
        $candidateEmail = $request->input('email');

        $candiateHighest_qualification = $request->input('highest_qualification');
        $candiateExperience_years = $request->input('experience_years');
        $candiateExperience_months = $request->input('experience_months');
        $candiateCurrent_job_title = $request->input('current_job_title');
        $candiateCurrent_employer = $request->input('current_employer');
        $candiateExpected_salary = $request->input('expected_salary');
        $candiateCurrent_salary = $request->input('current_salary');
        $candiateSkill = $request->input('skill');
        $candiateSkype_id = $request->input('skype_id');
        $candiateStatus = $request->input('candidateStatus');
        $candidateSource = $request->input('candidateSource');

        // Save Candidate Basic Info
        $candidate = new CandidateBasicInfo();

        if(isset($candidateSex)){
            $candidate->type = $candidateSex;
        }
        if(isset($candiateMaritalStatus)){
            $candidate->marital_status = $candiateMaritalStatus;
        }
        if(isset($candiateFname)){
            $candidate->fname = $candiateFname;
        }
        if(isset($candiateFname)){
            $candidate->lname = $candiateLname;
        }
        if(isset($candidateEmail)){
            $candidate->email = $candidateEmail;
        }
        if(isset($candiateMobile)){
            $candidate->mobile = $candiateMobile;
        }
        if(isset($candiatePhone)){
            $candidate->phone = $candiatePhone;
        }
        if(isset($candiateFAX)){
            $candidate->fax = $candiateFAX;
        }
        if(isset($candiateStreet1)){
            $candidate->street1 = $candiateStreet1;
        }
        if(isset($candiateStreet2)){
            $candidate->street2 = $candiateStreet2;
        }
        if(isset($candiateCity)){
            $candidate->city = $candiateCity;
        }
        if(isset($candiateState)){
            $candidate->state = $candiateState;
        }
        if(isset($candiateCountry)){
            $candidate->country = $candiateCountry;
        }
        if(isset($candiateZipCode)){
            $candidate->zipcode = $candiateZipCode;
        }

        $validator = \Validator::make(Input::all(),$candidate::$rules);

        if($validator->fails()){
            return redirect('candidate/createform')->withInput(Input::all())->withErrors($validator->errors());
        }

        /*$candidate->type = $request->input('candidateSex');
        $candidate->marital_status = $request->input('maritalStatus');
        $candidate->fname = $request->input('fname');
        $candidate->lname = $request->input('lname');
        $candidate->email = $request->input('email');
        $candidate->mobile = $request->input('mobile');
        $candidate->phone = $request->input('phone');
        $candidate->fax = $request->input('fax');
        $candidate->street1 = $request->input('street1');
        $candidate->street2 = $request->input('street2');
        $candidate->city = $request->input('city');
        $candidate->state = $request->input('state');
        $candidate->country = $request->input('country');
        $candidate->zipcode = $request->input('zipcode');*/
        $candidateStored = $candidate->save();

        if($candidateStored){
            $candidate_id = $candidate->id;

            // Save Candidate Other Info

            $candidateOtherInfo = new CandidateOtherInfo();

            $candidateOtherInfo->candidate_id = $candidate_id;
            if(isset($candiateHighest_qualification)){
                $candidate->highest_qualification = $candiateHighest_qualification;
            }
            if(isset($candiateExperience_years)){
                $candidate->experience_years = $candiateExperience_years;
            }
            if(isset($candiateExperience_months)){
                $candidate->experience_months = $candiateHighest_qualification;
            }
            if(isset($candiateCurrent_job_title)){
                $candidate->current_job_title = $candiateCurrent_job_title;
            }
            if(isset($candiateCurrent_employer)){
                $candidate->current_employer = $candiateCurrent_employer;
            }
            if(isset($candiateExpected_salary)){
                $candidate->expected_salary = $candiateExpected_salary;
            }
            if(isset($candiateCurrent_salary)){
                $candidate->current_salary = $candiateCurrent_salary;
            }
            if(isset($candiateSkill)){
                $candidate->skill = $candiateSkill;
            }
            if(isset($candiateSkype_id)){
                $candidate->skype_id = $candiateSkype_id;
            }
            if(isset($candiateStatus)){
                $candidate->status_id = $candiateStatus;
            }
            if(isset($candidateSource)){
                $candidate->source_id = $candidateSource;
            }
            if(isset($user_id)){
                $candidate->owner_id = $user_id;
            }
            /*$candidateOtherInfo->candidate_id = $candidate_id;
            $candidateOtherInfo->highest_qualification = $request->input('highest_qualification');
            $candidateOtherInfo->experience_years = $request->input('experience_years');
            $candidateOtherInfo->experience_months = $request->input('experience_months');
            $candidateOtherInfo->current_job_title = $request->input('current_job_title');
            $candidateOtherInfo->current_employer = $request->input('current_employer');
            $candidateOtherInfo->expected_salary = $request->input('expected_salary');
            $candidateOtherInfo->current_salary = $request->input('current_salary');
            $candidateOtherInfo->skill = $request->input('skill');
            $candidateOtherInfo->skype_id = $request->input('skype_id');
            $candidateOtherInfo->status_id = $request->input('candidateStatus');
            $candidateOtherInfo->source_id = $request->input('candidateSource');
            $candidateOtherInfo->owner_id = $user_id;*/
            $candidateOtherInfoStored = $candidateOtherInfo->save();

            if($candidateOtherInfoStored){

                // Save Candidate Documentes

                $fileResume = $request->file('resume');
                $fileFormattedResume = $request->file('formatted_resume');
                $fileCoverLatter = $request->file('cover_latter');
                $fileOthers = $request->file('others');

                if(isset($fileResume) && $fileResume->isValid()){
                    $fileResumeName = $fileResume->getClientOriginalName();
                    $fileResumeExtention = $fileResume->getClientOriginalExtension();
                    $fileResumeRealPath = $fileResume->getRealPath();
                    $fileResumeSize = $fileResume->getSize();
                    $fileResumeMimeType = $fileResume->getMimeType();

                    $extention = File::extension($fileResumeName);

                    $fileResumeNameArray = explode('.',$fileResumeName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileResumeNameArray[0]);
                    $fileResumeNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileResume->move($dir,$fileResumeNewName);

                    $fileResumeNewPath = $dir.$fileResumeNewName;

                    $candidateFileUpload = new CandidateUploadedResume();
                    $candidateFileUpload->candidate_id = $candidate_id;
                    $candidateFileUpload->uploaded_by = $user_id;
                    $candidateFileUpload->file_name = $fileResumeNewName;
                    $candidateFileUpload->file_type = 'Candidate Resume';
                    $candidateFileUpload->file = $fileResumeNewPath;
                    $candidateFileUpload->mime = $fileResumeMimeType;
                    $candidateFileUpload->size = $fileResumeSize;
                    $candidateFileUpload->uploaded_date = date('Y-m-d');
                    $candidateFileUploadStored = $candidateFileUpload->save();
                }

                if(isset($fileFormattedResume) && $fileFormattedResume->isValid()){
                    $fileFormattedResumeName = $fileFormattedResume->getClientOriginalName();
                    $fileFormattedResumeExtention = $fileFormattedResume->getClientOriginalExtension();
                    $fileFormattedResumeRealPath = $fileFormattedResume->getRealPath();
                    $fileFormattedResumeSize = $fileFormattedResume->getSize();
                    $fileFormattedResumeMimeType = $fileFormattedResume->getMimeType();

                    $extention = File::extension($fileFormattedResumeName);

                    $fileFormattedResumeNameArray = explode('.',$fileFormattedResumeName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileFormattedResumeNameArray[0]);
                    $fileFormattedResumeNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileFormattedResume->move($dir,$fileFormattedResumeNewName);

                    $fileFormattedResumeNewPath = $dir.$fileFormattedResumeNewName;

                    $candidateFormattedFileUpload = new CandidateUploadedResume();
                    $candidateFormattedFileUpload->candidate_id = $candidate_id;
                    $candidateFormattedFileUpload->uploaded_by = $user_id;
                    $candidateFormattedFileUpload->file_name = $fileFormattedResumeNewName;
                    $candidateFormattedFileUpload->file_type = 'Candidate Formatted Resume';
                    $candidateFormattedFileUpload->file = $fileFormattedResumeNewPath;
                    $candidateFormattedFileUpload->mime = $fileFormattedResumeMimeType;
                    $candidateFormattedFileUpload->size = $fileFormattedResumeSize;
                    $candidateFormattedFileUpload->uploaded_date = date('Y-m-d');
                    $candidateFormattedFileUploadStored = $candidateFormattedFileUpload->save();

                }

                if(isset($fileCoverLatter) && $fileCoverLatter->isValid()){

                    $fileCoverLatterName = $fileCoverLatter->getClientOriginalName();
                    $fileCoverLatterExtention = $fileCoverLatter->getClientOriginalExtension();
                    $fileCoverLatterRealPath = $fileCoverLatter->getRealPath();
                    $fileCoverLatterSize = $fileCoverLatter->getSize();
                    $fileCoverLatterMimeType = $fileCoverLatter->getMimeType();

                    $extention = File::extension($fileCoverLatterName);

                    $fileCoverLatterNameArray = explode('.',$fileCoverLatterName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileCoverLatterNameArray[0]);
                    $fileCoverLatterNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileCoverLatter->move($dir,$fileCoverLatterNewName);

                    $fileCoverLatterNewPath = $dir.$fileCoverLatterNewName;

                    $candidateCoverLatterUpload = new CandidateUploadedResume();
                    $candidateCoverLatterUpload->candidate_id = $candidate_id;
                    $candidateCoverLatterUpload->uploaded_by = $user_id;
                    $candidateCoverLatterUpload->file_name = $fileCoverLatterNewName;
                    $candidateCoverLatterUpload->file_type = 'Candidate Cover Latter';
                    $candidateCoverLatterUpload->file = $fileCoverLatterNewPath;
                    $candidateCoverLatterUpload->mime = $fileCoverLatterMimeType;
                    $candidateCoverLatterUpload->size = $fileCoverLatterSize;
                    $candidateCoverLatterUpload->uploaded_date = date('Y-m-d');
                    $candidateCoverLatterUploadStored = $candidateCoverLatterUpload->save();

                }

                if(isset($fileOthers) && $fileOthers->isValid()){

                    $fileOthersName = $fileOthers->getClientOriginalName();
                    $fileOthersExtention = $fileOthers->getClientOriginalExtension();
                    $fileOthersRealPath = $fileOthers->getRealPath();
                    $fileOthersSize = $fileOthers->getSize();
                    $fileOthersMimeType = $fileOthers->getMimeType();

                    $extention = File::extension($fileOthersName);

                    $fileOthersNameArray = explode('.',$fileOthersName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileOthersNameArray[0]);
                    $fileOthersNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileOthers->move($dir,$fileOthersNewName);

                    $fileOthersNewPath = $dir.$fileOthersNewName;

                    $candidateOthersUpload = new CandidateUploadedResume();
                    $candidateOthersUpload->candidate_id = $candidate_id;
                    $candidateOthersUpload->uploaded_by = $user_id;
                    $candidateOthersUpload->file_name = $fileOthersNewName;
                    $candidateOthersUpload->file_type = 'Others';
                    $candidateOthersUpload->file = $fileOthersNewPath;
                    $candidateOthersUpload->mime = $fileOthersMimeType;
                    $candidateOthersUpload->size = $fileOthersSize;
                    $candidateOthersUpload->uploaded_date = date('Y-m-d');
                    $candidateOthersUploadStored = $candidateOthersUpload->save();

                }
            }
        }

        return redirect()->route('candidate.createf')->with('success','Candidate Created Successfully');
    }     

}


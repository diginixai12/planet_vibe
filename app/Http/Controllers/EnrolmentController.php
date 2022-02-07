<?php

namespace App\Http\Controllers;

use Config;
use File;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Http\Requests\EnrolmentRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendOtp;
use Auth;
use Carbon\Carbon;
use DB;
use App\Helpers\helpers;
use App\Ekyc;
use App\Stock;
use App\Enrolment;
use App\Orgenrolment;
use App\Master;
use App\User;


class EnrolmentController extends Controller
{

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['verify' => false ]);
    }

    public function apply_dsc()
    {
        return view('enrolment.apply_dsc');
    }

    //*******BY Nikhil*********//
    public function store(EnrolmentRequest $request)
    {
        $photoPath = '';
        $panPath = '';
        $addressPath = '';
        if($request->hasFile('photo_file')){
            $file=$request->file('photo_file');
            $photoName = $request->file('photo_file')->getClientOriginalName();
            $photoPath = public_path('/docs/');
            $file->move($photoPath , $photoName);
        }

        if($request->hasFile('pan_file')){
            $file=$request->file('pan_file');
            $panName = $request->file('pan_file')->getClientOriginalName();
            $panPath = public_path('/docs/');
            $file->move($panPath , $panName);
        }

        if($request->hasFile('address_file')){
            $file=$request->file('address_file');
            $addressName = $request->file('address_file')->getClientOriginalName();
            $addressPath = public_path('/docs/');
            $file->move($addressPath , $addressName);
        }

        $token1=md5(rand().$request->pan.date("H:i:s"));
        $token2=md5(rand().$request->pan.date("H:i:s"));

        $min = pow(10,4);
        $max = pow(10,4+1)-1;
        $application_id=rand($min, $max).rand();

        $ekyc = Ekyc::where('email', $request->email)->first();
         
        if (!$ekyc) {
            $ekyc = new Ekyc();
        }
            $ekyc->nationality = $request->nationality;
            $ekyc->pan = $request->pan;
            $ekyc->name = $request->name;
            $ekyc->email = $request->email;
            $ekyc->mobile = $request->mobile;
            $ekyc->birthday = $request->birthday;
            $ekyc->gender = $request->gender;
            $ekyc->pincode = $request->pincode;
            $ekyc->state = $request->state;
            $ekyc->city = $request->city;
            $ekyc->address = $request->address;
            $ekyc->remarks = $request->remark;
            $ekyc->ekyc_pin = $request->ekyc_pin;
            $ekyc->photo_file = $photoName;
            $ekyc->pan_file = $panName;
            $ekyc->address_file = $addressName;
    
            $ekyc->save();
       
        if($request->verify_later == "yes"){
            auth()->user()->enrolments()->create([
            'type' => $request->type,
            'certification_class' => $request->certification_class,
            'user_type' => $request->user_type,
            'validity' => $request->validity,
            'nationality' => $request->nationality,
            'ekyc_type' => $request->ekyc_type,
            'pan' => $request->pan,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'pincode' => $request->pincode,
            'state' => $request->state,
            'city' => $request->city,
            'address' => $request->address,
            'remark' => $request->remark,
            'ekyc_pin' => $request->ekyc_pin,
            'photo_file' => $photoName,
            'pan_file' => $panName,
            'address_file' => $addressName,
            'ekyc_token' => $token1,
            'dsc_token' => $token2,
            'application_id' => $application_id,
            'ekyc_id' => $ekyc->id
            ]);

        }
        else{
                $user = Enrolment::where([
                    'email' => $request->email,
                    'otp' => $request->otp
                ])->first();


            if($user){
              $user->type = $request->type;
              $user->certification_class = $request->certification_class;
              $user->user_type = $request->user_type;
              $user->validity = $request->validity;
              $user->nationality = $request->nationality;
              $user->ekyc_type = $request->ekyc_type;
              $user->pan = $request->pan;
              $user->name = $request->name;
              $user->email = $request->email;
              $user->mobile = $request->mobile;
              $user->birthday = $request->birthday;
              $user->gender = $request->gender;
              $user->pincode = $request->pincode;
              $user->state = $request->state;
              $user->city = $request->city;
              $user->address = $request->address;
              $user->remark = $request->remark;
              $user->ekyc_pin = $request->ekyc_pin;
              $user->photo_file = $photoName;
              $user->pan_file = $panName;
              $user->address_file = $addressName;
              $user->ekyc_token = $token1;
              $user->dsc_token = $token2;
              $user->application_id = $application_id;
              $user->ekyc_id = $ekyc->id;
              $user->save();
                }
        }
        $master = Master::where('application_id',$application_id)->first();

        if(empty($master)){
            $master = new Master();
            $master->application_id = $application_id;
            $master->ekyc_id = $ekyc->id;
            $master->save();
        }

        
        // return redirect()->route('enrolment.authentication', [$token1, $token2]);
        return redirect()->route('enrolment.steps.completed', $application_id)->with('success', 'Verification saved successfully');

    }

    public function store1(Request $request)
    {
        $photoPath = '';
        $panPath = '';
        $addressPath = '';
        $photoName = '';
        $panName = '';
        $addressName = '';
        if($request->hasFile('photo_file')){
            $file=$request->file('photo_file');
            $photoName = $request->file('photo_file')->getClientOriginalName();
            $photoPath = public_path('/docs/');
            $file->move($photoPath , $photoName);
        }

        if($request->hasFile('pan_file')){
            $file=$request->file('pan_file');
            $panName = $request->file('pan_file')->getClientOriginalName();
            $panPath = public_path('/docs/');
            $file->move($panPath , $panName);
        }

        if($request->hasFile('address_file')){
            $file=$request->file('address_file');
            $addressName = $request->file('address_file')->getClientOriginalName();
            $addressPath = public_path('/docs/');
            $file->move($addressPath , $addressName);
        }

        $token1=md5(rand().$request->pan.date("H:i:s"));
        $token2=md5(rand().$request->pan.date("H:i:s"));


         $user = Enrolment::where(['application_id' => $request->application_id])->first();

          if(!$user){
               $user = new Enrolment();
               $ekyc = Ekyc::where('email', $request->email)->first();
               $min = pow(10,4);
                $max = pow(10,4+1)-1;
                $application_id=rand($min, $max).rand();
                $pin = rand(400,900);
                $user->application_id = $application_id;

            }
            else{
                $ekyc = Ekyc::where('id', $user->ekyc_id)->first();
            }
           
            if (!$ekyc) {
                   $ekyc = new Ekyc();
            }

            $signer = $request->pan . "@pan.futuriq";
            
            if($request->signer_id == "mobile"){
                $signer = $request->mobile . "@mobile.futuriq";
            }
            elseif($request->signer_id == "custom"){
                $signer = $request->custom_username . "@username.futuriq";
            }

            $ekyc->nationality = $request->country;
            $ekyc->pan = $request->pan;
            $ekyc->name = $request->name;
            $ekyc->email = $request->email;
            $ekyc->mobile = $request->mobile;
            $ekyc->birthday = $request->birthday;
            $ekyc->gender = $request->gender;
            $ekyc->pincode = $request->pincode;
            $ekyc->state = $request->state;
            $ekyc->city = $request->city;
            $ekyc->address = $request->address;
            $ekyc->remarks = $request->remark;
            $ekyc->ekyc_pin = $request->ekyc_pin;
            $ekyc->photo_file = $photoName;
            $ekyc->pan_file = $panName;
            $ekyc->address_file = $addressName;
            $ekyc->video_pin = $pin;
            $ekyc->signer_id = $request->username;
            $ekyc->user_type = "Ind";
    
            $ekyc->save();

              $user->type = $request->type;
              $user->certification_class = $request->certification_class;
              $user->user_type = $request->user_type;
              $user->validity = $request->validity;
              $user->ekyc_type = $request->ekyc_type;
              $user->email = $request->email;
              $user->mobile = $request->mobile;
              $user->birthday = $request->birthday;
              $user->remark = $request->remark;
              $user->ekyc_pin = $request->ekyc_pin;
              $user->ekyc_token = $token1;
              $user->dsc_token = $token2;
              $user->ekyc_id = $ekyc->id;
              $user->user_id = Auth::user()->id;
              $user->save(); 
              
        //       $stock = Stock::where(['user_id' => Auth::user()->id,'stock_class' => $request->certification_class,'stock_validity' => $request->validity])->first();

        //   if(!empty($stock)){

        //           if($stock->stock_qty > 1){
        //               $stock->stock_qty = $stock->stock_qty - 1;
        //               $stock->save();
        //           }
        //           else{
        //               Stock::where('user_id',Auth::user()->id)->delete();
        //           }

        //    }
              
          $timestamp = strtotime($request->birthday);
 
        // Creating new date format from that timestamp
            $new_date = date("d-m-Y", $timestamp);
         $tauth = $this->authentication($token1, $token2, true);
         $data['new'] = [
                         'application_id' => $application_id,
                         'dob' => $new_date,
                         'ekyc_id' => $ekyc->id,
                         'name' => $request->name,
                         'token1' => $tauth['token1'],
                         'token2' => $tauth['token2'],
                         'pdf_url' => $tauth['pdfUrl'],
                         'pin' => $pin,
                         'signer' => $signer,
                         'fail' => '1',
                     ];
        
        $master = Master::where('application_id',$application_id)->first();

        if(empty($master)){
            $master = new Master();
            $master->application_id = $application_id;
            $master->ekyc_id = $ekyc->id;
            if($master->save()){
                return json_encode($data);
            }
            else{
                return 0;
            }
        }

  }

  function edit_enrolment_details(Request $request){
        $enrolment = Enrolment::where('id','=',$request->id)->first();
        $enrolment->certification_class = $request->certification_class;
        $enrolment->validity = $request->validity;
        $enrolment->type = $request->type;
        $enrolment->email = $request->email;
        $enrolment->mobile = $request->mobile;
        
        $ekyc = Ekyc::where('id',$enrolment->ekyc_id)->first();
        $ekyc->name = $request->name;
        $ekyc->pan = $request->pan;
        $ekyc->email = $request->email;
        $ekyc->mobile = $request->mobile;
        $ekyc->gender = $request->gender;
        $ekyc->birthday = $request->dob;
        $ekyc->city = $request->city;
        $ekyc->pincode = $request->pincode;
        $ekyc->state = $request->state;
        $ekyc->address = $request->address;

        $token1=md5(rand().$request->pan.date("H:i:s"));
        $token2=md5(rand().$request->pan.date("H:i:s"));

       if($request->hasFile('photo_file')){
            $file=$request->file('photo_file');
            $photoName = $request->file('photo_file')->getClientOriginalName();
            $photoPath = public_path('/docs/');
            $file->move($photoPath , $photoName);

            $ekyc->photo_file = $photoName;
        }

        if($request->hasFile('pan_file')){
            $file=$request->file('pan_file');
            $panName = $request->file('pan_file')->getClientOriginalName();
            $panPath = public_path('/docs/');
            $file->move($panPath , $panName);

            $ekyc->pan_file = $panName;
        }

        if($request->hasFile('address_file')){
            $file=$request->file('address_file');
            $addressName = $request->file('address_file')->getClientOriginalName();
            $addressPath = public_path('/docs/');
            $file->move($addressPath , $addressName);

            $ekyc->address_file = $addressName;
        }
        $ekyc->save();

        $enrolment->ekyc_token = $token1;
        $enrolment->dsc_token = $token2;
        $enrolment->document_signed = 0;
        $enrolment->save();

         $tauth = $this->authentication($token1, $token2, true);

        $data['new'] = [
                         'id' => $enrolment->id,
                         'name' => $ekyc->name,
                         'pan' => $ekyc->pan,
                         'email' => $ekyc->email,
                         'mobile' => $ekyc->mobile,
                         'certification_class' => $enrolment->certification_class,
                         'validity' =>  $enrolment->validity,
                         'application_id' =>  $enrolment->application_id,
                         'token1' => $tauth['token1'],
                         'token2' => $tauth['token2'],
                         'pdf_url' => $tauth['pdfUrl'],

                     ];

                     return json_encode($data);

        // return redirect()->route('esign_now',$enrolment->application_id);

    }

    private function generatePdf($enrolment,$ekyc, $shouldStream=false)
    {
        $pdf = PDF::loadView('certificate.offline_kyc', compact('enrolment','ekyc'));
        $path = storage_path().'/app/public/docs/'. $ekyc->pan .'/pdf';
        File::makeDirectory($path, $mode = 0755, true, true);
        $pdf->save($path . '/' . $enrolment->application_id . '.pdf');
        if ($shouldStream) {
            return $pdf->stream($enrolment->application_id.'.pdf');
        }
        return asset('storage/docs/'.$ekyc->pan.'/pdf/'.$enrolment->application_id.'.pdf');
    }

    private function generatePdfHash($enrolment,$ekyc, $pdfUrl)
    {
        $request1 = $this->client->request('POST', Config::get('sign.HASH_N_SIGN_PDF').'/rest/esignature/calculateHash', [
            'json' => [
                
                [
                    "pdfContent" => $pdfUrl,
                    "name" => $ekyc->name,
                    "reason" => "Signing of DSC application form",
                    "location" => [
                        "page" => 1,
                        "x" => 300,
                        "y" => 600,
                        "width" => 200,
                        "height" => 150
                    ]
                ]
                
            ]
        ]);
        $response1 =  json_decode($request1->getBody()->getContents());
        $hashResps = json_decode(json_encode($response1->hashResps), true);
        return $hashResps;
    }

    
    public function authentication($token1, $token2, $shouldReturn=false)
    {
        
        $enrolment = Enrolment::where(['ekyc_token' => $token1, 'dsc_token' => $token2])->first();
        $ekyc = Ekyc::where('id',$enrolment->ekyc_id)->first();

        $pdfUrl = $this->generatePdf($enrolment,$ekyc);
        $hashResps = $this->generatePdfHash($enrolment,$ekyc, $pdfUrl);
        $hashValue = $hashResps[0]["hashValue"];
        $fileID = $hashResps[0]["fileID"];
    
        $enrolment->dsc_token = $hashValue;
        $enrolment->file_id = $fileID;
        $enrolment->save();

        if ($shouldReturn) {
            return ['token1' => $token1, 'token2' => $enrolment->dsc_token, 'pdfUrl' => $pdfUrl];
        }
    
        return view('authentication', compact('token1', 'hashValue', 'enrolment'));
        
    }

    public function list()
    {
        $enrolments = auth()->user()->enrolments;
        return view('enrolment.list', compact('enrolments'));
    }

    public function dsc_list(Request $request)
    { 
        $today= '1';
        // $enrolments = Enrolment::where('ra_approval_status','=',Null)->get();
        if(!empty($request->from_date) && !empty($request->to_date)){
               $enrolments = DB::table('enrolments')
                          ->join('ekyc','ekyc.id','=','enrolments.ekyc_id')
                          ->where(function($query) use ($today) {
                                return $query->where('enrolments.ra_approval_status',null)
                                ->orWhere('enrolments.ra_approval_status','=', '2');
                            })
                          ->whereBetween('enrolments.created_at',[$request->from_date, $request->to_date])
                          ->select('enrolments.id as id','enrolments.application_id as application_id','ekyc.name as name','ekyc.email as email','ekyc.email_verified as email_verified','ekyc.mobile_verified as mobile_verified','ekyc.mobile as mobile','ekyc.pan as pan','enrolments.certification_class as certification_class',
                          'enrolments.validity as validity','enrolments.ra_approval_status as ra_approval_status','enrolments.video_file as video_file','ekyc.photo_file as photo_file','enrolments.document_signed as document_signed')
                          ->get();

        }
        else{

        $enrolments = DB::table('enrolments')
                          ->join('ekyc','ekyc.id','=','enrolments.ekyc_id')
                          ->where(function($query) use ($today) {
                                return $query->where('enrolments.ra_approval_status',null)
                                ->orWhere('enrolments.ra_approval_status','=', '2');
                            })
                          ->select('enrolments.id as id','enrolments.application_id as application_id','ekyc.name as name','ekyc.email as email','ekyc.email_verified as email_verified','ekyc.mobile_verified as mobile_verified','ekyc.mobile as mobile','ekyc.pan as pan','enrolments.certification_class as certification_class',
                          'enrolments.validity as validity','enrolments.ra_approval_status as ra_approval_status','enrolments.video_file as video_file','ekyc.photo_file as photo_file','enrolments.document_signed as document_signed')
                          ->get();

        }
                          
        return view('enrolment.dsc_list', compact('enrolments'));
    }

    //***********made by nikhil********* */
    public function org_dsc_list(Request $request)
    {                                                                               
        // $enrolments = DB::table('orgenrolments')
        //                   ->join('ekyc','ekyc.id','=','orgenrolments.authoriser_id')
        //                   ->select('orgenrolments.*','ekyc.id as e_id','ekyc.name as e_name')
        //                   ->get(); 

        // $enrolments = Orgenrolment::where('ra_approval_status','=',0)->get();
        if(!empty($request->from_date) && !empty($request->to_date)){
                $enrolments = DB::table('orgenrolments')
                          ->join('ekyc','ekyc.id','=','orgenrolments.ekyc_id')
                          ->where(function($query) use ($today) {
                                return $query->where('orgenrolments.ra_approval_status',null)
                                ->orWhere('orgenrolments.ra_approval_status','=', '2');
                            })
                          ->whereBetween('orgenrolments.created_at',[$request->from_date, $request->to_date])
                          ->select('orgenrolments.id as id','orgenrolments.orgname as orgname','orgenrolments.govorgno as govorgno','orgenrolments.application_id as application_id','ekyc.name as name','ekyc.email_verified as email_verified','ekyc.mobile_verified as mobile_verified','ekyc.email as email','ekyc.mobile as mobile','ekyc.pan as pan','orgenrolments.certification_class as certification_class','orgenrolments.certification_type as certification_type',
                          'orgenrolments.validity as validity','orgenrolments.ra_approval_status as ra_approval_status','orgenrolments.video_file as video_file','orgenrolments.pan_file as pan_file','orgenrolments.document_signed as document_signed')
                          ->get();
        }
        else{
               $enrolments = DB::table('orgenrolments')
                          ->join('ekyc','ekyc.id','=','orgenrolments.ekyc_id')
                          ->where(function($query) use ($today) {
                                return $query->where('orgenrolments.ra_approval_status',null)
                                ->orWhere('orgenrolments.ra_approval_status','=', '2');
                            })
                          ->select('orgenrolments.id as id','orgenrolments.orgname as orgname','orgenrolments.govorgno as govorgno','orgenrolments.application_id as application_id','ekyc.name as name','ekyc.email_verified as email_verified','ekyc.mobile_verified as mobile_verified','ekyc.email as email','ekyc.mobile as mobile','ekyc.pan as pan','orgenrolments.certification_class as certification_class','orgenrolments.certification_type as certification_type',
                          'orgenrolments.validity as validity','orgenrolments.ra_approval_status as ra_approval_status','orgenrolments.video_file as video_file','orgenrolments.pan_file as pan_file','orgenrolments.document_signed as document_signed')
                          ->get();
        }
        
                          
        return view('enrolment.org_dsc_list', compact('enrolments'));
    }

    public function getEnrolmentByApplicationIdSteps(Request $request, $application_id)
    {
          
          $enrolment = Enrolment::where('application_id', $application_id)->first();

          if(!empty($enrolment)){
               $ekyc = Ekyc::findOrFail($enrolment->ekyc_id);
            //    if($ekyc->email_verified == 1 && $ekyc->mobile_verified == 1 && $enrolment->document_signed == 1){
            //         $ekyc->status = 1;
            //         $ekyc->save();
            //    }
            $is_org = 0;
            $user = User::where('id',$enrolment->ca_id)->first();
               $pdfUrl = asset('storage/docs/'.trim($ekyc->pan).'/pdf/'.trim($application_id).'_signed.pdf');
               return view('enrolment.completed-steps', compact('application_id', 'ekyc', 'enrolment', 'pdfUrl','user','is_org'));
          }

          $enrolment1 = Orgenrolment::where('application_id', $application_id)->first();

          if(!empty($enrolment1)){
              $enrolment = Orgenrolment::where('application_id', $application_id)->first();
              if($enrolment->certification_class == 'DGFT'){
                 $is_org = 2;
              }
              else if($enrolment->certification_class == 'Class2DocSigner' || $enrolment->certification_class == 'Class3DocSigner'){
                 $is_org = 3;
              }
              else{
                 $is_org = 1;
              }
               $ekyc = Ekyc::findOrFail($enrolment->ekyc_id);
               if(!empty($enrolment->authoriser_id)){
                  $auth_ekyc = Ekyc::findOrFail($enrolment->authoriser_id);
               }
               else{
                   $auth_ekyc = "";
               }
               $pdfUrl = asset('storage/docs/'.trim($ekyc->pan).'/pdf/'.trim($application_id).'_signed.pdf');
               return view('enrolment.completed-steps', compact('application_id', 'ekyc', 'enrolment', 'pdfUrl','auth_ekyc','is_org'));
          }

       }

       public function getEnrolmentByApplicationIdSteps1(Request $request, $application_id)
    {
        // $enrolment = '';
        // $type = $request->type ?? 'ind';
        // switch($type) {
        //     case 'org':
        //         $enrolment = Orgenrolment::where('application_id', $application_id)->first();
        //         break;
        //     default:
        //         $enrolment = Enrolment::where('application_id', $application_id)->first();
        // }

          $enrolment = Enrolment::where('application_id', $application_id)->first();

          if(!empty($enrolment)){
               $ekyc = Ekyc::findOrFail($enrolment->ekyc_id);
            //     if($ekyc->email_verified == 1 && $ekyc->mobile_verified == 1 && $enrolment->document_signed == 1){
            //         $ekyc->status = 1;
            //         $ekyc->save();
            //    }
               $pdfUrl = asset('storage/docs/'.trim($ekyc->pan).'/pdf/'.trim($application_id).'_signed.pdf');
               return view('enrolment.ekyc_complete_steps', compact('application_id', 'ekyc', 'enrolment', 'pdfUrl'));
          }

          $enrolment1 = Orgenrolment::where('application_id', $application_id)->first();

          if(!empty($enrolment1)){
              $enrolment = Orgenrolment::where('application_id', $application_id)->first();
               $ekyc = Ekyc::findOrFail($enrolment->ekyc_id);
               $auth_ekyc = Ekyc::findOrFail($enrolment->authoriser_id);

            //     if($ekyc->email_verified == 1 && $ekyc->mobile_verified == 1 && $enrolment->document_signed == 1){
            //         $ekyc->status = 1;
            //         $ekyc->save();
            //    }
               $pdfUrl = asset('storage/docs/'.trim($ekyc->pan).'/pdf/'.trim($application_id).'_signed.pdf');
               return view('enrolment.ekyc_complete_steps', compact('application_id', 'ekyc', 'enrolment', 'pdfUrl','auth_ekyc'));
          }

       }

    public function get_offline_kyc(Request $request, $application_id)
    {
        $enrolment = Enrolment::where(['application_id' => $application_id])->first();
        return $this->generatePdf($enrolment, true);
        // return $pdf->save($path . '/' . $application_id . '.pdf')->stream($application_id.'.pdf');
    }
        //*****************made by nikhil */
   public function video_verification_store(Request $request)
    {
        $master = Master::where('application_id','=',$request->verification_application_id)->first();
        $enrolment = Ekyc::where(['id' => $master->ekyc_id])->first();
        // $enrolment->video_file = $request->verification_video_hash;
        // $enrolment->status = 0;

        if (1) {
            return 1;
        } else {
            return 1;
        }
    }

    public function video_verification_store1(Request $request)
    {
        $master = Master::where('application_id','=',$request->verification_application_id)->first();
        $ekyc = Ekyc::where(['id' => $master->ekyc_id])->first();
        $ekyc->video_pin = $request->pin;
        $ekyc->save();


        $enrolment = Enrolment::where('application_id','=',$request->verification_application_id)->first();

        if(!empty($enrolment)){
            $enrolment->ca_approval_status = Null;
            $enrolment->video_approval_status = Null;
            $enrolment->save();
        }

         $enrolment1 = OrgEnrolment::where('application_id','=',$request->verification_application_id)->first();

        if(!empty($enrolment1)){
            $enrolment1->ca_approval_status = Null;
            $enrolment1->video_approval_status = Null;
            $enrolment1->save();
        }

        return redirect()->route('enrolment.steps.completed', $request->verification_application_id);
    }

    public function getEnrolmentByApplicationIdAndDob(Request $request)
    {
        $enrolment = Enrolment::where(['application_id' => $request->application_id, 'birthday' => $request->dob])->first();

        if(empty($enrolment)){
            $enrolment = Orgenrolment::where(['application_id' => $request->application_id, 'birthday' => $request->dob])->first();
        }
        if($enrolment) {
            return response()->json($enrolment);
        } else {
            return response()->json(['message' => 'application id or dob is incorrect'], 404);
        }
    }

    public function getEnrolmentByApplicationId(Request $request)
    {
        $enrolment = Enrolment::where('application_id', $request->application_id)->first();
        return response()->json($enrolment);
    }

    public function downloadCertificate(Request $request)
    {
        $organisation_unit = 'NA';
        $organisation = 'Personal';
        $pseudonym = '';
        $ekyc = '';
        $is_org = $request->is_org;
        if ($request->is_org == '1'){
            $enrolment = Orgenrolment::where('application_id', $request->application_id)->first();
            $organisation_unit = $enrolment->departmentname;
            $organisation = $enrolment->govorgno;
            $type = $enrolment->certification_type;
            $application_id = $enrolment->application_id;

            $common_name = $enrolment->orgname;
            $house = $enrolment->orghouse;
            $street_address = $enrolment->orgstreet;
            $state = $enrolment->orgstate;
            $postal_code = $enrolment->orgpincode;
            $locality = $enrolment->orglocality;
            $town = $enrolment->orgtown;
            $city = $enrolment->orgcity;
            $district = $enrolment->orgdistrict;
        }
        else if ($request->is_org == '2' ){
            $enrolment = Orgenrolment::where('application_id', $request->application_id)->first();
            $organisation_unit = 'DGFTIEC-'.$enrolment->iec_code.'-'.$enrolment->branch_code;
            $organisation = $enrolment->govorgno;
            $type = $enrolment->certification_type;
            $application_id = $enrolment->application_id;

            $common_name = $enrolment->orgname;
            $house = $enrolment->orghouse;
            $street_address = $enrolment->orgstreet;
            $state = $enrolment->orgstate;
            $postal_code = $enrolment->orgpincode;
            $locality = $enrolment->orglocality;
            $town = $enrolment->orgtown;
            $city = $enrolment->orgcity;
            $district = $enrolment->orgdistrict;
        }
        else if ($request->is_org == '3' ){
            $enrolment = Orgenrolment::where('application_id', $request->application_id)->first();
            $organisation_unit = $enrolment->departmentname;
            $organisation = $enrolment->govorgno;
            $type = $enrolment->certification_type;
            $application_id = $enrolment->application_id;

            $common_name = $enrolment->orgname;
            $house = $enrolment->orghouse;
            $street_address = $enrolment->orgstreet;
            $state = $enrolment->orgstate;
            $postal_code = $enrolment->orgpincode;
            $locality = $enrolment->orglocality;
            $town = $enrolment->orgtown;
            $city = $enrolment->orgcity;
            $district = $enrolment->orgdistrict;
        } else {
            $enrolment = Enrolment::where('application_id', $request->application_id)->first();
            $pseudonym = $enrolment->response_code ?? '';
            $type = $enrolment->type;
            $application_id = $enrolment->application_id;

            $house = "";
            $street_address = "";
            $locality = "";
            $town = "";
            $city = "";
            $district = "";
        }

        // $ekyc = Ekyc::findOrFail($request->ekyc);

        // $common_name = $ekyc->name;
        // $serial_number = hash("sha256", trim($ekyc->pan));
        // $house = $ekyc->address;
        // $street_address = $ekyc->address;
        // $state = $ekyc->state;
        // $postal_code = $ekyc->pincode;
        // $telephone = trim($ekyc->mobile); 
        // $country = $ekyc->nationality;
        // $type = $enrolment->certification_type;
        if($request->is_authoriser) {
            $ekyc = Ekyc::findOrFail($enrolment->authoriser_id);
        } else {
            $ekyc = Ekyc::findOrFail($request->ekyc);
        }

        if($ekyc) {
            if($request->is_org == '3' ){
                $serial_number = hash("sha256", trim($enrolment->orgpan));
            }
            else{
                $serial_number = hash("sha256", trim($ekyc->pan));
            }
            if($request->is_org == '0'){
                $common_name = $ekyc->name;
                $state = $ekyc->state;
                $postal_code = $ekyc->pincode;
            }
            
            $title = " ";
            $telephone = trim($ekyc->mobile); 
            $country = $ekyc->nationality;
        }

        return view('download_certificate', compact('title','is_org','application_id','common_name', 'serial_number', 'house', 'street_address', 'state', 'postal_code', 'telephone', 'pseudonym', 'organisation_unit', 'organisation', 'country', 'type','locality','town','city','district'));
    }

    //*************made by nikhil************** */
    public function sendemail_otp(Request $request){
        $user = Ekyc::where([
            'email' => $request->email
                    ])->count();
     
        if($user){
          $enrolment = Ekyc::where(['email' => $request->email])->first();
            $otp = rand(4000,9000);
            Mail::to($request->email)->send(new sendOtp($otp));
            $enrolment->email = $request->email;
            $enrolment->email_otp = $otp;
            $enrolment->save();
            return 1;
    }
    else{
          $enrolment = new Ekyc();
              $otp = rand(4000,9000);
            Mail::to($request->email)->send(new sendOtp($otp));
          $enrolment->email = $request->email;
          $enrolment->email_otp = $otp;
          $enrolment->save();
           return 1;
    }

}

public function sendphone_otp(Request $request){
        $user = Ekyc::where([
            'mobile' => $request->mobile
                    ])->count();
     
        if($user){
             $enrolment = Ekyc::where(['mobile' => $request->mobile])->first();
                  $phone = "+91".$request->mobile;
                   // $otp = rand(4000,9000);
                  $otp = 1234;
            // $send_otp = helpers::phone_msg($phone,$otp);
            $enrolment->mobile = $request->mobile;
            $enrolment->mobile_otp = $otp;
            if($enrolment->save()){
             return 1;
          }

    }
    else{
         $enrolment = Ekyc::where([
            'email' => $request->email,
          ])->first();
          $phone = "+91".$request->mobile;
          if($enrolment){
              // $otp = rand(4000,9000);
                  $otp = 1234;
            // $send_otp = helpers::phone_msg($phone,$otp);
            $enrolment->email = $request->email;
            $enrolment->mobile = $request->mobile;
            $enrolment->mobile_otp = $otp; 
          }
          else{
          $enrolment = new Ekyc();
                  $otp = 1234;
            //   $otp = rand(4000,9000);
            // $send_otp = helpers::phone_msg($phone,$otp);
            $enrolment->email = $request->email;
            $enrolment->mobile = $request->mobile;
            $enrolment->mobile_otp = $otp;
          } 
          if($enrolment->save()){
             return 1;
          }
    }

}

public function send_otp(Request $request){
        $user = Ekyc::where([
            'email' => $request->email
                    ])->count();

     
        if($user){

            $userC = Ekyc::where([
            'email' => $request->email
                    ])->first();

              $otp = rand(4000,9000);
            $userC->email_otp = $otp;
            $userC->save();
            Mail::to($request->email)->send(new sendOtp($otp));
        
        return 1;
    }
    else{

           return 0;
    }

}

    public function verify_email(Request $request){
        $user = Ekyc::where([
            'email' => $request->email,
            'email_otp' => $request->otp
          ])->first();

          if($user){
              $user->email_verified = 1;
              $user->email_verify_time = Carbon::now();
              $user->save();
            //   $user1 = Enrolment::where([
            //    'email' => $request->email,
            //   ])->first();
            //   $user1->email_verified = 1;
            //   $user1->save();
              Ekyc::where('email','=',$request->email)->update(['email_otp' => null]);
              
                  return 1;
              
          }
          else{
              return 0;
          }
    }

    public function verify_phone(Request $request){
        $user = Ekyc::where([
            'mobile' => $request->mobile,
            'mobile_otp' => $request->otp
          ])->first();

          

          if($user){
              $user->mobile_verified = 1;
              $user->save();
            //   $user1 = Enrolment::where([
            //    'email' => $request->email,
            //   ])->first();
            //   $user1->mobile_verified = 1;
              Ekyc::where('mobile','=',$request->mobile)->update(['mobile_otp' => null]);

              if($user->save()){

                 return 1;
              }
              else{
                  return 0;
              }
          }
          else{
              return 0;
          }
    }

    public function verify_pan(Request $request){

      if(!empty($request->pan)){
          $user = Ekyc::where([
            'pan' => $request->pan,
          ])->first();

          if($user){
             return 0;
          }
          else{
              return 1;
          }
      }  

       if(!empty($request->email)){
          $user = Ekyc::where([
            'email' => $request->email,
          ])->first();

          if($user){
             return 0;
          }
          else{
              return 1;
          }
      } 

       if(!empty($request->mobile)){
          $user = Ekyc::where([
            'mobile' => $request->mobile,
          ])->first();

          if($user){
             return 0;
          }
          else{
              return 1;
          }
      } 
        
    }

    public function pan_login_user(Request $request)
    {
        $ekyc = Ekyc::where(['signer_id' => $request->kycid , 'ekyc_pin' => $request->kycpin])->first();
        
        $min = pow(10,4);
        $max = pow(10,4+1)-1;
        $application_id=rand($min, $max).rand();
         $pin = rand(400,900);
         $token1=md5(rand().$request->pan.date("H:i:s"));
        $token2=md5(rand().$request->pan.date("H:i:s"));
        if($ekyc){
              $enrolment = new Enrolment();
              $enrolment->type = $request->type;
              $enrolment->certification_class = $request->certification_class;
              $enrolment->validity = $request->validity;
              $enrolment->ekyc_type = 'pan_kyc';
              $enrolment->email = $ekyc->email;
              $enrolment->mobile = $ekyc->mobile;
              $enrolment->ekyc_pin = $ekyc->ekyc_pin;
              $enrolment->ekyc_token = $token1;
              $enrolment->dsc_token = $token2;
              $enrolment->ekyc_id = $ekyc->id;
              $enrolment->application_id = $application_id;
              $enrolment->save();

              $tauth = $this->authentication($token1, $token2, true);

                $data['new'] = [
                         'id' => $enrolment->id,
                         'application_id' => $enrolment->application_id,
                         'dob' => $ekyc->birthday,
                         'name' => $ekyc->name,
                         'pan_number' => $ekyc->pan,
                         'email' => $ekyc->email,
                         'phone' => $ekyc->mobile,
                         'gender' => $ekyc->gender,
                         'city' => $ekyc->city,
                         'state' => $ekyc->state,
                         'address' => $ekyc->address,
                         'pincode' => $ekyc->pincode,
                         'signer' => $ekyc->signer_id,
                         'ekyc_pin' => $ekyc->ekyc_pin,
                         'pin' => $pin,
                         'token1' => $tauth['token1'],
                         'token2' => $tauth['token2'],
                         'pdf_url' => $tauth['pdfUrl'],
                         'video_file' => (!empty($enrolment->video_file)) ? $enrolment->video_file : "",
                         'code' => 1,
                     ];
                return json_encode($data);
             
        }
        else{
             $data['new'] = [
                         'code' => 0,
                     ];
                return json_encode($data);
        }
    }

  public function ekyc_save(Request $request){

            $ekyc = new Ekyc();
            $ekyc->nationality = $request->nationality;
            $ekyc->pan = $request->pan;
            $ekyc->name = $request->name;
            $ekyc->email = $request->email;
            $ekyc->mobile = $request->mobile;
            $ekyc->birthday = $request->birthday;
            $ekyc->gender = $request->gender;
            $ekyc->pincode = $request->pincode;
            $ekyc->state = $request->state;
            $ekyc->city = $request->city;
            $ekyc->address = $request->address;
            $ekyc->remarks = $request->remark;
            $ekyc->ekyc_pin = $request->ekyc_pin;
            $ekyc->photo_file = $request->photoName;
            $ekyc->pan_file = $request->panName;
            $ekyc->address_file = $request->addressName;
    
            $ekyc->save();

            return 1;
  }


}

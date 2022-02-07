<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use \Carbon\Carbon;
use App\Enrolment;
use App\Ekyc;
use App\Master;
use App\Orgenrolment;
use App\Video;
use DB;

class VideoController extends Controller
{

    public function video_record($application_id){
        $birthday = "";

        if($application_id == "upload"){
            $application_id = "";
            return view('video_record', compact('birthday','application_id'));
        }
          $enrolment = Enrolment::where('application_id','=',$application_id)->first();
          if($enrolment){
              $ekyc = Ekyc::where('id',$enrolment->ekyc_id)->first();

              $name="";
              if(!empty($ekyc)){
                 $name = $ekyc->name;
              }
               $birthday = $enrolment->birthday;
               return view('video_record', compact('birthday','application_id','name','ekyc'));
            }

            $orgenrolment = Enrolment::where('application_id','=',$application_id)->first();
            if($orgenrolment){
                $ekyc = Ekyc::where('id',$orgenrolment->ekyc_id)->first();

                $name="";
                if(!empty($ekyc)){
                    $name = $ekyc->name;
                }
               $birthday = $orgenrolment->birthday;
               return view('video_record', compact('birthday','application_id','name','ekyc'));
            }
    }

     public function upload(Request $request)
    {
        $application_id = $request->application_id;
        $videoFileHash = $request->video_file_hash;
        $videoPath = '';
        $datetime = Carbon::now()->format('Y-m-d H:m:s');
        if($request->hasFile('video-blob')){
            $file=$request->file('video-blob');
            $videoName = $request->file('video-blob')->getClientOriginalName();
            $videoPath = public_path('/videos/');
            $file->move($videoPath , $videoName); 

            // $master = Master::where('application_id',$application_id)->first();
            // $q1 = Ekyc::where('id',$master->ekyc_id)->first();
            // $q1->video_file = $videoName;
            // $q1->video_file_hash = $videoFileHash;
            // $q1->save();
            $enrolment = Enrolment::where('application_id',$application_id)->first();
            // $video = Video::where('application_id',$application_id)->first();

            // if(!empty($video)){
            //      $video->ekyc_id = $enrolment->ekyc_id;
            //     $video->type = $request->type;
            //     $video->video_file = $videoName;
            //     $video->video_file_hash = $videoFileHash;
            //     $video->save();
            // }
            // else{
            //     $video = new video();
            //     $video->application_id = $application_id;
            //     $video->ekyc_id = $enrolment->ekyc_id;
            //     $video->type = $request->type;
            //     $video->video_file = $videoName;
            //     $video->video_file_hash = $videoFileHash;
            //     $video->save();
            // }
            
            

            

            if(!empty($enrolment)){
                 $enrolment->video_file = $videoName;
                 $enrolment->video_file_hash = $videoFileHash;
                 $enrolment->save();
            }

            $orgenrolment = Orgenrolment::where('application_id',$application_id)->first();

            if(!empty($orgenrolment)){
                 $orgenrolment->video_file = $videoName;
                 $orgenrolment->video_file_hash = $videoFileHash;
                 $orgenrolment->save();
            }

        }

        $data['new'] = [
                         'image_name' => $videoName,
                         'datetime' => $datetime,
                     ];
                        return json_encode($data);

    }

     public function check(Request $request, $application_id=null)
    {
        $master = Master::where('application_id',$application_id)->first();
        $q = Ekyc::whereNotNull('video_file');
        if ($application_id) {
            $enrolment = $q->where('id',$master->ekyc_id);
        }
        $enrolment = $q->first();

        return view('video_check', compact('enrolment','application_id'));
    }

    public function docs_check(Request $request,$type, $application_id=null)
    {
        $enrolment = Enrolment::where('application_id',$application_id)->first();

        if(!empty($enrolment)){
            $enrol = Ekyc::where('id',$enrolment->ekyc_id)->first();
        }

        $orgenrolment = Orgenrolment::where('application_id',$application_id)->first();

         if(!empty($orgenrolment)){
        $enrolment = Orgenrolment::where('application_id',$application_id)->first();

            $enrol = Ekyc::where('id',$orgenrolment->ekyc_id)->first();
        }

        // $master = Master::where('application_id',$application_id)->first();
        
        // if (!empty($master)) {
        //     $enrol = Ekyc::where('id',$master->ekyc_id)->first();
        // }

        return response()->json(
            [
                'success' => true, 
                'enrol' => $enrol,
                'application_id' => $application_id,
                'enrolment' => $enrolment,
                'orgenrolment' => $orgenrolment,
                'type' => $type,
            ]);
        
        // return view('doc_check', compact('enrol','application_id','enrolment', 'orgenrolment', 'type'));
    }
}

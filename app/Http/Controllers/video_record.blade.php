@extends('layouts.new_dashboard')

@section('page-heading', 'Add Partner')

@section('content')



  <section>
        <div class="main-panel">
          <div class="content-wrapper2">
      <div class="container">
       
    <div class="row form-bb">

              <div class="col-12">
            
                <div class="fom-bg1">
                     <h3 class="mb-4" style="font-weight: 700;position: relative;font-size: 25px;text-align:center;">Video Record</h3>
        <div class="video-rec">
        <div class="video-new">
        <div class="wid20" id="Certifi">
        <div class="cir-active">1</div>
        <p class="cir-text">Verify Details</p>
        </div>
        </div>
        <div class="video-new1">
        <div class="wid20" id="personal">
        <div class="cir" id="tow">2</div>
        <p class="cir-text">Record Video</p>
        </div>
        </div>
        </div>
               
        <div class="mb-4 step1">
                      <div class="row">
                        <div class="col-md-8">
                          <div class="form-group">
                            <label>Application ID</label>
                            <input type="text" class="form-control" placeholder="Application ID" id="application_id" name="application_id" value="{{ $application_id }}"/>
                            <p id="application_id_error"></p>
                          </div>
                          <div class="form-group">
                            <label>Date of Birth</label>
                            <input
                            type="date"
                            id="birthday"
                            class="form-control"
                            name="birthday"
                            value="{{ $birthday }}"
                            />
                            <input
                            type="hidden"
                            id="ekyc_id"
                            value="{{ (!empty($ekyc->id)) ? $ekyc->id : '' }}
                            />
                            <p id="birthday_error"></p>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <p><input type="checkbox" id="confirmation_checked" /> I hereby confirm that I have a camera, microphone and have good internet connection (minimum 3G).I confirm to validate myself with my video recording and fair answers to questions displayed.</p>
                          </div>
                          <p id="all_error"></p>
                        </div>
                      </div>
                      <div class="text-right">
                        <button type="button" class="btn-form" id="move_step_2" disabled>Next</button>
                      </div>
                    </div>
          
          <div class="mb-4 col-12 step2" style="display:none;">
        <div class=" mb-3">
                        <!--<div class="col-lg-4"> Certification Class</div>-->
                        <div id="step_2">
                      <div class="row" >
                        <div class="col-6 justify-content-md-center middle">
                          
                        <div class="col-12 col-md-12 align-self-center">
                            
                          <p class="mb-2 text-center" id="before_view">Video Recording Left 00:<span id="countdowntimer">22</span>seconds</p>
                          {{-- <p class="mb-2 text-center" id="after_view" style="visibility:hidden">Video Recorded at  <span id="date_time"></span></p> --}}
                          <div class="form-group">
                            <div id="img"><img src="../images/video.jpg"></div>
                            <div id="recordVideo" class="embed-responsive embed-responsive-1by">
                              <div class="overlayText">
                                <p id="topText"></p>
                              </div>
                              <video id="videoElement" muted="muted"></video>
                            </div>
                            <div id="viewVideo" class="container embed-responsive embed-responsive-1by">
                              <div class="overlayText">
                                {{-- <p id="topText1">Content above your video</p> --}}
                              </div>
                              <video id="vid2"></video>

                            </div> 
                            
                          </div>
                           <?php
                              $otp = rand(400,900);
                           ?>
                           <div class="form-group">
                        <div class= "row">
                            <div class="col-lg-4">
                            <button type="button" style="float:left;" class="btn-form btn-padding mr-2 mr-md-5" id="btnStart" >Record</button>
                            <button type="button" style="float:left;" class="btn-form btn-padding mr-2 mr-md-5 d-none" id="btnReStart">Rerecord</button>
                            </div>
                            <!-- <button type="button" style="float:left;" class="btn btn-primary mr-3" id="btnStop" >Stop</button> -->
                            <div class="col-lg-4">
                            <button type="button" style="float:left;" class="btn-form btn-padding  mr-md-4" id="btnView" disabled>View</button>
                            </div>
                            <div class="col-lg-4">
                              <form method="POST" style="float:left;" action="{{ route('video.verification1') }}">
                              @csrf
                              <input type="hidden" name="verification_application_id" id="verification_application_id" />
                              <input type="hidden" name="verfication_dob" id="verfication_dob" />
                              <input type="hidden" name="pin" id="pin" value="{{ $otp }}" />
                              <input type="hidden" name="verification_video_hash" id="verification_video_hash" />
                              <button type="submit" href="" class="btn-form btn-padding" id="verification_form_submit" disabled>Submit</button>
                              </form>
                            </div>
                            </div>
                    </div>
                        </div>
                      </div>
                      <div class="col-6 mt-3">

                      <p class="text-uppercase mb-3" style="color: #b74f51;">Click the record button and read the script below</p>
                      
                      <ul class="list-group mb-2">
                        <li class="list-group-item">My name is <strong id="applicant_name">{{ (!empty($name)) ? $name : " " }}</strong></li>
                        <li class="list-group-item">My application ID is <strong id="applicant_application_id"></strong></li>
                        <li class="list-group-item">My date of birth is <strong id="applicant_dob"></strong></li>
                        <li class="list-group-item">PIN  -     <strong id="applicant_pin">{{ $otp }}</strong></li>
                        <li class="list-group-item">I have applied for a Futuriq digital signature</li>

                      </ul>
                      </div>
                        </div> 
                        <button class="buttonload d-none" id="loading">
                           <i class="fa fa-spinner fa-spin"></i>
                      </button>                       
                    </div>
          </div>
<!-- Modal -->
<div id="myModal" class="modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content1">
      <div class="modal-header">
        
        <h4 class="modal-title" style="color:#8dc74d">Your Enrolment is created successfully</h4>
      </div>
      <div class="modal-body">
        <ul class="list-group mb-2">
                        <li class="list-group-item">Your name is <strong id="modal_applicant_name"></strong></li>
                        <li class="list-group-item">Your application ID is <strong id="modal_applicant_application_id"></strong></li>
                        <li class="list-group-item">My date of birth is <strong id="modal_applicant_dob"></strong></li>
                      </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-form" data-dismiss="modal" onclick="modal_ok()">Ok</button>
      </div>
    </div>

  </div>
</div>
        
<script>
$(document).ready(function(){
 
  $(".video-new1").click(function(){
    $(".step1").hide();
  $(".step2").show();
    $("#tow").removeClass("cir");
    $("#tow").addClass("cir-active");
    $("#personal, #Certifi ").removeClass("wid20");
    $("#personal, #Certifi").addClass("wid21");
  });
  $(".personal1").click(function(){
    $(".step1, .step5, .step2, .step4").hide();
  $(".step3").show();
    $("#tow, #three").removeClass("cir");
    $("#tow, #three").addClass("cir-active");
    $("#personal1, #personal, #Certifi ").removeClass("wid20");
    $("#personal1, #personal, #Certifi").addClass("wid21");
  });
  $("#move_step_2").click(function(){
    $(".step1").hide();
    $(".step2").show();
    $("#tow").removeClass("cir");
    $("#tow").addClass("cir-active");
    $("#personal, #Certifi ").removeClass("wid20");
    $("#personal, #Certifi").addClass("wid21");
    
    openVideoRecording();
     
  });
  
});
    </script>

{{-- <script type="text/javascript"> 
function display_c(){
var refresh=1000; // Refresh rate in milli seconds
mytime=setTimeout('display_ct()',refresh)
}
function display_ct() {
var x = new Date()
var x1=x.getMonth() + 1+ "/" + x.getDate() + "/" + x.getFullYear(); 
x1 = x1 + " - " +  x.getHours( )+ ":" +  x.getMinutes() + ":" +  x.getSeconds();
// var x1=x.toUTCString();// changing the display to UTC string
console.log(x1);
document.getElementById('topText').innerHTML = x1;
display_c();
 }
 function display_c1(){
var refresh=1000; // Refresh rate in milli seconds
mytime=setTimeout('display_ct1()',refresh)
}
function display_ct1() {
var x = new Date()
// var x1=x.getMonth() + 1+ "/" + x.getDate() + "/" + x.getFullYear(); 
// x1 = x1 + " - " +  x.getHours( )+ ":" +  x.getMinutes() + ":" +  x.getSeconds();
var x1=x.toUTCString();// changing the display to UTC string
console.log(x1);
document.getElementById('topText1').innerHTML = x1;
display_c1();
 }
</script> --}}
<script>
    document.getElementById('recordVideo').style.display = "none";
    document.getElementById('viewVideo').style.display = "none";
    let video = document.querySelector('#videoElement');
    let vidSave = document.querySelector('#vid2');
    video.currentTime = 5;

    function openVideoRecording() {
      let constraintObj = {
          audio: {
            sampleRate: 48000,
            channelCount: 2,
            volume: 1.0
        },
          video: true
      };
      //handle older browsers that might implement getUserMedia in some way
      if(navigator.mediaDevices === undefined){
          navigator.mediaDevices = {};
          navigator.mediaDevices.getUserMedia = function(constraints){
              let getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
              if(!getUserMedia){
                  return Promise.reject(new Error('getUserMedia is not implemented in the browser'));
              }
              return new Promise(function(resolve, reject){
                  getUserMedia.call(navigator, constraints, resolve, reject);
              });
          }
      }else{
          navigator.mediaDevices.enumerateDevices()
          .then(devices => {
              devices.forEach(device=>{
                  console.log(device.kind.toUpperCase(), device.label);
                  console.log(device.deviceId, device.label);
              })
          })
          .catch(err => {
              console.log(err.name, err.message);
          })
      }
      navigator.mediaDevices.getUserMedia(constraintObj)
      .then(function(mediaStreamObj){
          //connect to media stream with first video element
          if("srcObject" in video){
            video.srcObject = mediaStreamObj;
          }else{
            //old version
            video.src = window.URL.createObjectURL(mediaStreamObj);
          }
          //add listeners for saving video/audio
          let stop = document.getElementById('btnStop');
          let submit = document.getElementById('verification_form_submit');
          let start = document.getElementById('btnStart');
          start.disabled = false;
          let view = document.getElementById('btnView');
          let mediaRecorder = new MediaRecorder(mediaStreamObj);
          let chunks = [];
           start.addEventListener('click', (ev) => {
             document.getElementById('recordVideo').style.display = "block";
             document.getElementById('img').style.display = "none";
              video.play();
              mediaRecorder.start();
              timer(video, mediaRecorder, chunks);
           });
           view.addEventListener('click', (ev) => {
              vidSave.play();
           }); 
          //  stop.addEventListener('click', (ev) => {
          //     mediaRecorder.stop();
          //     console.log(mediaRecorder.state);
          //  });
          
           
      })
      .catch(function(err){
          console.log(err.name, err.message);
      });
    }
    function timer(video, mediaRecorder, chunks) {
      var timeleft =22;
      var downloadTimer = setInterval(function(){
      timeleft--;
      document.getElementById("countdowntimer").textContent = timeleft;
        if(timeleft <= 0){
            video.pause();
            mediaRecorder.stop();
            mediaRecorder.ondataavailable = (ev) => {
               chunks.push(ev.data);
            }
            mediaRecorder.onstop = async (ev) => {
              let blob = new Blob(chunks, {'type' : 'video/mp4;'});
              console.log("Blog Data==>", blob);
              chunks = [];
              await uploadFile(blob);
              let videoURL = window.URL.createObjectURL(blob);
              vidSave.src = videoURL;
            }
            clearInterval(downloadTimer);
            document.getElementById('recordVideo').style.display = "none";
            document.getElementById('viewVideo').style.display = "block";
            document.getElementById('btnStart').disabled = true;
            document.getElementById('btnView').disabled = false;
            document.getElementById('verification_form_submit').disabled = false;
        }    
      },1000);
    }
    
     function loading(iRun) {
    var loader = document.getElementById('loading').classList;
            if (iRun) {
                loader.remove('d-none');
            }
            else {
                loader.add('d-none');
            }
        }
    
    function uploadFile(blob) {
        document.getElementById('verification_form_submit').disabled = true;
        document.getElementById('btnView').disabled = true;
        loading(true);
        var application_id = document.getElementById('application_id').value;
        var birthday = document.getElementById('birthday').value;
        var ekyc_id = document.getElementById('ekyc_id').value;
        var file = new File([blob], 'msr-' + (new Date).toISOString().replace(/:|\./g, '-') + '.webm', {
            type: 'video/webm'
        });
        const hashHexVideo = getHash("SHA-256", new Blob([file]));
        hashHexVideo.then(function(result) {
          const hashHexVideoValue = result;
          console.log('video_file_hash==>', hashHexVideoValue);
          // create FormData
          var formData = new FormData();
          formData.append('application_id', application_id);
          formData.append('_token', "{{ csrf_token() }}");
          formData.append('video-filename', file.name);
          formData.append('video-blob', file);
          formData.append('video_file_hash', hashHexVideoValue);
          formData.append('ekyc_id', ekyc_id);
          formData.append('type', 'applicant');
          makeXMLHttpRequest('{{ route("video.upload") }}', formData, function() {
              var downloadURL = 'https://futuriq.in/videos/' + file.name;
                document.getElementById('verification_application_id').value = application_id;
                document.getElementById('verfication_dob').value = birthday;
              console.log('File uploaded to this path:', downloadURL);
          });
      });
    }
    function makeXMLHttpRequest(url, data, callback) {
      // display_ct1();
        console.log('Data===>', data);
        var request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
              loading(false);
              const obj = JSON.parse(request.responseText);
                callback(location.href + request.responseText);
                console.log("res", obj.new.image_name);
                document.getElementById('verification_video_hash').value = obj.new.image_name;
                // document.getElementById('date_time').textContent = obj.new.datetime;
                document.getElementById('verification_form_submit').removeAttribute('disabled');
                document.getElementById('btnView').removeAttribute('disabled');
                document.getElementById('btnStart').classList.add('d-none');
                document.getElementById('btnReStart').classList.remove('d-none');
                
            }
        };
        request.open('POST', url);
        request.send(data);
    }
    let view = document.getElementById('btnView');
  view.addEventListener('click', (ev) => {
    document.getElementById('before_view').style.visibility = "hidden";
    // document.getElementById('after_view').style.visibility = "visible";
    });
    
  let restart = document.getElementById('btnReStart');
  restart.addEventListener('click', (ev) => {
    document.getElementById('recordVideo').style.display = "block";
    document.getElementById('viewVideo').style.display = "none";
    let video = document.querySelector('#videoElement');
    let vidSave = document.querySelector('#vid2');
    video.currentTime = 5; 
    document.getElementById('btnStart').classList.remove('d-none');
    document.getElementById('btnReStart').classList.add('d-none'); 
    document.getElementById("countdowntimer").textContent = 22;
    document.getElementById('btnView').disabled = true;
    document.getElementById('verification_form_submit').disabled = true;
     document.getElementById('before_view').style.visibility = "visible";

    let constraintObj = {
          audio: true,
          video: true
      };
      //handle older browsers that might implement getUserMedia in some way
      if(navigator.mediaDevices === undefined){
          navigator.mediaDevices = {};
          navigator.mediaDevices.getUserMedia = function(constraints){
              let getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
              if(!getUserMedia){
                  return Promise.reject(new Error('getUserMedia is not implemented in the browser'));
              }
              return new Promise(function(resolve, reject){
                  getUserMedia.call(navigator, constraints, resolve, reject);
              });
          }
      }else{
          navigator.mediaDevices.enumerateDevices()
          .then(devices => {
              devices.forEach(device=>{
                  console.log(device.kind.toUpperCase(), device.label);
                  console.log(device.deviceId, device.label);
              })
          })
          .catch(err => {
              console.log(err.name, err.message);
          })
      }
      navigator.mediaDevices.getUserMedia(constraintObj)
      .then(function(mediaStreamObj){
          //connect to media stream with first video element
          if("srcObject" in video){
            video.srcObject = mediaStreamObj;
          }else{
            //old version
            video.src = window.URL.createObjectURL(mediaStreamObj);
          }
          //add listeners for saving video/audio
          let stop = document.getElementById('btnStop');
          let submit = document.getElementById('verification_form_submit');
          let start = document.getElementById('btnStart');
          start.disabled = true;
          let view = document.getElementById('btnView');
          let mediaRecorder = new MediaRecorder(mediaStreamObj);
          let chunks = [];
           // start.addEventListener('click', (ev) => {
              video.play();
              mediaRecorder.start();
              timer(video, mediaRecorder, chunks);
           // });
           view.addEventListener('click', (ev) => {
              vidSave.play();
           }); 
          //  stop.addEventListener('click', (ev) => {
          //     mediaRecorder.stop();
          //     console.log(mediaRecorder.state);
          //  });
          
           
      })
      .catch(function(err){
          console.log(err.name, err.message);
      });
  });
    
     /**
      * @param {"SHA-1"|"SHA-256"|"SHA-384"|"SHA-512"} algorithm https://developer.mozilla.org/en-US/docs/Web/API/SubtleCrypto/digest
      * @param {string|Blob} data
    **/
    async function getHash(algorithm, data) {
          const main = async (msgUint8) => { // https://developer.mozilla.org/en-US/docs/Web/API/SubtleCrypto/digest#converting_a_digest_to_a_hex_string
            const hashBuffer = await crypto.subtle.digest(algorithm, msgUint8)
            const hashArray = Array.from(new Uint8Array(hashBuffer))
            return hashArray.map(b => b.toString(16).padStart(2, '0')).join(''); // convert bytes to hex string
          }
          if (data instanceof Blob) {
            const arrayBuffer = await data.arrayBuffer()
            const msgUint8 = new Uint8Array(arrayBuffer)
            return await main(msgUint8)
          }
          const encoder = new TextEncoder()
          const msgUint8 = encoder.encode(data)
          return await main(msgUint8)
    }
    
</script>
    
   @endsection
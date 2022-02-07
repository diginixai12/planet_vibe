<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="generator" content="">
        <title>dashboard</title>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
		<link href="css/style1.css" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style1.css') }}" rel="stylesheet">

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    </head>
    <body>

<div class="main-panel_login" id="login_panel">
<div class="content-wrapper-login">
  <div class=" justify-content-end"><a class="download-ut" href="{{ route('login') }}"> Admin Login</a></div>
  <div class="container h-100">
	  

    <div class="row form-bb pl-4 justify-content-center h-100 align-items-center">
	
      <div class="col-12">
	 <div class="row">
                <div class="col-md-6">
				<h3 class="mb-4" style="font-weight: 700;position: relative;font-size: 25px;color:#fff;">EKYC Login</h3>
				</div>
	  <div class="col-md-6 justify-content-end"><a class="download-ut" href=" {{ url('/futuriq_utility_Setup_1.0.6.exe') }}"> <i class="fa fa-download"></i> DOWNLOAD UTILITY</a></div>
        <div class="fom-bg1">
          
          <div class="row mt-3">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-12 grid-margin">
                 
    <form method="post" action="{{ route('ekyc.details') }}" enctype="multipart/form-data" id="contactForm">
    @csrf
                                <div class="existing_kyc" id="existing_kyc1">           
                                        <div class="row mb-3">
                                                      <!--<div class="col-lg-4"> Vaildity </div>-->
                                                      <div class="col-lg-6">
													  <div class="position-relative">
                                                        <input type="text" class="form-control_ekyc" placeholder="User Signer Id" id="pan_kycid" name="ekyc_id" required>
                                                         <i class="fa fa-user eye-icon" aria-hidden="true"></i>
														 </div>
													  </div>
                                          <div class="col-lg-6">
                                                <div class="col-lg-12 p-0 mb-3 d-flex">
                            <div class="col-lg-8 p-0"> 
                                <input type="text" class="form-control_ekyc" placeholder="Enter Otp" name="Otp"> 
								</div>
                                 <div class="col-lg-4 p-0"><button type="button"  class="btn-form_otp_login" onclick="">Send Otp</button>
                                  
                              </div>    
                            </div>
                                                      </div>
                                                  </div>
												  <div class="row mb-3">
												  <div class="col-6">
												  <div class="position-relative">
                                                 
                                                         <input type="password" class="form-control_ekyc" placeholder="KYC Pin" id="id_password" name="ekyc_pin" required>
                                                  
                                                  
                                                        <i class="fa fa-eye eye-icon" id="togglePassword" style="cursor: pointer;"></i>

                                                </div>
												  
                            
                       
												 </div>
										 <div class="col-6">
										 <div class="row">
										  <div class="col-7"><input type="checkbox" name="verify_later" class="check-b" value="yes" onclick=""> <span class="check-b-text">I agree to the terms and conditions.</span></div>
                                        
                                                        <div class="d-flex  col-5 mb-3 justify-content-end">
                                                  <p id="pan_login_error" style="color: red;margin-right:10px;"></p>
                                                  <button  class="btn-form" type="submit"><i class="fa fa-lock" aria-hidden="true"></i> Login</button>

                                                            </div>
                                                           </div>
                                                            

                                                      </div>
										</div>
										
									  <div class="row mb-5">
									  <div class="col-12 text-center">
									  <a href="">Forgot Password.</a> If you do not have Kyc Account <a href="">Click Here.</a>
                                             </div>
											 </div>
											<div class="row">
											<div class="col-4 sig">
											<div class="row">
											<div class="col-3">
											<img src="../images/icon-new.png">
											</div>
											<div class="col-9 signatur">
											<h5>Electronic Signature</h5>
											Electronic Signature or Electronic Authentication Technique and Procedure Rules, 2015 - eauthentication technique using e-KYC services.
											  </div>
											  </div>
											  </div>
											  <div class="col-4 sig1">
											<div class="row">
											<div class="col-3">
											<img src="../images/icon-new.png">
											</div>
											<div class="col-9 signatur">
											<h5>eSign Service Benefit</h5>
											eSign service allows applications to replace manual paper based signatures by integrating an API which allows an Aadhaar holder to electronically sign a form/document anytime, anywhere, and on any device legally in India.</div>
											  </div>
											  </div>
											  <div class="col-4 sig2">
											<div class="row">
											<div class="col-3">
											<img src="../images/icon-new.png">
											</div>
											<div class="col-9  signatur">
											<h5>eSign Service Facilitates</h5>
											eSign service facilitates significant reduction in paper handling costs, improves efficiency, and offers convenience to customers.</div>
											  </div>
											  </div>
                                                  </div></div>
                             </form>      
                      </div>
                   
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

   const togglePassword = document.querySelector('#togglePassword');
  const password = document.querySelector('#id_password');
 
  togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});

</script>

</head>
</html>
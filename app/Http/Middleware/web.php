<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// header("Access-Control-Allow-Origin: *");

Route::get('/', function () {
    return view('welcome');
});

Route::get('/logout', function(){
    Session::flush();
    Auth::logout();
    return Redirect::to("/")
      ->with('message', array('type' => 'success', 'text' => 'You have successfully logged out'));
});

Route::get('/logout_for_ekyc', function(){
    Session::flush();
    Auth::logout();
    return Redirect::to("/ekyc/login")
      ->with('message', array('type' => 'success', 'text' => 'You have successfully logged out'));
});

Route::get('/videocheck/{application_id?}', 'VideoController@check')->name('video.check');
Route::get('/docs_check/{type}/{application_id?}', 'VideoController@docs_check')->name('video.check');

Route::get('/videorecord/{id}', 'VideoController@video_record')->name('vidoerecord');

Route::get('download-certificate', 'EnrolmentController@downloadCertificate')->name('download.certificate');

Route::post('/video/upload', 'VideoController@upload')->name('video.upload');
Route::post('/video/verification', 'EnrolmentController@video_verification_store')->name('video.verification');
Route::post('/video/verification1', 'EnrolmentController@video_verification_store1')->name('video.verification1');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('apply-dsc', 'EnrolmentController@apply_dsc')->name('apply.dsc');
    
    Route::get('enrolment/list', 'EnrolmentController@list')->name('enrolment.list');
    Route::get('enrolment/dsc-list', 'EnrolmentController@dsc_list')->name('enrolment.dsc.list');
    Route::get('enrolment/ra_report', 'admin\EnrolmentController@ra_report')->name('enrolment.ra_report');
    Route::get('enrolment/org_dsc-list', 'EnrolmentController@org_dsc_list')->name('enrolment.org_dsc.list');
    Route::get('enrolment/completed-steps/{application_id}', 'EnrolmentController@getEnrolmentByApplicationIdSteps')->name('enrolment.steps.completed');
    Route::get('enrolment/all_list', 'admin\EnrolmentController@getAllEnrolment')->name('enrolment.all_list');
    Route::get('enrolment/all_docs', 'admin\EnrolmentController@getAllDocument')->name('enrolment.all_docs');
    Route::get('enrolment/change_status/{type}/{application_id}/{status}', 'admin\EnrolmentController@change_status')->name('enrolment.change_status');
    Route::post('enrolment/change_approval_status', 'admin\EnrolmentController@change_approval_status')->name('change_approval_status');
    Route::get('adminhtml/dashboard_dsc', 'admin\EnrolmentController@getDashboard_dsc')->name('adminhtml.dashboard_dsc');
    Route::get('adminhtml/org_dashboard_dsc', 'admin\EnrolmentController@get_OrgDashboard_dsc')->name('adminhtml.org_dashboard_dsc');
    Route::get('adminhtml/admin_report', 'admin\EnrolmentController@getAdmin_report')->name('adminhtml.admin_report');
    Route::get('adminhtml/self_stock_report', 'admin\EnrolmentController@self_stock_report')->name('adminhtml.self_stock_report');
    Route::post('adminhtml/self_generated_stock_save', 'admin\EnrolmentController@self_generated_stock')->name('adminhtml.self_generated_stock_save');
    Route::get('adminhtml/self_generated_stock', 'admin\EnrolmentController@self_stock' )->name('adminhtml.self_generated_stock');
    Route::get('adminhtml/manage_partners/{id}', 'admin\EnrolmentController@manage_partners')->name('adminhtml.manage_partners');
    Route::get('adminhtml/manage_admin/{id}', 'admin\EnrolmentController@manage_admin')->name('adminhtml.manage_admin');
    Route::get('adminhtml/pending_approval/{type}/{approval_type}', 'admin\EnrolmentController@pending_approval')->name('adminhtml.pending_ca_approval');
    Route::get('/enrolment/application_id', 'EnrolmentController@getEnrolmentByApplicationId')->name('enrolment.by.application.id');
    Route::get('search_steps', 'admin\EnrolmentController@search_steps')->name('search_steps');
    Route::view('/apply_dsc_new', 'enrolment.apply_dsc_new')->name('apply_dsc_new');
    Route::view('/apply_dsc_new_org', 'enrolment.apply_dsc_new_org')->name('apply_dsc_new_org');
    Route::view('/apply_dsc_new_dgft', 'enrolment.apply_dsc_dgft')->name('apply_dsc_new_dgft');
    Route::view('/apply_dsc_new_docsigner', 'enrolment.apply_dsc_docsigner')->name('apply_dsc_new_docsigner');
    Route::post('/get_cities', 'admin\EnrolmentController@get_cities')->name('get_cities');
             
    //partner
    Route::get('/partner/create', 'PartnerController@create')->name('partner.create');
    Route::get('/partner/add_admin', 'PartnerController@add_admin')->name('partner.add_admin');
    Route::post('/partner', 'PartnerController@store')->name('partner.store');
    Route::get('/partner/index', 'PartnerController@index')->name('partner.index');
    Route::post('/partner/edit', 'PartnerController@edit')->name('partner.edit');
    Route::post('/partner/update', 'PartnerController@update')->name('partner.update');
    Route::get('/list_users', 'PartnerController@list_users')->name('list_users');
    Route::get('/partner/add_permission/{id}', 'PartnerController@add_permissions')->name('partner.add_permission');
    Route::post('/partner/update_permission', 'PartnerController@update_permissions')->name('partner.update_permission');

      Route::get('/partner/add_user_permission/{id}/{user_id}', 'PartnerController@add_user_permissions')->name('partner.add_user_permission');
    Route::post('/partner/update_user_permission', 'PartnerController@update_user_permissions')->name('partner.update_user_permission');

    Route::resource('add_stock', 'StockController');
    Route::post('/add_stock/edit', 'StockController@edit')->name('add_stock.edit');
    Route::get('/stock/list', 'StockController@list')->name('add_stock.list');
    Route::get('/transfer_stock', 'StockController@transfer_stock')->name('stock.transfer');
    Route::get('/transfer_report', 'StockController@transfer_report')->name('stock.transfer_report');
    Route::post('/transfer_stock_save', 'StockController@transfer_stock_save')->name('stock.transfer_save');
    Route::post('/stock/update', 'StockController@update')->name('stock.update');
    Route::post('/stock/remove_stock', 'StockController@remove_stock')->name('stock.remove_stock');
    Route::post('/stock_check', 'StockController@stock_check')->name('stock_check');
    Route::post('/check_pin', 'admin\EnrolmentController@check_pin')->name('check_pin');
    Route::get('/login_profile/{id}', 'admin\EnrolmentController@login_profile')->name('login_profile');
    Route::post('/edit_main_profile', 'admin\EnrolmentController@edit_main_profile')->name('edit_main_profile');


});

    Route::post('/pan_login_user', 'EnrolmentController@pan_login_user')->name('pan_login_user');
    Route::post('/enrolment', 'EnrolmentController@store')->name('enrolment.store');
    Route::post('/enrolment1', 'EnrolmentController@store1')->name('enrolment.store1');
    Route::post('/pan_login_user', 'EnrolmentController@pan_login_user')->name('pan_login_user');
    Route::get('/enrolment/authentication/ekyc/{token1}/dsc/{token2}', 'EnrolmentController@authentication')->name('enrolment.authentication');
    Route::get('/enrolment/application_id_and_dob', 'EnrolmentController@getEnrolmentByApplicationIdAndDob')->name('enrolment.by.application.id.and.dob');
    Route::post('/generate_otp', 'admin\EnrolmentController@generate_otp')->name('generate_otp');
    Route::post('/sendemail_otp', 'EnrolmentController@sendemail_otp')->name('sendemail_otp');
    Route::post('/sendphone_otp', 'EnrolmentController@sendphone_otp')->name('sendphone_otp');
    Route::post('/send_otp', 'EnrolmentController@send_otp')->name('send_otp');
    Route::post('/send_phone_otp', 'admin\EnrolmentController@send_phone_otp')->name('send_phone_otp');
    Route::post('/verify_email', 'EnrolmentController@verify_email')->name('verify_email');
    Route::post('/verify_phone', 'EnrolmentController@verify_phone')->name('verify_phone');
    Route::post('/esign_verify_phone', 'admin\EnrolmentController@esign_verify_phone')->name('esign_verify_phone');
    Route::post('/verify_pan', 'EnrolmentController@verify_pan')->name('verify_pan');
    Route::post('/verify_partner_email', 'PartnerController@verify_partner_email')->name('verify_partner_email');
    Route::post('/verify_input', 'OrgEnrolmentController@verify_input')->name('verify_input');
    Route::post('/update_file', 'admin\EnrolmentController@update_file')->name('update_file');
    Route::post('/get_gst_data', 'admin\EnrolmentController@get_gst_data')->name('get_gst_data');
    Route::post('/rejection_reason_submit', 'admin\EnrolmentController@rejection_reason_submit')->name('rejection_reason_submit');
    Route::post('/verify_orginput', 'OrgEnrolmentController@verify_orginput')->name('verify_orginput');


Route::get('enrolment/offline-kyc/{application_id}', 'EnrolmentController@get_offline_kyc')->name('enrolment.offline.kyc');

Route::get('/esign/individual/{application_id}', 'EsignController@individual_enrolment')->name('send.xml');

Route::get('/esign/org/{application_id}', 'EsignController@org_enrolment')->name('send.org.xml');

Route::get('/get-certificate', 'ServerRequestController@getCertificate')->name('get.certificate');

// nikhil
Route::view('/token_component', 'TokenComponent')->name('token_component');
Route::get('ekyc/login', function () {
    return view('enrolment.ekyc_login');
});
Route::view('ekyc/login', 'enrolment.ekyc_login')->name('ekyc.login');
// Route::get('ekyc/login', function(){
//     Session::flush();
//     Auth::logout();
//     return view('enrolment.ekyc_login');
// })->name('ekyc.login');
Route::get('esign_now/{id}', 'admin\EnrolmentController@esign_now')->name('esign_now');


Route::post('ekyc/details', 'admin\EnrolmentController@ekyc_details')->name('ekyc.details');
Route::post('ekyc/edit_details', 'admin\EnrolmentController@edit_ekyc_details')->name('ekyc.edit_details');
Route::post('ekyc/edit_enrolment', 'admin\EnrolmentController@edit_ekyc_enrolment')->name('ekyc.edit_enrolment');
Route::post('ekyc/edit_orgenrolment', 'admin\EnrolmentController@edit_ekyc_orgenrolment')->name('ekyc.edit_orgenrolment');
Route::post('enrolment/edit_enrolment_status', 'admin\EnrolmentController@edit_enrolment_status')->name('ekyc.edit_enrolment_status');
Route::post('ekyc/edit_enrolment_details', 'EnrolmentController@edit_enrolment_details')->name('ekyc.edit_enrolment_details');
Route::post('ekyc/edit_orgenrolment_details', 'admin\EnrolmentController@edit_orgenrolment_details')->name('ekyc.edit_orgenrolment_details');
Route::get('enrolment/ekyc_completed-steps/{application_id}', 'EnrolmentController@getEnrolmentByApplicationIdSteps1')->name('enrolment.steps.completed1');
Route::view('search_dsc', 'enrolment.search_dsc')->name('enrolment.search_dsc');
Route::post('search_dsc_details', 'admin\EnrolmentController@search_dsc_details')->name('search_dsc_details');
Route::get('download_dsc', 'admin\EnrolmentController@download_dsc')->name('enrolment.download_dsc');

Route::get('Ekyc/Confirmation/{application_id}/{id}', 'admin\EnrolmentController@ekyc_confirmation')->name('enrolment.ekyc_confirmation');


// saurav
Route::view('/ind', 'org_enrolment.ind')->name('org_enrolment.ind');
Route::view('/orggov', 'org_enrolment.orggov')->name('org_enrolment.orggov');
Route::view('/orggov2', 'org_enrolment.orggov2')->name('org_enrolment.orggov2');
Route::get('orggovnon', 'OrgEnrolmentController@create')->name('org_enrolment.orggovnon');

Route::post('orggovnon', 'OrgEnrolmentController@store')->name('org_enrolment.orggovnon');
Route::post('orggovnon', 'OrgEnrolmentController@store1')->name('org_enrolment.orggovnon1');
Route::post('orggovnon/create_user', 'OrgEnrolmentController@create_user')->name('org_enrolment.create_user');
Route::post('orggovnon/login_user', 'OrgEnrolmentController@login_user')->name('org_enrolment.login_user');
// Route::view('/orggovnon', 'org_enrolment.orggovnon')->name('org_enrolment.orggovnon');
Route::view('/orggovnon2', 'org_enrolment.orggovnon2')->name('org_enrolment.orggovnon2');
Route::view('/orggovdgst', 'org_enrolment.orggovdgst')->name('org_enrolment.orggovdgst');
Route::view('/orggovdgst2', 'org_enrolment.orggovdgst2')->name('org_enrolment.orggovdgst2');
Route::any('/last', 'OrgEnrolmentController@store')->name('org_enrolment.last');
// Route::view('/last', 'org_enrolment.last')->name('org_enrolment.last');


// api



/*------------------------------------------------admin------------------------------------------------------*/


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('approve/{application_id}', 'Admin/EnrolmentController@approveScreen')->name('approve.screen');
    Route::post('approve', 'Admin/EnrolmentController@approveApplicant')->name('approve.applicant');
    Route::get('application-list', 'ApplicationController@list')->name('application.list');
});

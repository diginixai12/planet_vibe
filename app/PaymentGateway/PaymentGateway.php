<?php

namespace App\PaymentGateway;

class PaymentGateway
{
  public static function get_access_token($params)
  {
    $params = json_encode($params);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://cerpagamentonline.emis.co.ao:9443/online-payment-gateway/api/v1/token',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $params,
      CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Content-Type: application/json'
      ),
      CURLOPT_SSLCERT => getcwd() . '\recerfileforverification\keyBag.pem',
      CURLOPT_SSLCERTTYPE => 'PEM',
    ));

    $response = curl_exec($curl);

    $info =curl_errno($curl)>0 ? array("curl_error_".curl_errno($curl)=>curl_error($curl)) : curl_getinfo($curl);
    // print_r($info);

    curl_close($curl);
    // echo $response;

    return json_decode($response);
  }
}

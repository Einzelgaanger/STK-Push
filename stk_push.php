<?php
if(isset($_POST['submit'])){
    date_default_timezone_set('Africa/Nairobi');

    # access token
    $consumerKey = getenv('CONSUMER_KEY');
    $consumerSecret = getenv('CONSUMER_SECRET');
    
    $BusinessShortCode = getenv('BUSINESS_SHORTCODE');
    $Passkey = getenv('PASSKEY');  
    
    $PartyA = $_POST['phonenumber']; //Phone number, 
    $AccountReference = 'Pio Spices East Africa';
    $TransactionDesc = 'Test lipa na mpesa stk push initiation';
    $Amount = $_POST['amount'];
    
    $Timestamp = date('YmdHis');    
    
    $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);
    # header for access token
    $headers = ['Content-Type:application/json; charset=utf8'];
    # M-PESA endpoint urls
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    # callback url
    $CallBackURL = 'https://' . $_SERVER['HTTP_HOST'] . '/callback.php';  

    $curl = curl_init($access_token_url);
    //encode to base64
    $credentials = base64_encode($consumerKey.":".$consumerSecret);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_USERPWD,$consumerKey.":".$consumerSecret); 
    //excecute curl request
    $result = curl_exec($curl);
    $status = curl_getinfo($curl,CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    $accessToken = $result->access_token; 
   
    curl_close($curl);
    # header for stk push
    $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$accessToken];

    # initiating the transaction
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $initiate_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

    $curl_post_data = array(
        'BusinessShortCode' => $BusinessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $Amount,
        'PartyA' => $PartyA,
        'PartyB' => $BusinessShortCode,
        'PhoneNumber' => $PartyA,
        'CallBackURL' => $CallBackURL,
        'AccountReference' => $AccountReference,
        'TransactionDesc' => $TransactionDesc
    );

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);
    print_r($curl_response);

    echo $curl_response;
} else {
    echo 'error';
}
?>

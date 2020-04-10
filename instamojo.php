<?php
session_start();
$mobno=$_SESSION['mobno'];
$name=$_SESSION['uname'];
$email=$_SESSION['email'];
if (isset($_GET['amount'])) {
    $total=$_GET['amount'];
    if (isset($_GET['id'])) {
        $id=$_GET['id'];
    }
    else{
        header("Location:cart.php");
        exit();
    }
}
else{
    header("Location:cart.php");
    exit();
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payment-requests/');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER,
            array("X-Api-Key:test_81c8739502522266621c7b43888",
                  "X-Auth-Token:test_7fbd147f050b082a5c7968febeb"));
$payload = Array(
    'purpose' => 'Tech Sajjad Order #'.$id,
    'amount' => $total,
    'phone' => $mobno,
    'buyer_name' => $name,
    'redirect_url' => 'https://www.techsajjad.tk/success.php',
    'send_email' => false,
    'webhook' => '',
    'send_sms' => false,
    'email' => $email,
    'allow_repeated_payments' => false
);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
$response = curl_exec($ch);
curl_close($ch); 

$json_decode = json_decode($response,true);
$long_url=$json_decode['payment_request']['longurl'];
$pri=$json_decode['payment_request']['id'];
header("Location:".$long_url);

?>

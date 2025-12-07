<?php
header("content-type:application/json");
if(!isset($_GET['email'])){
echo json_encode([
    "status" => false,
    "msg" => "Enter Parameters"
    ]);
exit;
}
$name = $_GET['name'];
$email = $_GET['email']??'';
if(empty($name)){
echo json_encode([
    "status" => false,
    "msg" => "Name Required"
    ]);
exit;
}
if(empty($email)){
echo json_encode([
    "status" => false,
    "msg" => "Email Required"
    ]);
exit;
}

function httpCall($url, $postFields = [], $headers = []) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return json_encode([
            "status" => false,
            "error" => $error
        ]);
    }

    curl_close($ch);
    return $response;
}
generateFcmStyleToken() {
    $part1 = bin2hex(random_bytes(10)); // fc9oxX_UQVy6u...
    $part2 = bin2hex(random_bytes(15));
    $part3 = bin2hex(random_bytes(30));

    // Add symbols similar to Google tokens
    $part1 = substr(base64_encode(random_bytes(12)), 0, 22);

    $final = $part1 . ":" . strtoupper(bin2hex(random_bytes(40)));

    return $final;
}
function generateShortSecureToken($length = 30) {
    $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $token = "";
for ($i = 0; $i < $length; $i++) {
        $token .= $characters[random_int(0, strlen($characters) - 1)];
    }

    return $token;
}
$token1 = generateFcmStyleToken();
$token2 = generateShortSecureToken();
$url = "https://clipfun.fun/api/version_15/login_with_google";
$data = http_build_query([
    "google_id" => $token2,
    "name" => $name,
    "email" => $email,
    "image" => "https://lh3.googleusercontent.com/a/ACg8ocJJAaQnNindRIswqbXgzxBlAwXTixKlUJT17ceQsgcw8ES8iw=s96-c",
    "firebase_token" => $token1,
    "referrer_url" => "utm_source=(not%20set)&utm_medium=(not%20set)"
    ]);
$headers = [
    "Host: clipfun.fun",
    "x-api-key: 5cc8ff22bab10bd31294056f536e5598",
    "content-type: application/x-www-form-urlencoded",
    "content-length: ".strlen($data),
    "accept-encoding: gzip",
    "user-agent: okhttp/4.10.0"];

$response =  httpCall($url,$data,$headers);
$json = json_decode($response,true);
$msg = $json['msg'];
$user_id = $json['user_id'];
if($msg == "Login success"){
$coinUrl = "https://clipfun.fun/api/version_15/Add_Coin";
$coinData = "user_id=$user_id&video_id=15513";
$coinHeaders = [
    "Host: clipfun.fun",
    "x-api-key: 5cc8ff22bab10bd31294056f536e5598",
    "content-type: application/x-www-form-urlencoded",
    "content-length: ".strlen($coinData),
    "accept-encoding: gzip",
    "user-agent: okhttp/4.10.0"];
for($i=0;$i<10;$i++){
    
echo httpCall($coinUrl,$coinData,$coinHeaders);
}
}else{
    echo $response;
}
?>

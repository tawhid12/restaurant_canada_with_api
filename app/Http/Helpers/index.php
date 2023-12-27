<?php

function Replace($data) {
    $data = str_replace("!", "", $data);
    $data = str_replace("@", "", $data);
    $data = str_replace("#", "", $data);
    $data = str_replace("$", "", $data);
    $data = str_replace("%", "", $data);
    $data = str_replace("^", "", $data);
    $data = str_replace("&", "", $data);
    $data = str_replace("*", "", $data);
    $data = str_replace("(", "", $data);
    $data = str_replace(")", "", $data);
    $data = str_replace("?", "", $data);
    $data = str_replace("+", "", $data);
    $data = str_replace("=", "", $data);
    $data = str_replace(",", "", $data);
    $data = str_replace(":", "", $data);
    $data = str_replace(";", "", $data);
    $data = str_replace("|", "", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace('"', "", $data);
    $data = str_replace("  ", "-", $data);
    $data = str_replace(" ", "-", $data);
    $data = str_replace(".", "-", $data);
    $data = str_replace("__", "-", $data);
    $data = str_replace("_", "-", $data);
    return strtolower($data);
}

function encryptor($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
    $secret_key='beatnik#technolgoy_sampreeti';
    $secret_iv ='beatnik$technolgoy@sampreeti';

        // hash
    $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the encyption given text/string/number
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
            //decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

// The function to count words in Unicode  strings
function countUnicodeWords( $unicode_string ){
    // First remove all the punctuation marks & digits
    $unicode_string = preg_replace('/[[:punct:][:digit:]]/', '', $unicode_string);
    // Now replace all the whitespaces (tabs, new lines, multiple spaces) by single space
    $unicode_string = preg_replace('/[[:space:]]/', ' ', $unicode_string);
    // The words are now separated by single spaces and can be splitted to an array
    // I have included \n\r\t here as well, but only space will also suffice
    $words_array = preg_split( "/[\n\r\t ]+/", $unicode_string, 0, PREG_SPLIT_NO_EMPTY );
    // Now we can get the word count by counting array elments
    return count($words_array);
}

function limitWordShow($string, $word_limit){
    $words = explode(" ",$string);
    return implode(" ", array_splice($words, 0, $word_limit));
}

function currentUser(){
   return encryptor('decrypt', request()->session()->get('roleIdentity'));
}

function currentUserId(){
   return encryptor('decrypt', request()->session()->get('user'));
}

function company(){
    return ['companyId' => encryptor('decrypt', Session::get('companyId'))];
}

function branch(){
    return ['branchId' => encryptor('decrypt', Session::get('branchId'))];
}

function package(){
	$companyId = encryptor('decrypt', Session::get('companyId'));
	$packold=DB::select("select DATEDIFF(endAt,now()) as r from user_packages where companyId=$companyId and endAt >= date(now()) order by id DESC limit 1");
    if($packold && $packold[0]->r >= 0)
		return $packold[0]->r;
	else
		return -1;
}

function invoice(){
	return [
		['image'=>'invoice_first.PNG','link'=>'invoice_first'],
		['image'=>'invoice_second.PNG','link'=>'invoice_second'],
		['image'=>'invoice_third.JPG','link'=>'invoice_third']
	];
}

function menuActive($routeName, $type = null){
    if ($type == 3)
        $class = 'side-menu--open';
    else
        $class = 'active';
    
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value))
                return $class;
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}

function mobile_bank(){
    return ["Select Mobile Bank","Bkash","Rocket","Nagad","mCash","SureCash","Ucash"];
}

function sendSMS($contact, $text){
    /*$url = "https://esms.mimsms.com/smsapi";
    $data = [
        "api_key" => "C20090626197dd85101bd7.34935998",
        "type" => "text",
        "contacts" => $contact,
        "senderid" => "8809612436737",
        "msg" => $text,
     ];
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     $response = curl_exec($ch);
     curl_close($ch);
     return $response;*/
}
function verificationCode($length)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = 0;
    while ($length > 0 && $length--) {
        $max = ($max * 10) + 9;
    }
    return random_int($min, $max);
}

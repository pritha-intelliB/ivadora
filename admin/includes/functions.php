<?php
$currentdatetime = strtotime(date("Y-m-d H:i:s")); 
$convdatetime = date("Y-m-d H:i:s", $currentdatetime);

function replacewith($string){
$string = str_replace(" ","-",$string);
return strtolower($string);	
}


function getToken($length){
     /*$token = "";
     $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
     $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
     $codeAlphabet.= "0123456789";
     $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;*/
	 $number = uniqid();
    $varray = str_split($number);
    $len = sizeof($varray);
    $otp = array_slice($varray, $len-$length, $len);
    $otp = implode(",", $otp);
    $otp = str_replace(',', '', $otp);
    return($otp);
}
?>
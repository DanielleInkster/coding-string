<?php

$sensitive_data = ['CardCVV', 'cardCVV', 'CVVCVCSecurity', 'CardNumber', 
'CardDataNumber', 'CardExpiry', 'CardExp', 'Exp', 'cardNumber', 'cardExpiry',
'07_Account_Card_Number', '07_Account_Expiry', '17_CVV'];

function redactInfo($string, $sensitive_data){;
    if (is_object(json_decode($string))){  
        jsonString($sensitive_data, $string);
    } else if (substr($string, 0, 7) == "Request"){
        queryString($sensitive_data, $string);
    } else if (substr($string, 0, 5) == "<?xml"){
        xmlString($sensitive_data, $string);
    } else {
        arrayString($sensitive_data, $string);
    }
}

// identifies and redacts specified values
function searchInfo($sensitive_data, $array){
    foreach($sensitive_data as $data) {
        if (array_key_exists($data, $array)){
         $length = strlen($array[$data]); 
         $array[$data] = str_repeat('*', $length);
         }; 
     }
     return $array;
}
 //manipulates data in a json string
function jsonString($sensitive_data, $string){
    $array = json_decode($string, TRUE);
        $array = searchInfo($sensitive_data, $array);
        $string = json_encode($array);
        return strval($string);
}
//manipulates data in a xml string - return is not yet complete
function xmlString($sensitive_data, $string){
    $json= json_encode(simplexml_load_string($string));
        $array = json_decode($json, TRUE);
        $array = $array["NewOrder"];
        $array =searchInfo($sensitive_data, $string);
        $array = array_flip($array);
        $xml = new SimpleXMLElement('<Request/>');
        array_walk_recursive($array, array ($xml, 'addChild'));
        $string =  $xml->asXML();
        return strval($string);
}
//manipulates data in a query string
function queryString($sensitive_data, $string){
        parse_str($string, $array);
        $array = searchInfo($sensitive_data, $array);
        $string = urldecode(http_build_query($array));
        return strval($string);
}
//manipulates data in an array string - too bulky and return is not yet complete
function arrayString($sensitive_data, $string){
    $string = str_replace("\n", "&", $string);
        $string = str_replace(" => ", "=", $string);
        $string = str_replace("[", "", $string);
        $string = str_replace("]", "", $string);
        parse_str($string, $array);
        $array = searchInfo($sensitive_data, $array); 
        return $array;
}

;
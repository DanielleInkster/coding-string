
<?php

function redactInfo($string){
    $sensitive_data = ['CardCVV', 'CardNumber', 'CardExp'];
  
    if (is_object(json_decode($string))) 
    {   
        $array = json_decode($string, TRUE);

        foreach($sensitive_data as $data) {
            array_key_exists($data, $array);
            $length = strlen($array[$data]); 
            $array[$data] = str_repeat('*', $length);
            };
            print_r(json_encode($array));
    }
}


$string_1 = '{
    "MsgTypId": 111231232300,
    "CardNumber": "4242424242424242",
    "CardExp": "1024",
    "CardCVV": "240",
    "TransProcCd": "004800",
    "TransAmt": "57608",
    "MerSysTraceAudNbr": "456211",
    "TransTs": "180603162242",
    "AcqInstCtryCd": "840",
    "FuncCd": "100",
    "MsgRsnCd": "1900",
    "MerCtgyCd": "5013",
    "AprvCdLgth": "6",
    "RtrvRefNbr": "1029301923091239"
}';
redactInfo($string_1);
; 
<?php

function contains($str, $sensitive_data= array("Card Num", "Exp", "CVV"))
{
    foreach($sensitive_data as $data) {
        if (stripos($str,$data) !== false) echo "{$data} found!";
    }
}


;
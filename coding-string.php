<?php
//function below found here: https://gist.github.com/troelskn/1287893
//used as checksum for data integrity
function is_valid_luhn($number) {
    settype($number, 'string');
    $sumTable = array(
      array(0,1,2,3,4,5,6,7,8,9),
      array(0,2,4,6,8,1,3,5,7,9));
    $sum = 0;
    $flip = 0;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
      $sum += $sumTable[$flip++ & 0x1][$number[$i]];
    }
    return $sum % 10 === 0;
  }

function redact($string) {
	$card_regex = '/(?:\d[ \t-]*?){13,19}/m';
	$cvv_regex = '/cvv[=>:" \].a-zA-Z]*\d{3,4}[\&\"\<]*/mi';
	$cvv_regex_2 = '/\b\d{3,4}\b/m';

	$expiry_regex = '/exp.*[=>:" \].a-zA-Z]*\d{3,4}[\&\"\<]*/mi';
	$expiry_regex_2 = '/\b\d{4}\b/m';


    $card_matches = [];
    $cvv_matches = [];
    $expiry_matches = [];

    preg_match_all($card_regex, $string, $card_matches);
    preg_match_all($cvv_regex, $string, $cvv_matches);
    preg_match_all($expiry_regex, $string, $expiry_matches);

    foreach ($card_matches as $match_group)
    {
        foreach ($match_group as $match)
        {
            $stripped_match = preg_replace('/[^\d]/', '', $match);

            if (false === is_valid_luhn($stripped_match))
			// Is it a valid Luhn number?
            {
                continue;
            }

            $card_length = strlen($stripped_match);
            $replacement = str_pad('', $card_length, "*");

            // If so, replace the match
            $string = str_replace($match, $replacement, $string);
        }
    }
	foreach ($cvv_matches as $match_group)
    {
        foreach ($match_group as $match)
        {
			$cvv_matches_2 = [];
			preg_match_all($cvv_regex_2, $match, $cvv_matches_2);
			//print_r($cvv_matches_2);
		}
	}
	foreach ($cvv_matches_2 as $match_group)
    {
        foreach ($match_group as $match)
        {
            $stripped_match = preg_replace('/[^\d]/', '', $match);
			//print_r($stripped_match);

            $card_length = strlen($stripped_match);
            $replacement = str_pad('', $card_length, "*");

            // If so, replace the match
            //$string = str_replace($match, $replacement, $string);
			$match2 = "/".$match."\b/";
            $string = preg_replace($match2, $replacement, $string);
        }
    }
	foreach ($expiry_matches as $match_group)
    {
        foreach ($match_group as $match)
        {
			$expiry_matches_2 = [];
			preg_match_all($expiry_regex_2, $match, $expiry_matches_2);
			//print_r($expiry_matches_2);
		}
	}
	foreach ($expiry_matches_2 as $match_group)
    {
        foreach ($match_group as $match)
        {
            $stripped_match = preg_replace('/[^\d]/', '', $match);
			//print_r($stripped_match);

            $card_length = strlen($stripped_match);
            $replacement = str_pad('', $card_length, "*");

            // If so, replace the match
            //$string = str_replace($match, $replacement, $string);
			$match2 = "/".$match."\b/";
            $string = preg_replace($match2, $replacement, $string);
        }
    }

    echo "$string";
}

//read line and regex for the following - replace with same number of *
//check if card number matches expected size/type/etc. 
//check if exp|expiry = 4 characters
// check if CVV = 3 characters

echo "\n\n\n==========1==========\n\n\n";
redact("[orderId] => 212939129
[orderNumber] => INV10001
[salesTax] => 1.00
[amount] => 21.00
[terminal] => 5
[currency] => 1
[type] => purchase
[avsStreet] => 123 Road
[avsZip] => A1A 2B2
[customerCode] => CST1001
[cardId] => 18951828182
[cardHolderName] => John Smith
[cardNumber] => 5454545454545454
[cardExpiry] => 1025
[cardCVV] => 100");

echo "\n\n\n==========2==========\n\n\n";
redact("Request=Credit Card.Auth Only&Version=4022&HD.Network_Status_Byte=*&HD.Application_ID=TZAHSK!&HD.Terminal_ID=12991kakajsjas&HD.Device_Tag=000123&07.POS_Entry_Capability=1&07.PIN_Entry_Capability=0&07.CAT_Indicator=0&07.Terminal_Type=4&07.Account_Entry_Mode=1&07.Partial_Auth_Indicator=0&07.Account_Card_Number=4242424242424242&07.Account_Expiry=1024&07.Transaction_Amount=142931&07.Association_Token_Indicator=0&17.CVV=200&17.Street_Address=123 Road SW&17.Postal_Zip_Code=90210&17.Invoice_Number=INV19291");

echo "\n\n\n==========3==========\n\n\n";
redact('{
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
}');

echo "\n\n\n==========4	==========\n\n\n";
redact("<?xml version='1.0' encoding='UTF-8'?>
<Request>
	<NewOrder>
		<IndustryType>MO</IndustryType>
		<MessageType>AC</MessageType>
		<BIN>000001</BIN>
		<MerchantID>209238</MerchantID>
		<TerminalID>001</TerminalID>
		<CardBrand>VI</CardBrand>
		<CardDataNumber>5454545454545454</CardDataNumber>
		<Exp>1026</Exp>
		<CVVCVCSecurity>300</CVVCVCSecurity>
		<CurrencyCode>124</CurrencyCode>
		<CurrencyExponent>2</CurrencyExponent>
		<AVSzip>A2B3C3</AVSzip>
		<AVSaddress1>2010 Road SW</AVSaddress1>
		<AVScity>Calgary</AVScity>
		<AVSstate>AB</AVSstate>
		<AVSname>JOHN R SMITH</AVSname>
		<OrderID>23123INV09123</OrderID>
		<Amount>127790</Amount>
	</NewOrder>
</Request>");
?>

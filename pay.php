<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
ini_set('display_errors', 1);
/*$apikey = "ZmI2NDY3MTYtNDkwMi00NjlkLWIwNjItOTZiZjJhODEwOTVhOmZkNjYyMWUzLTdjYmMtNDYxMC1hNTlmLTI3YjUyNWFmYjc4Mw==";
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "https://identity-uat.ngeniuspayments.com/auth/realms/ni/protocol/openidconnect/token"); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Authorization: Basic ".$apikey, 
	"Content-Type: application/x-www-form-urlencoded")); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query(array('grant_type' => 'client_credentials'))); 
$output = json_decode(curl_exec($ch)); 
$err = curl_error($ch);
if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}*/
//$access_token = $output->access_token;
?>
<?php

/*$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api-gateway-uat.ngenius-payments.com/identity/auth/access-token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>  json_encode(array('grant_type' => 'client_credentials')),
  CURLOPT_HTTPHEADER => array(
    "accept: application/vnd.ni-identity.v1+json",
    "authorization: Basic ZmI2NDY3MTYtNDkwMi00NjlkLWIwNjItOTZiZjJhODEwOTVhOmZkNjYyMWUzLTdjYmMtNDYxMC1hNTlmLTI3YjUyNWFmYjc4Mw==",
    "content-type: application/vnd.ni-identity.v1+json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}*/
?>
<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api-gateway-uat.ngenius-payments.com/transactions/outlets/15253c39-5955-4662-aceb-0bcaf39cd5f9/orders",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"action\":\"SALE\",\"amount\":{\"currencyCode\":\"AED\",\"value\":120}}",
  CURLOPT_HTTPHEADER => array(
    "accept: application/vnd.ni-payment.v2+json",
    "authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICIzOTVTT3NDdkZUY3NlRmpqNTNiZy1lbFBsUlJZci00OEUzWmN0eDloZnVRIn0.eyJqdGkiOiI4ODIxZTU5ZS1iNDhiLTRlNWItOTg1Ny05MTJlZTQzNzBmYzIiLCJleHAiOjE1NzExMzQ4ODgsIm5iZiI6MCwiaWF0IjoxNTcxMTM0NTg4LCJpc3MiOiJodHRwczovL2lkZW50aXR5LXVhdC5uZ2VuaXVzLXBheW1lbnRzLmNvbS9hdXRoL3JlYWxtcy9uaSIsImF1ZCI6ImZiNjQ2NzE2LTQ5MDItNDY5ZC1iMDYyLTk2YmYyYTgxMDk1YSIsInN1YiI6ImE0NTExYWQyLTIwNDItNDFjZC1iMDU4LTBmYzE1ZTk0ZjczNyIsInR5cCI6IkJlYXJlciIsImF6cCI6ImZiNjQ2NzE2LTQ5MDItNDY5ZC1iMDYyLTk2YmYyYTgxMDk1YSIsImF1dGhfdGltZSI6MCwic2Vzc2lvbl9zdGF0ZSI6IjUzNThlNTFiLTk0ZTktNDdmMy1iM2NiLTA4ODgyNjA0NjM5OSIsImFjciI6IjEiLCJhbGxvd2VkLW9yaWdpbnMiOltdLCJyZWFsbV9hY2Nlc3MiOnsicm9sZXMiOlsiQ1JFQVRFX0FVVEhPUklaQVRJT04iLCJWSUVXX1BBWU1FTlQiLCJSRVZFUlNFX0FVVEhPUklaQVRJT04iLCJNQU5BR0VfQ0FQVFVSRSIsIk1BTkFHRV9JTlZPSUNFUyIsIlZJRVdfQU5EX0RPV05MT0FEX1JFUE9SVFMiLCJWSUVXX09SREVSIiwiTUVSQ0hBTlRfU1lTVEVNUyIsIkNSRUFURV9WRVJJRklDQVRJT04iLCJDUkVBVEVfT05FX1NUQUdFX1NBTEUiLCJDUkVBVEVfT1JERVIiLCJNQU5BR0VfUkVGVU5EIiwiQ1JFQVRFX1NUQU5EX0FMT05FX1JFRlVORCJdfSwicmVzb3VyY2VfYWNjZXNzIjp7fSwic2NvcGUiOiIiLCJjbGllbnRJZCI6ImZiNjQ2NzE2LTQ5MDItNDY5ZC1iMDYyLTk2YmYyYTgxMDk1YSIsImNsaWVudEhvc3QiOiIxMDQuNDUuNjYuMTQiLCJyZWFsbSI6Im5pIiwiZ2l2ZW5fbmFtZSI6Im1lc2gtbXZwIiwiY2xpZW50QWRkcmVzcyI6IjEwNC40NS42Ni4xNCIsImhpZXJhcmNoeVJlZnMiOlsiNDZjYzdhMjEtZThlNy00MWQ2LWJiNzQtOTgxYjQxMWEwZTQ0Il19.NTUqK-9JEWD1vPDhrvzVTDgMSNXyaaURpZAzN273CMicZZVocLOlxmm0nbvOBck2TNdRAUejt8Wk9XWPDfymiMYMJHBmiJEonNd3Fyoj-qvz861ZpVl38HpzedZuKXSrOv6Meu8QjKbK6MmfKLkfPAXPgFVMrzMKo9W5TcE405FULuodfNSB1KX8m0_gJw3bhA5cVP3lBCkkF7t6tVjM8253CGY6oU8TuA7393h2zgD-4k7WKFXxUnt477qvmEXmJGHPVBFz0L0NtWO7mQ19EwYT-oTs4qNfD8aOgXWCBlNJO6w0N2t2m51zpU9q9NsTF4dONGad-77oW8abnI79CA",
    "content-type: application/vnd.ni-payment.v2+json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
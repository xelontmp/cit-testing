<?php


function get_data($url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_ENCODING, 0);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_URL, $url);
  // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  $data = curl_exec($ch);
  $info = curl_getinfo($ch);
  if (curl_errno($ch)) {
    throw new Exception('Curl error: ' . curl_error($ch));
  }
  curl_close($ch);
  if ($data === FALSE) {
    throw new Exception("curl_exec returned FALSE. Info follows:\n" . print_r($info, TRUE));
  }
  return $data;
}


if (isset($_GET["request"])) {
  get_data($_GET["request"]);
  // get_data('https://uslugi.gospmr.org/?option=com_uslugi&view=gosuslugi&task=getUslugi');
}
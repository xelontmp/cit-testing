<?php

require_once('./add_log.php');


// function array_to_xml($data, &$xml_data)
// {
//   foreach ($data as $key => $value) {
//     if (is_array($value)) {
//       if (is_numeric($key)) {
//         $key = 'item' . $key; //dealing with <0/>..<n/> issues
//       }
//       $subnode = $xml_data->addChild($key);
//       array_to_xml($value, $subnode);
//     } else {
//       $xml_data->addChild("$key", htmlspecialchars("$value"));
//     }
//   }
// }


function file_name_prefix()
{
  date_default_timezone_set('Europe/Chisinau');
  return date('Y-m-d H-i-s');
}


$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
// echo gettype($data);
// print_r($data);
/* $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');*/
// array_to_xml($data, $xml_data);
//saving generated xml file;
$file_path = 'uploads/';
$file_temlate_path = 'template/';
$file_name_result = file_name_prefix() . '.xml';
$file__head_name = $file_temlate_path . 'template1.xml';
$file__bottom_name = $file_temlate_path . 'template2.xml';
// $result = $xml_data->asXML('uploads/' . $file_name);
// echo file_name_prefix();
$rezult_data = '';
foreach ($data as $key => $value) {
// print_r($key);
// print_r($value);
// echo '<br>';
$rezult_data .= '<tr>';
  $rezult_data .= '<td width="64">
    <p align="center">' . trim($value['id']) . '</p>
  </td>';
  $rezult_data .= '<td width="250">
    <p align="center">' . trim($value['name']) . '</p>
  </td>';
  $rezult_data .= '<td width="171">
    <p align="center">' . trim($value['organization']) . '</p>
  </td>';
  $rezult_data .= '<td width="172">
    <p align="center">' . trim($value['payment']['payment_amount']) . '</p>
  </td>';
  $rezult_data .= '</tr>';
}
// add_log($rezult_data);
// Первый файл
if (file_exists($file__head_name)) {
$file_content_head = file_get_contents($file__head_name);
} else {
die('Первая часть файла не найдена');
}
if (file_exists($file__bottom_name)) {
$file_content_bottom = file_get_contents($file__bottom_name);
} else {
die('Вторая часть файла не найдена');
}
file_put_contents($file_path . $file_name_result, $file_content_head, FILE_APPEND | LOCK_EX);
file_put_contents($file_path . $file_name_result, $rezult_data, FILE_APPEND | LOCK_EX);
file_put_contents($file_path . $file_name_result, $file_content_bottom, FILE_APPEND | LOCK_EX);
// print_r($data);
echo $file_name_result;
<?
error_reporting(0);

function send_file_to_ftp($filename, $remote_file, $ftp_server, $ftp_user_name, $ftp_user_pass)
{
  // $filename = '2022-04-25 02-17-44.xml';
  $file = 'uploads/' . $filename;
  // установка соединения
  $ftp = ftp_connect($ftp_server);
  // проверка имени пользователя и пароля
  ftp_login($ftp, $ftp_user_name, $ftp_user_pass) or die("{\"error\": 100, \"description\": \"Cannot login\"}");
  ftp_pasv($ftp, true) or die("{\"error\": 100, \"description\": \"Cannot switch to passive mode\"}");

  // загрузка файла
  if (ftp_put($ftp, $remote_file, $file, FTP_BINARY)) {
    echo "{\"error\": 0, \"description\": \"$filename успешно загружен на сервер\", \"filename\": \"$filename\"}";
  } else {
    echo "{\"error\": 100, \"description\": \"Не удалось загрузить $filename на сервер\"}";
  }
  // закрытие соединения
  ftp_close($ftp);
}

$postData = file_get_contents('php://input');
// echo gettype($postData);
$data = json_decode($postData, true);
// print_r($data);
($data['host'] && $data['user'] && $data['pass'] && $data['filename']) or die("{\"error\": 100, \"description\": \"Недостаточно параметров для отправки по ftp\"}");

send_file_to_ftp($data['filename'], $data['filename'], $data['host'], $data['user'], $data['pass']);
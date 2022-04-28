<?
require_once('./add_log.php');


function query_execute($conn, $sql)
{
  return $conn->query($sql);
}


function add_log_date_access($servername, $dbname, $username, $password, $port, $mess)
{
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname, $port);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  // check if table exist 
  if (query_execute($conn, "SELECT * FROM information_schema.tables WHERE table_schema = 'sql11488099' AND table_name = 'cit_access_log' LIMIT 1;")->num_rows == 0) {
    if (query_execute($conn, "CREATE TABLE `cit_access_log` (
    `access_timestamp` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	  `message` VARCHAR(2000) NOT NULL DEFAULT 'current_timestamp()' COLLATE 'utf8mb4_unicode_ci'
  );") === TRUE) {
      echo "Таблица cit_access_log успешно создана<br>";
    } else {
      die("Ошибка создания таблицы: " . $conn->error);
    }
  }
  query_execute($conn, 'insert into cit_access_log (message) values ("' . trim($mess) . '");');
  // add_log(query_execute($conn, "select * from cit_access_log;")->fetch_all(MYSQLI_ASSOC));

  // add_log(query_execute($conn, "drop table cit_access_log"));

  // $access_list = query_execute($conn, "select * from cit_access_log;")->fetch_all(MYSQLI_ASSOC);
  // $access_list = mysqli_fetch_all(query_execute($conn, "select * from cit_access_log;"), MYSQLI_ASSOC);
  $access_list = query_execute($conn, "select * from cit_access_log order by access_timestamp DESC LIMIT 5;");
  // add_log($access_list);
  echo 'Время запросов сервиса и статусы завершения (5 записей)<br>';
  // foreach ($access_list as $list_item) {
  while ($row = $access_list->fetch_assoc()) {
    // echo $list_item['access_timestamp'] . '<br>';
    echo $row['access_timestamp'] . ' - Состояние завершения: ' . $row['message'] . '<br>';
    // add_log($row);
  }
  echo ' * * * <br>';
  $conn->close();
}


$postData = file_get_contents('php://input');
// echo gettype($postData);
$data = json_decode($postData, true);

($data['servername'] && $data['dbname'] && $data['username'] && $data['password'] && $data['port'] && $data['mess']) or die("not enough parameters");
// print_r($data);
add_log_date_access($data['servername'], $data['dbname'], $data['username'], $data['password'], $data['port'], $data['mess']);
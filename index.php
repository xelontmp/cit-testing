<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link href="assets/css/style.css" rel="stylesheet">
  <!-- <script src="//uslugi.gospmr.org/?option=com_uslugi&view=gosuslugi&task=getUslugi"></script> -->
</head>

<body>
  <section class="container">
    <h1>Тестирование работы сервиса</h1>
    <form>
      <label for="basic-url" class="form-label">Введите начальный адрес для запроса данных</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3"
          value="https://uslugi.gospmr.org/?option=com_uslugi&view=gosuslugi&task=getUslugi">
      </div>
      <label for="basic-url-2" class="form-label">Адрес для запроса каждой услуги (без параметра id)</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control" id="basic-url-2" aria-describedby="basic-addon3"
          value="https://uslugi.gospmr.org/?option=com_uslugi&view=usluga&task=getUsluga&uslugaId=">
      </div>
      <label for="ftp-user-name" class="form-label">Параметры подключения к ftp серверу</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control" id="ftp-host" placeholder="Host" aria-label="Host"
          value="ftp.dlptest.com">
        <span class="input-group-text"></span>
        <input type="text" class="form-control" id="ftp-user-name" placeholder="Username" aria-label="Username"
          value="dlpuser">
        <span class="input-group-text"></span>
        <input type="password" class="form-control" id="ftp-password" placeholder="Password" aria-label="Password"
          value="rNrKYTX9g7z3RgJRmxWuGHbeu">
      </div>
      <label for="ftp-user-name" class="form-label">Параметры подключения к MySQL серверу</label>
      <div class="input-group mb-3">
        <span class="input-group-text">Адрес и порт</span>
        <input type="text" class="form-control" id="db-host" placeholder="Host" aria-label="Host"
          value="sql11.freesqldatabase.com">
        <span class="input-group-text">:</span>
        <input type="text" class="form-control" id="db-port" placeholder="Port" aria-label="Port" value="3306">
        <span class="input-group-text"></span>
        <input type="text" class="form-control" id="db-name" placeholder="Database name" aria-label="Database name"
          value="sql11488099">
        <span class="input-group-text"></span>
        <input type="text" class="form-control" id="db-user-name" placeholder="Username" aria-label="Username"
          value="sql11488099">
        <span class="input-group-text"></span>
        <input type="password" class="form-control" id="db-password" placeholder="Password" aria-label="bH9RL25DPc"
          value="bH9RL25DPc">
      </div>
      <input class="btn btn-primary" type="button" id="main-action" value="Начать обработку">
    </form>
  </section>
  <section class="container progress-container">
    <div id="progress" class="progress">
      <!-- <div class="progress-bar" role="progressbar" style="width: 1%;" aria-valuenow="1" aria-valuemin="0" aria-valuemax="8">1 из 8</div> -->
    </div>
  </section>
  <section id="log-container" class="container">
  </section>
  <script src="assets/js/main.js"></script>
</body>

</html>
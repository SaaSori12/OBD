<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма регістрації</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/forma.css">
</head>
<body>
  <div class="conteiner mt-4">
    <?php
      if($_COOKIE['user_hash'] == ''):
    ?>
    <div class="row">
      <div class="col">
      <h1>Регістрація</h1><br>
      <form action="validation-form/check.php" method="post">
        <input type="text" class="form-control" name="login" id="login" placeholder="Введіть логін"><br>
        <input type="text" class="form-control" name="name" id="name" placeholder="Введіть ім'я"><br>
        <input type="password" class="form-control" name="pass" id="pass" placeholder="Введіть пароль"><br>
        <button class="btn btn-success" type="submit">Зареєструвати</button>
    </form>
      </div>
      <div class="col">
      <h1>Авторизація</h1><br>
      <form action="validation-form/auth.php" method="post">
        <input type="text" class="form-control" name="login" id="login" placeholder="Введіть логін"><br>
        <input type="password" class="form-control" name="pass" id="pass" placeholder="Введіть пароль"><br>
        <button class="btn btn-success" type="submit">Авторизувати</button>
    </form>
      </div>
      <?php else: ?>
        <?header('Location: customer.php')?>;
      <?php endif;?>

    </div>
  </div>
</body>
</html>
<?php
require_once('back/connection.php');
$query = 'select * from enterprises';
$stmt = $connection->query($query);
$res = $stmt->fetchAll();
$titles = array();
array_push($titles,'');
for ($i = 0; $i < count($res); $i++) {
        array_push($titles,$res[$i]['subdivision']);
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Росатом Госкорпорация «Росатом» ядерные технологии атомная энергетика АЭС ядерная медицина ядерное машиностроение ядерное топливо атомный ледокол добыча урана ветроэнергетика цифровизация</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="shortcut icon" href="/img/rosatom-1.png" type="image/x-icon">
</head>
<body>
<a href="" class="show" style="display: none">Показать ошибку</a>
<a href="" class="none1" style="display: none">Показать успех</a>
<img src="img/rosatom-2.png" class="rosatom-logo">
<form class="signup-form">
    <h2 style="color: white">Регистрация</h2>
    <label for="form-control">Выберите подразделение</label>
    <select name="subdivision" class="form-control">
        <?php
        for ($i = 0; $i < count($titles); $i++){
            echo '<option style="width: 200px;" value="'.$titles[$i].'">'.$titles[$i].'</option>';
        }
        ?>
    </select>
    <label for="form-control">Табельный номер</label>
    <input type="number" name="p-number" class="form-control" placeholder="12345678">
    <label for="form-control">Должность</label>
    <input type="text" name="position" class="form-control" placeholder="Должность">
    <label for="form-control">Фамилия</label>
    <input type="text" name="surname" class="form-control" placeholder="Фамилия">
    <label for="form-control">Имя</label>
    <input type="text" name="name" class="form-control" placeholder="Имя">
    <label for="form-control">Отчество</label>
    <input type="text" name="mid-name" class="form-control" placeholder="Отчество">
    <label for="form-control">Номер телефона</label>
    <input type="text" name="phone" id="phone" class="form-control" placeholder="8(999) 999-9999">
    <label for="form-control">Адрес электронной почты</label>
    <input type="email" name="email" class="form-control" placeholder="name@example.com">
    <label for="form-control">Пароль</label>
    <input type="password" name="pass" class="form-control" placeholder="Пароль">
    <label for="form-control">Подтверждение пароля</label>
    <input type="password" name="pass-confirm" class="form-control" placeholder="Пароль">
    <button type="submit" class="btn btn-primary register-btn" style="margin-top: 20px">Зарегистрироваться</button>
    <p class="p1" style="color: white; margin-top: 10px">
        У вас есть аккаунт?  <a href="index.php">Войти</a>
    </p>
    <p class="msg-suc none">Lorem ipsum dolor sit amet.</p>
    <p class="msg-err none">Lorem ipsum dolor sit amet.</p>
</form>

</body>
<script src="js/jQuery.js"></script>
<script src ="js/jquery.maskedinput.min.js"></script>
<script src="js/main.js"></script>
</html>
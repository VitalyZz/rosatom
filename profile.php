<?php
session_start();
require_once('back/connection.php');
$query = 'select p_number from users where email = ?';
$stmt = $connection->prepare($query);
$stmt->execute([$_SESSION['user']['email']]);
$res = $stmt->fetch();
$p_number =  $res['p_number'];

$query = 'select id_inst,position from info where p_number = ?';
$stmt = $connection->prepare($query);
$stmt->execute([$p_number]);
$res = $stmt->fetch();
$id_inst =  $res['id_inst'];
$pos = $res['position'];

$query = 'select path from instructions where id_inst = ?';
$stmt = $connection->prepare($query);
$stmt->execute([$id_inst]);
$res = $stmt->fetch();
$full_path=  $res['path'];
$p = explode('||',$full_path);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Росатом Госкорпорация «Росатом» ядерные технологии атомная энергетика АЭС ядерная медицина ядерное машиностроение ядерное топливо атомный ледокол добыча урана ветроэнергетика цифровизация</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="shortcut icon" href="/img/rosatom-1.png" type="image/x-icon">
</head>
<body>
<a href="back/logout.php"><button class="btnExit" style="position: absolute;top:20px;right: 20px;">Выход</button></a>
<a href="download.php" style="color: #FFFFFF; background-color: #0000cc; padding: 5px 15px; border: none; text-decoration: none; position: absolute; top: 20px; left: 20px;">Скачать в PDF</a>
<div class="div" style="position: absolute;left: 50%;transform: translate(-50%,0);">
<h2 class="youIn">Вы в личном кабинете!</h2>
<h3 style="color: white; margin-top: 5px;"><?=$_SESSION['user']['email']?></h3
    <div class="download_div" style="width: 800px; display: flex;flex-wrap: wrap; margin-top: 20px;">
    <?php
    foreach ($p as $pa) {
        echo '<a href="' .$pa.'" download="" style="margin-right: 20px;"><button class="btnDownload">Скачать</button></a>';
        $filename = $_SERVER['DOCUMENT_ROOT'].'/files/files1/'.substr($pa, -7);
        $fileContents = file($filename);

        $pattern = "/II. Должностные обязанности/";
        $linesFound = preg_grep($pattern, $fileContents);
        $first = array_keys($linesFound)[0] . '</br>';
        $first += 3;

        $pattern = "/III. Права/";
        $linesFound = preg_grep($pattern, $fileContents);
        $second = array_keys($linesFound)[0] . '</br>';
        $second -= 3;

        $fd = fopen($filename, 'r') or die("не удалось открыть файл");
        $i = 0;
        $str = '';
        $text = [];
        $txt = '';
        while (!feof($fd)) {
            $str = htmlentities(fgets($fd));
            if ($i >= $first && $i <= $second) {
                array_push($text, $str);
                $txt .= $str;
                $txt .= "<br>";
            }
            $i++;
        }
        fclose($fd);

        echo '<div class="text_div"><h1 class="instr">Должностная инструкция: '.$pos.'</h1></div>';
        foreach ($text as $el) {
            echo '<p class="elOfText">'.$el.'</p>';
        }
    }

    ?>
    </div>
</div>
</body>
<script src="js/jQuery.js"></script>
<script src="js/main.js"></script>
</html>
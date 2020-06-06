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
require_once 'dompdf/lib/html5lib/Parser.php';
require_once 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'dompdf/src/Autoloader.php';
Dompdf\AutoLoader::register();
// reference the Dompdf namespace
use Dompdf\Dompdf;


/////////////////////////////



foreach ($p as $pa) {
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
    $txt = '';
    $text = [];
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

    $ggg = '<div class="text_div"><h1 class="instr">Должностная инструкция: '.$pos.'</h1></div>';
    foreach ($text as $el) {
        $ggg .= '<p class="elOfText">'.$el.'</p>';
//        echo '<p class="elOfText">'.$el.'</p>';
    }

}


///////////////////////////

// instantiate and use the dompdf class
$dompdf = new Dompdf([
    'defaultFont' => 'DejaVu Serif'
]);
$dompdf->loadHtml("
<style>
.text_div {
    background-color: #0094ff;
    padding: 10px 30px;
    margin-top: 30px;
    margin-bottom: 20px;
    color: #fff;
    border-radius: 3px;
}

.instr {
    font-size: 28px;
    text-align: center;
}

.elOfText {
    background-color: #ffd12a;
    font-size: 18px;
    color: #1B1B1B;
    padding: 10px 30px;
    margin-top: 15px;
    font-weight: bold;
}
</style>
$ggg");

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
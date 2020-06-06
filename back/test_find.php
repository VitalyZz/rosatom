<?php
$filename = "008.txt";
$fileContents = file($filename);

$pattern = "/Должностные обязанности/";
$linesFound = preg_grep($pattern, $fileContents);
$first = array_keys($linesFound)[0].'</br>';
$first += 3;

echo "<pre>", print_r($linesFound, true), "</pre>";
$pattern = "/Права/";
$linesFound = preg_grep($pattern, $fileContents);
$second = array_keys($linesFound)[0].'</br>';
$second -= 3;

echo "<pre>", print_r($linesFound, true), "</pre>";


$fd = fopen($filename, 'r') or die("не удалось открыть файл");
$i = 0;
$str = '';
$text = '';
while(!feof($fd))
{
    $str = htmlentities(fgets($fd));
    if ($i >= $first && $i <= $second) {
        $text .= $str;
        $text .= "<br>";
    }
    $i++;
}
fclose($fd);

echo $text;




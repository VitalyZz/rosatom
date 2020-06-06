<?php
print_r(find_word('База'));
    function find_word($value)
    {
        $dir = '../files/files1';
        $files1 = scandir($dir);
        $st_strpos = $value;
        $k = 0;
        $arr = array();
        for ($i = 2; $i < /*count($files1)*/5; $i++) {
            $st_search = 'files1/' . $files1[$i];
            if (strpos(file_get_contents("$st_search"), "$st_strpos")) {
                array_push($arr,('Есть совпадение ' . $files1[$i] . '</br>'));
                $k++;
            }
        }
        if ($k == 0) {
            array_push($arr,('Нет совпадений'));
        }
        return $arr;
    }
?>


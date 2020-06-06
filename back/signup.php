<?php
session_start();
require_once('connection.php');

$p_number = $_POST['p-number'];
$surname = $_POST['surname'];
$name = $_POST['name'];
$mid_name = $_POST['mid-name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$pass_confirm = $_POST['pass-confirm'];
$sub = $_POST['subdivision'];
$pos = $_POST['position'];

$path = find_word($pos);
for($i = 0; $i < count($path); $i++){
    if($i == 0) {
        $full_path .= $path[$i];
    }else{
        $full_path .= '||'.$path[$i];
    }
}

$id_info = rand(0,1000000);
$id_inst = rand(0,1000000);
$error_fields = [];
if($p_number === ''){
    $error_fields[] = 'p-number';
}
if($pos === ''){
    $error_fields[] = 'position';
}
if($sub === ''){
    $error_fields[] = 'subdivision';
}
if($surname === ''){
    $error_fields[] = 'surname';
}
if($name === ''){
    $error_fields[] = 'name';
}
if($mid_name === ''){
    $error_fields[] = 'mid-name';
}
if($phone === ''){
    $error_fields[] = 'phone';
}
if($email === '' || !filter_var($email,FILTER_VALIDATE_EMAIL)){
    $error_fields[] = 'email';
}
if($pass === ''){
    $error_fields[] = 'pass';
}
if($pass_confirm === ''){
    $error_fields[] = 'pass-confirm';
}

if(!empty($error_fields)){
    $response = [
        "status" => false,
        "type" => 1,
        "message" => "Проверьте правильность полей",
        "fields" => $error_fields
    ];

    echo json_encode($response);

    die();
}

    if($pass_confirm == $pass) {
        $query = 'INSERT INTO users(p_number, email, pass) VALUES (?,?,?)';
        $stmt = $connection->prepare($query);
        $stmt->execute([$p_number, $email, md5($pass)]);

        $query = 'SELECT id_sub from enterprises where subdivision = ? ';
        $stmt = $connection->prepare($query);
        $stmt->execute([$sub]);
        $id_sub = $stmt->fetch();
        $id_sub = $id_sub['id_sub'];

        $query = 'INSERT INTO `instructions`(`id_inst`, `path`) VALUES (?,?)';
        $stmt = $connection->prepare($query);
        $stmt->execute([$id_inst,$full_path]);


        $query = 'INSERT INTO `info`(`surname`, `name`, `mid_name`, `phone`, `id_info`, `id_sub`, `p_number`, `id_inst`, `position`) VALUES (?,?,?,?,?,?,?,?,?)';
        $stmt = $connection->prepare($query);
        $stmt->execute([$surname,$name,$mid_name,$phone,$id_info,$id_sub,$p_number,$id_inst,$pos]);


        $_SESSION['user'] = [
            'email' => $email
        ];
        $response = [
            "status" => true,
            "message" => "Регистрация прошла успешно",
        ];
        echo json_encode($response);
    } else{
        $response = [
            "type" => 2,
            "status" => false,
            "message" => "Пароли не совпадают",
        ];

        echo json_encode($response);
    }


function find_word($value)
{
    $dir = '../files/files1';
    $files1 = scandir($dir);
    $st_strpos = $value;
    $k = 0;
    $arr = array();
    for ($i = 2; $i < count($files1); $i++) {
        $st_search = '../files/files1/' . $files1[$i];
        if (strpos(file_get_contents("$st_search"), "$st_strpos")) {
            array_push($arr,( '../files/files1/'.$files1[$i]));
            $k++;
        }
    }
    if ($k == 0) {
        array_push($arr,('Нет совпадений'));
    }
    return $arr;
}
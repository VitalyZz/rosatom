<?php
session_start();
require_once('connection.php');
$email = $_POST['email'];
$pass = $_POST['pass'];
$error_fields = [];
if($email === '' || !filter_var($email,FILTER_VALIDATE_EMAIL)){
    $error_fields[] = 'email';
}
if($pass === ''){
    $error_fields[] = 'pass';
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

$query = 'select * from users where email = ? and pass = ?';
$stmt = $connection->prepare($query);
$stmt->execute([$email,md5($pass)]);
$res = $stmt->fetch();
if($stmt->rowCount() > 0){
    foreach ($res as $r){
        $_SESSION['user'] = [
            'email' => $email
        ];
    }
    $response = [
        "status" => true
    ];
    echo json_encode($response);
} else{
    $response = [
        "status" => false,
        "message" => 'Неверный логин или пароль'
    ];
    echo json_encode($response);
}
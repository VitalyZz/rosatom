<?php
$connection = new PDO('mysql:host=localhost;dbname=rosatom3', 'root', '');
if (!$connection) {
    die('Error connect to db!');
}
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$con = mysqli_connect('localhost', 'root', '1234', 'SMARTCHEFBD');
if (!$con) {
    echo "Falha ao ligar a base de dados: " . mysqli_connect_error();
    exit();
}

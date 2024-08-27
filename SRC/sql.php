<?php
$con = mysqli_connect('localhost', 'root', '1234');

if (!$con) {
    echo "Falha ao ligar a base de dados: " . mysqli_connect_error();
    exit();
}

$sql = file_get_contents('pi.sql');

// Execute as consultas SQL
$queries = explode(';', $sql);
foreach ($queries as $query) {
    if (!empty($query)) {
        $con->query($query);
    }
}

$con->close();
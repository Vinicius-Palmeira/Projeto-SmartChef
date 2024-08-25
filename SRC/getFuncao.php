<?php
include('check.php');
$stmt = $con->prepare("SELECT FUNCAO FROM FUNCIONARIOS WHERE ID = ? LIMIT 1");
$stmt->bind_param("i", $_COOKIE["ID"]);
$stmt->execute();
$stmt = $stmt->get_result()->fetch_assoc();
echo "" . $stmt['FUNCAO'];
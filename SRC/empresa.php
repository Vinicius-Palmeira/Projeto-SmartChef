<?php
include('check.php');
$stmt = $con->prepare("SELECT USERNAME FROM EMPRESA WHERE ID = ? LIMIT 1");
$stmt->bind_param("i", $_COOKIE["EMPRESA_ID"]);
$stmt->execute();
$stmt = $stmt->get_result()->fetch_assoc();
echo "" . $stmt['USERNAME'];
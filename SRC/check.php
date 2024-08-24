<?php
include("conexao.php");

if (isset($_COOKIE["EMPRESA_ID"]) && isset($_COOKIE["ID"]) && isset($_COOKIE["TOKEN"]) && isset($_COOKIE["CHAVE"]) && isset($_COOKIE["FUNCAO"])) {
    $stmt = $con->prepare("SELECT EMPRESA_ID, FUNCAO FROM FUNCIONARIOS WHERE (ID = ? AND TOKEN LIKE ? AND `CHAVE` = ?) LIMIT 1");
    $stmt->bind_param("isi", $_COOKIE["ID"], $_COOKIE["TOKEN"], $_COOKIE["CHAVE"]);
    $stmt->execute();
    $login = $stmt->get_result()->fetch_assoc();

    // Check if exists
    if (!$login) {
        die("<script>location.href = 'SRC/logout.php';</script>");
    } else {
        setcookie("EMPRESA_ID", $_COOKIE['EMPRESA_ID'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("ID", $_COOKIE['ID'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("TOKEN", $_COOKIE['TOKEN'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("CHAVE", $_COOKIE['CHAVE'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("FUNCAO", $login['FUNCAO'], time() + (10 * 365 * 24 * 60 * 60));
    }
} else {
    die("<script>location.href = 'SRC/logout.php';</script>");
}
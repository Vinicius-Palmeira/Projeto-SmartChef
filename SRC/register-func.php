<?php
include("conexao.php");

if (isset($_POST["nome"]) && isset($_POST["senha"]) && isset($_POST["funcao"])&& isset($_POST["senha-repetida"] ) && isset($_COOKIE["EMPRESA_ID"] )) {
    
    $username = $_POST["nome"];
    $senha = $_POST["senha"];
    $funcao = $_POST["funcao"];
    $senharep = $_POST["senha-repetida"];
    $empid = $_COOKIE['EMPRESA_ID'];

    if ($username == "" || $senha == "" || $funcao == "" || $senharep == "" || $empid == "") {
        die(header("HTTP/1.0 401 Preenche todos os campos do formulario"));
    }

    $padrao = '/^[A-Za-z_-]+$/';

    if (preg_match($padrao, $username)) {
        $username =  strtoupper($username);
    } else {
        die(header("HTTP/1.0 401 O nome de usuario apresenta caracteres invalidos"));
    }

    if ($senha != $senharep) {
        die(header("HTTP/1.0 401 Passwords diferentes"));
    }

    $checkuser = $con->prepare("SELECT ID FROM FUNCIONARIOS WHERE USERNAME = ?");
    $checkuser->bind_param("s", $username);
    $checkuser->execute();
    $count = $checkuser->get_result()->num_rows;
    if ($count > 0) {
        die(header("HTTP/1.0 Nome de usuario ja existente"));
    }

    $password = password_hash($senha, PASSWORD_DEFAULT);

    $token = bin2hex(openssl_random_pseudo_bytes(20));
    $key = rand(100000000, 999999999);

    $stmt = $con->prepare("INSERT INTO FUNCIONARIOS (`ID`,`EMPRESA_ID`, `USERNAME`, `SENHA`, `FUNCAO`, `TOKEN`, `CHAVE`) VALUES (0, ?, ?, ?, ?, ?, ?);");
    $stmt->bind_param("issssi", $empid, $username, $password, $funcao, $token, $key);
    if (!$stmt->execute()) {
        die("Erro ao inserir na tabela FUNCIONARIOS: " . $stmt->error);
        echo "D";
    }

} else {
    die(header("HTTP/1.0 401 Formulário de autenticação inválido"));
}

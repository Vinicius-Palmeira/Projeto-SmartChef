<?php
include("conexao.php");

if (isset($_POST["user"]) && isset($_POST["telefone"]) && isset($_POST["cnpj"]) && isset($_POST["email"]) && isset($_POST["pass"]) && isset($_POST["word"])) {

    $username = $_POST["user"];
    $telefone = $_POST["telefone"];
    $cnpj = $_POST["cnpj"];
    $email = $_POST["email"];
    $password = $_POST["pass"];
    $word = $_POST["word"];

    if ($username == "" || $telefone == "" || $cnpj == "" || $email == "" || $password == "" || $word == "") {
        die(header("HTTP/1.0 401 Preenche todos os campos do formulario"));
    }

    $padrao = '/^[A-Za-z_-]+$/';

    if (preg_match($padrao, $username)) {
        $username =  strtoupper($username);
    } else {
        die(header("HTTP/1.0 401 O nome de usuario apresenta caracteres invalidos"));
    }

    /*
    $padrao = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

    if (!preg_match($padrao, $password)) {
        die(header("HTTP/1.0 401 Senha não atende aos requisitos de segurança."));
    }
    */

    $checkuser = $con->prepare("SELECT ID FROM EMPRESA WHERE USERNAME = ?");
    $checkuser->bind_param("s", $username);
    $checkuser->execute();
    $count = $checkuser->get_result()->num_rows;
    if ($count > 0) {
        die(header("HTTP/1.0 Nome de usuario ja existente"));
    }

    $checktelefone = $con->prepare("SELECT ID FROM EMPRESA WHERE TELEFONE = ?");
    $checktelefone->bind_param("s", $telefone);
    $checktelefone->execute();
    $count = $checktelefone->get_result()->num_rows;
    if ($count > 0) {
        die(header("HTTP/1.0 401 Conta registada com este telefone ja existente"));
    }

    $checkcnpf = $con->prepare("SELECT ID FROM EMPRESA WHERE CNPJ = ?");
    $checkcnpf->bind_param("s", $cnpj);
    $checkcnpf->execute();
    $count = $checkcnpf->get_result()->num_rows;
    if ($count > 0) {
        die(header("HTTP/1.0 401 Conta registada com este CNPJ já existente"));
    }

    $checkEmail = $con->prepare("SELECT ID FROM EMPRESA WHERE EMAIL = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $count = $checkEmail->get_result()->num_rows;
    if ($count > 0) {
        die(header("HTTP/1.0 401 Conta registada com este e-mail já existente"));
    }

    if ($password != $word) {
        die(header("HTTP/1.0 401 Passwords diferentes"));
    }

    $password = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $con->prepare("INSERT INTO EMPRESA (`ID`,`USERNAME`, `TELEFONE`, `CNPJ`, `EMAIL`, `SENHA`) VALUES (0, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $telefone, $cnpj, $email, $password);
    $stmt->execute();

    $getUser = $con->prepare("SELECT ID FROM EMPRESA WHERE USERNAME = ?");
    $getUser->bind_param("s", $username);
    $getUser->execute();
    $user = $getUser->get_result()->fetch_assoc();

    $EMPRESA_ID = $user['ID'];

    $token = bin2hex(openssl_random_pseudo_bytes(20));
    $key = rand(100000000, 999999999);

    $stmt = $con->prepare("INSERT INTO USER (`ID`,`EMPRESA_ID`, `USERNAME`, `SENHA`, `FUNCAO`, `TOKEN`, `KEY`, `VALIDO`) VALUES (0, ?, ?, ?, 'ADM', ?, ?, '1')");
    $tempusername = $username;
    $stmt->bind_param("isssi", $EMPRESA_ID, $tempusername, $password, $token, $key);
    $stmt->execute();

    $getUser = $con->prepare("SELECT EMPRESA_ID, ID, TOKEN, `KEY`, FUNCAO FROM USER WHERE USERNAME = ? and `TOKEN` = ? and `KEY` = ?");
    $getUser->bind_param("sss", $username, $token, $key);
    $getUser->execute();
    $user = $getUser->get_result()->fetch_assoc();


    if ($stmt && $user) {
        setcookie("EMPRESA_ID", $user['EMPRESA_ID'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("ID", $user['ID'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("TOKEN", $user['TOKEN'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("KEY", $user['KEY'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("FUNCAO", $user['FUNCAO'], time() + (10 * 365 * 24 * 60 * 60));
        return true;
    } else {
        die(header("HTTP/1.0 401 Ocorreu um erro na base de dados"));
    }
} else {
    die(header("HTTP/1.0 401 Formulário de autenticação inválido"));
}

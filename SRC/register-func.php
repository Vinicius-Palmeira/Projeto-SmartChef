<?php
include("conexao.php");

if (isset($_POST["nome"]) && isset($_POST["senha"]) && isset($_POST["funcao"])&& isset($_POST["senha-repetida"] )) {

    $username = $_POST["nome"];
    $senha = $_POST["senha"];
    $funcao = $_POST["funcao"];
    $senharep = $_POST["senha-repetida"];
    $empid = $_COOKIE['EMPRESA_ID'];
    

    if ($username == "" || $senha == "" || $funcao == "" || $senharep == "") {
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


    $stmt = $con->prepare("INSERT INTO EMPRESA (ID, USERNAME, TELEFONE, CNPJ, EMAIL, SENHA, ATIVIDADE) VALUES (0, ?, ?, ?, ?, ?, ?);");
    $stmt->bind_param("ssssss", $username, $telefone, $cnpj, $email, $password, $atividade);
    if (!$stmt->execute()) {
        die("Erro ao inserir na tabela EMPRESA: " . $stmt->error);
    }
    

    $getUser = $con->prepare("SELECT ID FROM EMPRESA WHERE USERNAME = ?");
    $getUser->bind_param("s", $username);
    $getUser->execute();
    $user = $getUser->get_result()->fetch_assoc();

    $EMPRESA_ID = $user['ID'];

    $token = bin2hex(openssl_random_pseudo_bytes(20));
    $key = rand(100000000, 999999999);

    $stmt = $con->prepare("INSERT INTO FUNCIONARIOS (`ID`,`EMPRESA_ID`, `USERNAME`, `SENHA`, `FUNCAO`, `TOKEN`, `CHAVE`) VALUES (0, ?, ?, ?, 'ADM', ?, ?);");
    $stmt->bind_param("isssi", $EMPRESA_ID, $username, $password, $token, $key);
    if (!$stmt->execute()) {
        die("Erro ao inserir na tabela FUNCIONARIOS: " . $stmt->error);
    }

    $getUser = $con->prepare("SELECT EMPRESA_ID, ID, TOKEN, CHAVE, FUNCAO FROM FUNCIONARIOS WHERE USERNAME = ?");
    $getUser->bind_param("s", $username);
    $getUser->execute();
    $user = $getUser->get_result()->fetch_assoc();


    if ($stmt && $user) {
        setcookie("EMPRESA_ID", $user['EMPRESA_ID'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("ID", $user['ID'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("TOKEN", $user['TOKEN'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("CHAVE", $user['CHAVE'], time() + (10 * 365 * 24 * 60 * 60));
        setcookie("FUNCAO", $user['FUNCAO'], time() + (10 * 365 * 24 * 60 * 60));
        return true;
    } else {
        die(header("HTTP/1.0 401 Ocorreu um erro na base de dados"));
    }
} else {
    die(header("HTTP/1.0 401 Formulário de autenticação inválido"));
}

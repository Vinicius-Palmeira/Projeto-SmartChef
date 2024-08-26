<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Testes</title>
    <link rel="stylesheet" href="../../CSS/sweetalert2.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
</head>

<style>
    .fundo{
        background-color: white;
        background-image: url("https://images.adsttc.com/media/images/5bf3/5d1c/08a5/e509/1100/014e/large_jpg/FEATURE_IMAGE.jpg?1542675707");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        min-height: 100Vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .container{
        background-color: rgba(0,0,0,0.7);
        border-radius: 20px;
        margin: 0 auto;
        width: 60Vw;
        min-height: 500px;
        color: white;
    }

    .container div{
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    button{
        padding: 10px 15px;
        margin-bottom: 20px;
        width: 300px;
    }
</style>

<body>
    <div class="fundo p-5">
        <div class="container text-center p-3 py-5">
            <h1>CONEXAO COM SQL</h1>
            <div class="my-5">
            <?php
                $con = mysqli_connect('localhost', 'root', '1234');

                if (!$con) {
                    echo "Falha ao ligar a base de dados: " . mysqli_connect_error();
                }else{
                    echo "Sucesso na conexão";
                }
            ?>      
            </div>
            <a href="../../testes.html"><button>Voltar</button></a>
        </div>
    </div>
    <!-- SCRIPT's -->
    <script src="../../JS/jquery.js"></script>
    <script src="../../JS/sweetalert2.js"></script>
</body>
</html>

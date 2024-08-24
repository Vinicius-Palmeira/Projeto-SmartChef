<?php
include("check.php");

if ($_COOKIE['FUNCAO'] == 'ADM') {
    include("pages/UserADM.html");
} elseif ($_COOKIE['FUNCAO'] == 'COZINHA') {
    include("pages/UserCOZINHA.html");
} elseif ($_COOKIE['FUNCAO'] == 'GARCOM') {
    include("pages/UserGARCOM.html");
} elseif ($_COOKIE['FUNCAO'] == 'CAIXA') {
    include("pages/UserCAIXA.html");
} else {
    include("logout.php");
}

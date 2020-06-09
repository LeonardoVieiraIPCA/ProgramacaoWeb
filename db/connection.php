<?php
//tenta fazer a ligação a base de dados
if (new mysqli("localhost", "root", "", "dark_reddit") != true) {
    die("Erro: Não foi possível ligar-se à Base de Dados\n\n" . $th);
} else {
    $conn = new mysqli("localhost", "root", "", "dark_reddit");
    $conn->set_charset('utf8');
}

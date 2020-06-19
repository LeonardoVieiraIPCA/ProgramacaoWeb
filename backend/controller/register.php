<?php

require("../model/register.php");
require("../../db/connection.php");

global $conn;

if (isset($_POST["registUsername"]) && $_POST["registUsername"] != "" && isset($_POST["registPassword"]) && $_POST["registPassword"] != "") {
    if (preg_match('/[A-Za-z0-9]+/', $_POST['registUsername']) == 0) {
        exit("O utilizador não é válido");
    }
    if (strlen($_POST['registPassword']) > 20 || strlen($_POST['registPassword']) < 5) {
        exit("A palavra-passe deve ter entre 5 e 29 carateres");
    }

    $nomeUtilizador = $conn->real_escape_string($_POST["registUsername"]);

    $palavraPasse = password_hash($_POST['registPassword'], PASSWORD_DEFAULT);

    Register($nomeUtilizador, $palavraPasse);
}

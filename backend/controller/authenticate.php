<?php

session_start();
require("../model/authenticate.php");
require("../../db/connection.php");

//Verificamos se os dados do formulário de login foram enviados, a função isset() verifica se os dados existem
if (!isset($_POST['username'], $_POST['password'])) {
	//Não foi possível obter os dados que deviam ter sido enviados
	exit("Por favor, preencha os campos do nome de utilizador e a palavra-passe");
}

$nomeUtilizador = $conn->real_escape_string($_POST["username"]);

$password = $conn->real_escape_string($_POST["password"]);

Authenticate($nomeUtilizador, $password, $_POST["rememberUser"]);

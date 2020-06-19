<?php

//necessita a ligação para funcionar
require("../../db/connection.php");

function Register($nomeUtilizador, $palavraPasse)
{
	global $conn;
	$stmt = $conn->stmt_init();
	$stmt->prepare("INSERT INTO user (Username, Password) VALUES (?, ?)");

	$stmt->bind_param("ss", $nomeUtilizador, $palavraPasse);

	$stmt->execute();

	echo '<script type="text/JavaScript">  
     alert("Utilizador Inserido!"); 
     </script>'; 

	$stmt->close();
    $conn->close();
}

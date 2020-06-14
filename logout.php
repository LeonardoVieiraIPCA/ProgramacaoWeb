<?php
	session_start();
	session_destroy();
	//Redireciona o utilizador para a página login
	header('Location: index.html');
?>
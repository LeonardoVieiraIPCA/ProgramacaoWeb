<?php
	session_start();
	session_destroy();
	echo "<script> alert('You logout!'); </script>";
	//Redireciona o utilizador para a página inicial
	header('Location: ../../index.html');
?>
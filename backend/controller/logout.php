<?php
	session_start();
	session_destroy();
	echo "<script> alert('fhjfhjf'); </script>";
	//Redireciona o utilizador para a página login
	header('Location: ../../index.html');
?>
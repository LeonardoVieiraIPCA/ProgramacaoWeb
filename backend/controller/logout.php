<?php
	session_start();
	session_destroy();
	setcookie('cookieId', "", time() - 3600);
	setcookie('cookieLoggedin', "", time() - 3600);
	setcookie('cookieUser', "", time() - 3600);

	//Redireciona o utilizador para a página inicial
	header('Location: ../../index.html');

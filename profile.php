<?php
	//Inicia uma sessão
	session_start();
	//Caso o utilizador não estiver com o login realizado, é encaminhado para a página de login
	if (!isset($_SESSION['loggedin']))
	{
		header('Location: index.html');
		exit;
	}
	//Informações relativas à base de dados
	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'root';
	$DATABASE_PASS = '';
	$DATABASE_NAME = 'pw2';
	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if (mysqli_connect_errno())
	{
		exit('Erro na conexão com o SQL: ' . mysqli_connect_error());
	}
	//Como não temos as informações da palabra-passe e do email armazenadas nas sessões, temos de as ir buscar/procurar à base de dados
	$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
	//Neste caso, podemos usar o ID da conta para se obter as informações relativas à conta do utilizador
	$stmt->bind_param('i', $_SESSION['id']);
	$stmt->execute();
	$stmt->bind_result($password, $email);
	$stmt->fetch();
	$stmt->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
		<link href="css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Website Title</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Profile Page</h2>
			<div>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$email?></td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>
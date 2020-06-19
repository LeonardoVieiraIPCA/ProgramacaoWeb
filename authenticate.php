<?php

	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'root';
	$DATABASE_PASS = '';
	$DATABASE_NAME = 'dark_reddit';
	//Tenta estabelecer ligação à base de dados
	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if ( mysqli_connect_errno())
	{
		//Se houver algum erro/problema com a conexão, para o script e apresenta o erro
		exit("Ocorreu um erro na conexão com o SQL: " . mysqli_connect_error());
	}
	//Verificamos se os dados do formulário de login foram enviados, a função isset() verifica se os dados existem
	if (!isset($_POST['usernames'], $_POST['password']))
	{
		//Não foi possível obter os dados que deviam ter sido enviados
		exit("Por favor, preencha os campos do nome de utilizador e a palavra-passe");
	}

	$sql = "SELECT * FROM user";

	$result = $con->query($sql);

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			if($row["Password"] = $_POST["password"])
			{
				//O login foi realizado com sucesso
				echo "Palavra-passe correta";
				header('Location: index.html');
				//Cria uma sessão para que saiba que o utilizador está com o login efetuado com sucesso. Este código age como um cookie, lembrando o servidor dos dados
				session_regenerate_id();
				$_SESSION['loggedin'] = TRUE;
				$_SESSION['name'] = $_POST['usernames'];
				$_SESSION['id'] = $id;
				//Configuração dos cookies
				//Este cookie vai expirar 30 dias depois de ter sido criado
				//O "/" significa que este cookie vai estar disponível em todo o site
				setcookie("usernames", "", time()+(86400 * 30), "/");
				echo "Bem vindo" .$_SESSION["name"];
				exit();
			}
		}
	}
?>
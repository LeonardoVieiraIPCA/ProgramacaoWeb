<?php
	session_start();
	//Informações relativas à conexão com a base de dados
	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'root';
	$DATABASE_PASS = '';
	$DATABASE_NAME = 'pw2';
	//Tenta estabelecer ligação à base de dados
	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if ( mysqli_connect_errno())
	{
		//Se houver algum erro/problema com a conexão, para o script e apresenta o erro
		exit("Ocorreu um erro na conexão com o SQL: " . mysqli_connect_error());
	}
	//Verificamos se os dados do formulário de login foram enviados, a função isset() verifica se os dados existem
	if (!isset($_POST['username'], $_POST['password']))
	{
		//Não foi possível obter os dados que deviam ter sido enviados
		exit("Por favor, preencha os campos do nome de utilizador e a palavra-passe");
	}
	//Prepara a instrução SQL e impedirá a injeção de SQL
	if ($stmt = $con->prepare('SELECT id, password FROM user WHERE Username = username'))
	{
		//Parâmetros de ligação
		//$stmt->bind_param('s', $_POST['username']);
		$stmt->execute();
		//Armazena o resultado para que se possa verificar se a conta existe na base de dados
		$stmt->store_result();
		if ($stmt->num_rows > 0)
		{
			$stmt->bind_result($id, $password);
			$stmt->fetch();
			//A conta existe, vericação da senha
			if (password_verify($_POST['password'], $password))
			{
				//O login foi realizado com sucesso
				//Cria uma sessão para que saiba que o utilizador está com o login efetuado com sucesso. Este código age como um cookie, lembrando o servidor dos dados
				session_regenerate_id();
				$_SESSION['loggedin'] = TRUE;
				$_SESSION['name'] = $_POST['username'];
				$_SESSION['id'] = $id;
				//Configuração dos cookies
				//Este cookie vai expirar 30 dias depois de ter sido criado
				//O "/" significa que este cookie vai estar disponível em todo o site
				setcookie("username", "", time()+(86400 * 30), "/");
				header('Location: home.php');
				echo "Bem vindo" .$_SESSION["name"];
			}
			else
			{
				echo "Palavra-passe incorreta";
			}
		}
		else
		{
			echo "Nome de utilizador incorreto";
		}
		$stmt->close();
		//Remove todos os dados da sessão
		session_destroy();
	}
?>
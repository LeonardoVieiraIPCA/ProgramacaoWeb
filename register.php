<?php
	//Informações relativas à conexão com a base de dados
	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'root';
	$DATABASE_PASS = '';
	$DATABASE_NAME = 'pw2';
	//Tenta estabelecer ligação à base de dados
	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if (mysqli_connect_errno())
	{
		//Se houver algum erro/problema com a conexão, para o script e apresenta o erro
		exit("Conexão falhada na conexão com o SQL: " . mysqli_connect_error());
	}
	//Verificamos se os dados existem
	if (!isset($_POST['username'], $_POST['password'], $_POST['email']))
	{
		//Não foram obtidos os dados que deveriam ter sido enviados
		exit("Por favor, preencha todos os campos do formulário");
	}
	//Verifica se os campos de registo enviados não estão vazios
	if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']))
	{
		//Um ou mais valores estão vazios
		exit("Por favor, preencha todos os campos do formulário");
	}
	//Verifica se existe alguma conta com o mesmo nome de utilizador
	if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?'))
	{
		//Parâmetros de ligação e hash da senha usando a função PHP passoword_hash (usa-se "s" por que é uma string)
		$stmt->bind_param('s', $_POST['username']);
		$stmt->execute();
		$stmt->store_result();
		//Armazena o resultado para que se possa verificar se a conta existe na base de dados
		if ($stmt->num_rows > 0)
		{
			//Utilizador já existente
			echo "Utilizador já existente, por favor escolha outro nome de utilizador";
		}
		else
		{
			//Utilizador não existente, insere uma nova conta
			if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)'))
			{
				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				{
					exit("O email não é válido");
				}
				if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0)
				{
					exit("O utilizador não é válido");
				}
				if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5)
				{
					exit("A palavra-passe deve ter entre 5 e 20 carateres");
				}
				//Para não se expor a palavra-passe na base de dados, realiza-se o hash da senha e usa o password_verify quando o utilizador efetuar o login
				$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
				$stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
				$stmt->execute();
				echo "O registo foi realizado com sucesso. Já podes fazer login!";
			}
			else
			{
				//Houve um erro na instrução SQL, verifique se a tabela existe com os 3 campos
				echo "Ocorreu um erro";
			}
		}
		$stmt->close();
	}
	else
	{
		//Houve um erro na instrução SQL, verifique se a tabela existe com os 3 campos
		echo "Ocorreu um erro";
	}
	$con->close();
?>
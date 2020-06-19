<?php
	//Estabelecer a ligação à base de dados
	$mysqli = new mysqli("localhost", "root", "", "dark_reddit");
	$mysqli->set_charset('utf8');

	if($mysqli->connect_error != "")
	{
		//Se a ligação deu erro, o programa acaba aqui
		exit("Ocorreu um erro: ".$mysqli->connect_error);
	}

	//A ligação não deu erro
	echo "Conexão estabelecida";

	//Verifica se os campos foram todos preenchidos
	if(isset($_POST["username"]) && $_POST["username"]!="" && isset($_POST["passwords"]) && $_POST["passwords"]!="")
	{
		//Verifica se os dados estão de acordo com as condições
		if(preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0)
		{
			exit("O utilizador não é válido");
		}
		if(strlen($_POST['passwords']) > 20 || strlen($_POST['passwords']) < 5)
		{
			exit("A palavra-passe deve ter entre 5 e 20 carateres");
		}
		$nomeUtilizador = $mysqli->real_escape_string($_POST["username"]);
		$palavraPasse = password_hash($_POST['passwords'], PASSWORD_DEFAULT);

		//Insere os dados do utilizador na base de dados
		$sql = "INSERT INTO user (Username, Password) VALUES ('$nomeUtilizador', '$palavraPasse')";
		if($mysqli->query($sql))
		{
			echo "<br>Utilizador inserido com sucesso";
		}
		else
		{
			echo "<br>ERRO: Não foi possível inserir o utilizador na base de dados!";
		}
	}
?>
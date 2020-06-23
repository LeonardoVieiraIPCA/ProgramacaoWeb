<?php

//necessita a ligação para funcionar
require("../../db/connection.php");

function Authenticate($username, $pass, $rememberUser)
{
	global $conn;

	if ($stmt = $conn->prepare('SELECT Id, Password FROM user WHERE Username = ?')) {
		// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
		$stmt->bind_param('s', $username);
		$stmt->execute();
		// Store the result so we can check if the account exists in the database.
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $password);
			$stmt->fetch();
			// Account exists, now we verify the password.
			// Note: remember to use password_hash in your registration file to store the hashed passwords.
			if (password_verify($pass, $password)) {
				// Verification success! User has loggedin!
				// Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
				if (!isset($rememberUser)) {
					session_regenerate_id();
					$_SESSION['loggedin'] = TRUE;
					$_SESSION['username'] = $username;
					$_SESSION['id'] = $id;
				} else {
					setcookie('cookieId', $id, time() + 3600);
					setcookie('cookieLoggedin', TRUE, time() + 3600);
					setcookie('cookieUsername', $username, time() + 3600);
				}
			} else {
				echo 'Incorrect password!';
			}
		} else {
			echo 'Incorrect username!';
		}
		header('Location: ../../index.html');

		$stmt->close();
	}
}

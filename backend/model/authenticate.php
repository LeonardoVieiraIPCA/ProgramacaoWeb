<?php

//necessita a ligação para funcionar
require("../../db/connection.php");

function Authenticate($username, $pass)
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
				session_regenerate_id();
				$_SESSION['loggedin'] = TRUE;
				$_SESSION['name'] = $username;
				$_SESSION['id'] = $id;
				header('Location: ../../post.html');
				
			} else {
				echo 'Incorrect password!';
			}
		} else {
			echo 'Incorrect username!';
		}

		$stmt->close();
	}
}

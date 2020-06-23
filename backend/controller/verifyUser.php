<?php
require("../../db/connection.php");

session_start();
class VerifyUser
{
  public $login;
  public $userId;

  function __construct($login, $userId)
  {
    $this->login = $login;
    $this->userId = $userId;
  }
}

if (
  isset($_COOKIE['cookieId'], $_COOKIE['cookieLoggedin'], $_COOKIE['cookieUsername']) ||
  isset($_SESSION['loggedin'], $_SESSION['username'], $_SESSION['id'])
) {

  $verifyUser = new VerifyUser("", "");

  if (isset($_COOKIE['cookieId'], $_COOKIE['cookieLoggedin'], $_COOKIE['cookieUsername'])) {
    $verifyUser = new VerifyUser($_COOKIE['cookieLoggedin'], $_COOKIE['cookieId']);
  } else {
    $verifyUser = new VerifyUser($_SESSION['loggedin'], $_SESSION['username'], $_SESSION['id']);
  }

  echo json_encode($verifyUser, JSON_UNESCAPED_UNICODE);
} else {
  $verifyUser = new VerifyUser(false, "");

  echo json_encode($verifyUser, JSON_UNESCAPED_UNICODE);
}

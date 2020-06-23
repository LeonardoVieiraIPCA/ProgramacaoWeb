<?php
require("../../db/connection.php");
session_start();

class VerifyUser
{
  public $canDeletePost;
  public $commentsCanDelete = [];

  function __construct($canDeletePost, $commentsCanDelete)
  {
    $this->canDeletePost = $canDeletePost;
    $this->commentsCanDelete = $commentsCanDelete;
  }
}

class Comments
{
  public $commentsIds = [];
  public $commentsUsersIds = [];

  function __construct($commentsIds, $commentsUsersIds)
  {
    $this->commentsIds = $commentsIds;
    $this->commentsUsersIds = $commentsUsersIds;
  }
}

//echo  json_encode("", JSON_UNESCAPED_UNICODE);

if (isset($_COOKIE['cookieId']) || isset($_SESSION['id'])) {

  //"id" do "user" que fez o "post"
  $sql = "SELECT * FROM post WHERE Id=" . $_GET["verifyUserDelete"];
  $postUserId = $conn->query($sql);

  if ($postId = $postUserId->fetch_assoc()) {
    $postUserId = $postId["User_Id"];
  }

  $commentsUsersIds = [];
  $commentsIds = [];

  //"ids" dos "comments" do "post"
  $sql = "SELECT * FROM comments WHERE Post_Id=" . $_GET["verifyUserDelete"];
  $cIds = $conn->query($sql);

  if ($cIds->num_rows > 0) {
    while ($commentId = $cIds->fetch_assoc()) {
      array_push($commentsIds, $commentId["Id"]);
      array_push($commentsUsersIds, $commentId["User_Id"]);
    }
  }

  $comments = new Comments($commentsIds, $commentsUsersIds);

  if (isset($_COOKIE['cookieId'])) {
    $id = $_COOKIE['cookieId'];
  } else if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
  }

  $j = 0;

  $commetsIdsOfCurrentUserId = [];
  for ($i = 0; $i < count($comments->commentsIds); $i++) {
    if ($comments->commentsUsersIds[$i] == $id) {
      $commetsIdsOfCurrentUserId[0] = $comments->commentsIds[$i];
      $j++;
    }
  }


  if ($postUserId == $id) {
    $verifyUser = new VerifyUser("true", $commetsIdsOfCurrentUserId);
  } else {
    $verifyUser = new VerifyUser("", $commetsIdsOfCurrentUserId);
  }

  echo json_encode($verifyUser, JSON_UNESCAPED_UNICODE);
} else {
  $verifyUser = new VerifyUser("", []);
  echo json_encode($verifyUser, JSON_UNESCAPED_UNICODE);
}

<?php
require("../model/post.php");

//verifica se recebeu um pedido POST com o nome insert
if (isset($_GET["refresh"]) && $_GET["refresh"] != "") {

    //passa esse valor para a variável objRefreshJSON e descodifica-a
    $objRefreshJSON = $_GET["refresh"];
    $posts = json_decode($objRefreshJSON);

    GetPosts($posts);
}

if (isset($_POST["insert"]) && $_POST["insert"] != "" && (isset($_SESSION['loggedin']) || isset($_COOKIE["cookieLoggedin"]))) {

    //passa esse valor para a variável objInsertJSON e descodifica-a
    $objInsertJSON = $_POST["insert"];
    $post = json_decode($objInsertJSON);

    if (isset($_COOKIE['cookieId'])) {
        $userId = $_COOKIE['cookieId'];
    } else if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];
    }

    CreatePost($post, $userId);
}

if (isset($_POST["delete"]) && $_POST["delete"] != "" && (isset($_SESSION['loggedin']) || isset($_COOKIE["cookieLoggedin"]))) {

    //passa esse valor para a variável postIdDeleteJSON e descodifica-a
    $postIdDeleteJSON = $_POST["delete"];
    $postId = json_decode($postIdDeleteJSON);

    if (isset($_COOKIE['cookieId'])) {
        $userId = $_COOKIE['cookieId'];
    } else if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];
    }

    DeletePost($postId, $userId);
}

if (isset($_POST["deleteComment"]) && $_POST["deleteComment"] != "" && (isset($_SESSION['loggedin']) || isset($_COOKIE["cookieLoggedin"]))) {

    //passa esse valor para a variável postIdDeleteJSON e descodifica-a
    $commentIdDeleteJSON = $_POST["deleteComment"];
    $commentId = json_decode($commentIdDeleteJSON);

    if (isset($_COOKIE['cookieId'])) {
        $userId = $_COOKIE['cookieId'];
    } else if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];
    }

    DeleteComment($commentId, $userId);
}

if (isset($_GET["getPost"]) && $_GET["getPost"] != "") {

    //passa esse valor para a variável postIdJSON e descodifica-a
    $postIdJSON = $_GET["getPost"];
    $postId = json_decode($postIdJSON);

    GetPost($postId);
}

if (isset($_POST["votesChange"]) && $_POST["votesChange"] != "" && (isset($_SESSION['loggedin']) || isset($_COOKIE["cookieLoggedin"]))) {

    //passa esse valor para a variável objJSON e descodifica-a
    $objJSON = $_POST["votesChange"];
    $obj = json_decode($objJSON);

    if (isset($_COOKIE['cookieId'])) {
        $userId = $_COOKIE['cookieId'];
    } else if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];
    }

    VotesChange($obj, $userId);
}

if (isset($_GET["search"]) && $_GET["search"] != "") {

    //passa esse valor para a variável objRefreshJSON e descodifica-a
    $searchJSON = $_GET["search"];
    $searchPost = json_decode($searchJSON);

    SearchPost($searchPost);
}

if (isset($_POST["addComment"]) && $_POST["addComment"] != "" && (isset($_SESSION['loggedin']) || isset($_COOKIE["cookieLoggedin"]))) {

    //passa esse valor para a variável objInsertJSON e descodifica-a
    $commentJSON = $_POST["addComment"];
    $comment = json_decode($commentJSON);

    if (isset($_COOKIE['cookieId'])) {
        $userId = $_COOKIE['cookieId'];
    } else if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];
    }

    AddComment($comment, $userId);
}

if (isset($_GET["loadComments"]) && $_GET["loadComments"] != "") {

    //passa esse valor para a variável objRefreshJSON e descodifica-a
    $postJSON = $_GET["loadComments"];
    $post = json_decode($postJSON);

    LoadComments($post);
}

<?php
require("../model/post.php");

//verifica se recebeu um pedido POST com o nome insert
if (isset($_GET["refresh"]) && $_GET["refresh"] != "") {

    //passa esse valor para a variável objRefreshJSON e descodifica-a
    $objRefreshJSON = $_GET["refresh"];
    $posts = json_decode($objRefreshJSON);

    GetPosts($posts);
}

if (isset($_POST["insert"]) && $_POST["insert"] != "" && isset($_SESSION['loggedin'])) {

    //passa esse valor para a variável objInsertJSON e descodifica-a
    $objInsertJSON = $_POST["insert"];
    $post = json_decode($objInsertJSON);

    CreatePost($post, $_SESSION['id']);
}

if (isset($_POST["delete"]) && $_POST["delete"] != "" && isset($_SESSION['loggedin'])) {

    //passa esse valor para a variável postIdDeleteJSON e descodifica-a
    $postIdDeleteJSON = $_POST["delete"];
    $postId = json_decode($postIdDeleteJSON);

    DeletePost($postId, $_SESSION['id']);
}

if (isset($_GET["getPost"]) && $_GET["getPost"] != "") {

    //passa esse valor para a variável postIdJSON e descodifica-a
    $postIdJSON = $_GET["getPost"];
    $postId = json_decode($postIdJSON);
    
    GetPost($postId);
}

if (isset($_POST["votesChange"]) && $_POST["votesChange"] != "") {

    //passa esse valor para a variável postJSON e descodifica-a
    $postJSON = $_POST["votesChange"];
    $post = json_decode($postJSON);
    
    VotesChange($post, $_SESSION['id']);
}

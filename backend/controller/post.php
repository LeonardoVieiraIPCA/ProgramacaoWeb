<?php
require("../model/post.php");

//verifica se recebeu um pedido POST com o nome insert
if (isset($_GET["refresh"]) && $_GET["refresh"] != "") {

    //passa esse valor para a variável objRefreshJSON e descodifica-a
    $objRefreshJSON = $_GET["refresh"];
    $posts = json_decode($objRefreshJSON);

    GetPosts($posts);
}

if (isset($_POST["insert"]) && $_POST["insert"] != "") {

    //passa esse valor para a variável objInsertJSON e descodifica-a
    $objInsertJSON = $_POST["insert"];
    $post = json_decode($objInsertJSON);

    CreatePost($post, 2);
}

if (isset($_POST["delete"]) && $_POST["delete"] != "") {

    //passa esse valor para a variável postIdDeleteJSON e descodifica-a
    $postIdDeleteJSON = $_POST["delete"];
    $postId = json_decode($postIdDeleteJSON);

    DeletePost($postId, 2);
}

if (isset($_GET["getPost"]) && $_GET["getPost"] != "") {

    //passa esse valor para a variável postIdJSON e descodifica-a
    $postIdJSON = $_GET["getPost"];
    $postId = json_decode($postIdJSON);
    
    GetPost($postId, 2);
}

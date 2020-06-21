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

    //passa esse valor para a variável objJSON e descodifica-a
    $objJSON = $_POST["votesChange"];
    $obj = json_decode($objJSON);
    
    VotesChange($obj, $_SESSION['id']);
}

if (isset($_GET["search"]) && $_GET["search"] != "") {

    //passa esse valor para a variável objRefreshJSON e descodifica-a
    $searchJSON = $_GET["search"];
    $searchPost = json_decode($searchJSON);

    SearchPost($searchPost);
}

if (isset($_POST["addComment"]) && $_POST["addComment"] != "" && isset($_SESSION['loggedin'])) {

    //passa esse valor para a variável objInsertJSON e descodifica-a
    $commentJSON = $_POST["addComment"];
    $comment = json_decode($commentJSON);

    AddComment($comment, $_SESSION['id']);
}

if (isset($_GET["loadComments"]) && $_GET["loadComments"] != "") {

    //passa esse valor para a variável objRefreshJSON e descodifica-a
    $postJSON = $_GET["loadComments"];
    $post = json_decode($postJSON);

    LoadComments($post);
}

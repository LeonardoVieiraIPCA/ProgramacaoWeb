<?php
require("../model/post.php");

//verifica se recebeu um pedido POST com o nome insert
if (isset($_GET["refresh"]) && $_GET["refresh"] != "") {

    //passa esse valor para a variável objInsertJSON e descodifica-a
    $objInsertJSON = $_GET["refresh"];
    $posts = json_decode($objInsertJSON);

    GetPosts($posts);
}

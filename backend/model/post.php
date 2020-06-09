<?php

class Post
{
    public $id;
    public $username;
    public $Vote;
    public $title;

    function __construct($id, $username, $Vote, $title)
    {
        $this->id = $id;
        $this->username = $username;
        $this->Vote = $Vote;
        $this->title = $title;
    }
}

class Vote
{
    public $upVote;
    public $downVote;

    function __construct($upVote, $downVote)
    {
        $this->upVote = $upVote;
        $this->downVote = $downVote;
    }
}

//necessita a ligação para funcionar
require("../../db/connection.php");

function GetPosts($posts)
{
    //variável para aceder a base de dados
    global $conn;

    //coloca a query numa variável
    $sql = "SELECT Id, User_Id, Votes_Id, Title FROM post";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        //guarda todos os posts no array
        while ($row = $result->fetch_assoc()) {

            //vai buscar o "user" associado ao "post"
            $sql = "SELECT Username FROM user WHERE id=" . $row["User_Id"];
            $username = $conn->query($sql);

            if ($rrr = $username->fetch_assoc()) {
                $username = $rrr["Username"];
            }

            //vai buscar o "vote" associado ao "post"
            $sql = "SELECT Up, Down FROM votes WHERE id=" . $row["Votes_Id"];
            $vote = $conn->query($sql);

            if ($rowVote = $vote->fetch_assoc()) {
                $vote = new Vote($rowVote["Up"], $rowVote["Down"]);
            }

            //guardar o valor que foi buscar no objeto
            $post = new Post($row["Id"], $username, $vote, $row["Title"]);
            array_push($posts, $post);
        }

        $result = json_encode($posts, JSON_UNESCAPED_UNICODE);
        echo $result;
    }
}

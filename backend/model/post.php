<?php

//necessita a ligação para funcionar
require("../../db/connection.php");

//Criação de uma sessão
session_start();
class Post
{
    public $id;
    public $username;
    public $title;
    public $description;
    public $Vote;

    function __construct($id, $username, $title, $description, $Vote)
    {
        $this->id = $id;
        $this->username = $username;
        $this->title = $title;
        $this->description = $description;
        $this->Vote = $Vote;
    }
}

class Comment
{
    public $id;
    public $username;
    public $text;
    public $Vote;

    function __construct($id, $username, $text, $Vote)
    {
        $this->id = $id;
        $this->username = $username;
        $this->text = $text;
        $this->Vote = $Vote;
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

function GetPosts($posts)
{
    //variável para aceder a base de dados
    global $conn;

    //coloca a query numa variável
    $sql = "SELECT * FROM post";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        //guarda todos os posts no array
        while ($row = $result->fetch_assoc()) {

            //vai buscar o "user" associado ao "post"
            $sql = "SELECT Username FROM user WHERE id=" . $row["User_Id"];
            $username = $conn->query($sql);

            if ($usernameRow = $username->fetch_assoc()) {
                $username = $usernameRow["Username"];
            }

            //vai buscar o "vote" associado ao "post"
            $sql = "SELECT Up, Down FROM votes WHERE id=" . $row["Votes_Id"];
            $vote = $conn->query($sql);

            if ($rowVote = $vote->fetch_assoc()) {
                $vote = new Vote($rowVote["Up"], $rowVote["Down"]);
            }

            //guardar o valor que foi buscar no objeto
            $post = new Post($row["Id"], $username, $row["Title"], $row["Description"], $vote);
            array_push($posts, $post);
        }

        //colocar por ordem Desc 
        usort($posts, function ($first, $second) {
            return $first->Vote->upVote < $second->Vote->upVote;
        });

        $result = json_encode($posts, JSON_UNESCAPED_UNICODE);
        echo $result;
    }
}

function CreatePost($post, $User_Id)
{
    //variável para aceder a base de dados
    global $conn;

    $stmt = $conn->stmt_init();

    //cria os "votes" para o "Post"
    $stmt->prepare("INSERT INTO votes (User_Id, Up, Down, Modifying) VALUES (?, ?, ?, ?)");
    $zero = 0; //é necessário fazer isto pois o "bind_param" não aceita valores se não forem variáveis
    $one = 1;
    $stmt->bind_param("iiii", $User_Id, $zero, $zero, $one);

    $stmt->execute();

    //vai buscar o "Id" do "vote" que acabou de ser criado
    $sql = "SELECT Id FROM votes WHERE User_Id=" . $User_Id . " AND Modifying=1";
    $vote_id = $conn->query($sql);

    if ($id = $vote_id->fetch_assoc()) {
        $vote_id = $id["Id"];
    }

    $title = $post->title;
    $description = $post->description;

    //coloca a query numa variável
    if ($stmt->prepare("INSERT INTO post (User_Id, Title, Description, Votes_Id) VALUES (?, ?, ?, ?)")) {
        $stmt->bind_param("issi", $User_Id, $title, $description, $vote_id);
        $stmt->execute();

        $idPost = $conn->insert_id;

        $stmt->prepare("UPDATE votes SET Modifying=0 WHERE Id=" . $vote_id);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    echo $idPost;
}

function DeletePost($postId, $userId)
{
    //variável para aceder a base de dados
    global $conn;
    $stmt = $conn->stmt_init();

    $sql = "SELECT * FROM post WHERE Id=" . $postId;
    $user_Id = $conn->query($sql);

    if ($id = $user_Id->fetch_assoc()) {
        $user_Id = $id["User_Id"];
    }

    //cria os "votes" para o "Post"
    if ($userId == $user_Id) {
        $stmt->prepare("DELETE FROM post WHERE Id=" . $postId);
        $stmt->execute();

        $stmt->close();
        $conn->close();

        echo "Post Eliminado com exito!";
    } else {
        echo "Post não foi Eliminado!";
    }
}

function DeleteComment($commentId, $userId)
{
    //variável para aceder a base de dados
    global $conn;
    $stmt = $conn->stmt_init();

    $sql = "SELECT * FROM comments WHERE Id=" . $commentId;
    $user_Id = $conn->query($sql);

    if ($id = $user_Id->fetch_assoc()) {
        $user_Id = $id["User_Id"];
    }

    //cria os "votes" para o "Post"
    if ($userId == $user_Id) {
        $stmt->prepare("DELETE FROM comments WHERE Id=" . $commentId);
        $stmt->execute();

        $stmt->close();
        $conn->close();

        echo "Post Eliminado com exito!";
    } else {
        echo "Post não foi Eliminado!";
    }
}


function GetPost($postId)
{
    //variável para aceder a base de dados
    global $conn;

    //coloca a query numa variável
    $sql = "SELECT * FROM post WHERE Id=" . $postId;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        //guarda todos os posts no array
        while ($row = $result->fetch_assoc()) {

            //vai buscar o "user" associado ao "post"
            $sql = "SELECT Username FROM user WHERE id=" . $row["User_Id"];
            $username = $conn->query($sql);

            if ($usernameRow = $username->fetch_assoc()) {
                $username = $usernameRow["Username"];
            }

            //vai buscar o "vote" associado ao "post"
            $sql = "SELECT Up, Down FROM votes WHERE id=" . $row["Votes_Id"];
            $vote = $conn->query($sql);

            if ($rowVote = $vote->fetch_assoc()) {
                $vote = new Vote($rowVote["Up"], $rowVote["Down"]);
            }

            //guardar o valor que foi buscar no objeto
            $post = new Post($row["Id"], $username, $row["Title"], $row["Description"], $vote,);
        }

        $result = json_encode($post, JSON_UNESCAPED_UNICODE);
        echo $result;
    }
}

function VotesChange($objVote, $User_Id)
{
    //variável para aceder a base de dados
    global $conn;

    //vai buscar o "Id" do "vote" que acabou de ser criado
    if ($objVote->type != "comments") {

        $sql = "SELECT * FROM post WHERE Id=" . $objVote->id;
        $post = $conn->query($sql);

        if ($postInfo = $post->fetch_assoc()) {
            $votes_Id = $postInfo["Votes_Id"];
        }
    } else {
        $sql = "SELECT * FROM comments WHERE Id=" . $objVote->id;
        $post = $conn->query($sql);

        if ($postInfo = $post->fetch_assoc()) {
            $votes_Id = $postInfo["Votes_Id"];
        }
    }

    $sql = "SELECT * FROM votes WHERE Id=" . $votes_Id;
    $vote = $conn->query($sql);

    if ($v = $vote->fetch_assoc()) {
        $upVote = $v["Up"];
        $downVote = $v["Down"];
    }

    if ($objVote->type != "comments") {
        $sql = "SELECT * FROM uservote WHERE User_Id=" . $User_Id . " AND Vote_Id=" . $votes_Id;
        $uservote = $conn->query($sql);
    } else {
        $sql = "SELECT * FROM uservote WHERE Vote_Id=" . $votes_Id;
        $uservote = $conn->query($sql);
    }

    //vai buscar a base de dados o valor do "vote"
    // VoteType = 0 - sem "vote"
    // VoteType = 1 - "downVote"
    // VoteType = 2 - "upVote"
    if ($vt = $uservote->fetch_assoc()) {
        $voteType = $vt["VoteType"];
    }

    $stmt = $conn->stmt_init();

    if ($uservote->num_rows < 1) {
        $stmt->prepare("INSERT INTO uservote (User_Id, Vote_Id, VoteType) VALUES (?, ?, ?)");
        $zero = 0;
        $stmt->bind_param("iii", $User_Id, $votes_Id, $zero);
        $stmt->execute();
        $voteType = 0;
    }

    //se o utilizador carregou em "upVote"
    if ($objVote->voteType == "upVote") {

        //se já tinha dado "upVote" então vai tirar o "upVote"
        if ($voteType == 2) {
            $upVote = $upVote - 1;
            $voteType = 0;
        } else if ($voteType == 1) {
            $downVote = $downVote - 1;
            $upVote = $upVote + 1;
            $voteType = 2;
        } else { //casso contrário irá adiciona-lo
            $upVote = $upVote + 1;
            $voteType = 2;
        }
    } else {

        //se já tinha dado "downVote" então vai tirar o "downVote"
        if ($voteType == 1) {
            $downVote = $downVote - 1;
            $voteType = 0;
        } else if ($voteType == 2) {
            $upVote = $upVote - 1;
            $downVote = $downVote + 1;
            $voteType = 1;
        } else { //casso contrário irá adiciona-lo
            $downVote = $downVote + 1;
            $voteType = 1;
        }
    }

    //echo "fdghfjhfgjh: " .$voteType;

    $stmt->prepare("UPDATE votes SET up=" . $upVote . ", down=" . $downVote . " WHERE Id=" . $votes_Id);
    $stmt->execute();

    $stmt->prepare("UPDATE uservote SET VoteType=" . $voteType . " WHERE User_Id=" . $User_Id . " AND Vote_Id=" . $votes_Id);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $votes = new Vote($upVote, $downVote);

    echo json_encode($votes, JSON_UNESCAPED_UNICODE);
}

function SearchPost($searchPost)
{
    //variável para aceder a base de dados
    global $conn;

    //coloca a query numa variável
    $sql = "SELECT * FROM post WHERE Title LIKE '%" . $searchPost->titleSearch . "%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        //guarda todos os posts no array
        while ($row = $result->fetch_assoc()) {

            //vai buscar o "user" associado ao "post"
            $sql = "SELECT Username FROM user WHERE id=" . $row["User_Id"];
            $username = $conn->query($sql);

            if ($usernameRow = $username->fetch_assoc()) {
                $username = $usernameRow["Username"];
            }

            //vai buscar o "vote" associado ao "post"
            $sql = "SELECT Up, Down FROM votes WHERE id=" . $row["Votes_Id"];
            $vote = $conn->query($sql);

            if ($rowVote = $vote->fetch_assoc()) {
                $vote = new Vote($rowVote["Up"], $rowVote["Down"]);
            }

            //guardar o valor que foi buscar no objeto
            $post = new Post($row["Id"], $username, $row["Title"], $row["Description"], $vote);
            array_push($searchPost->result, $post);
        }

        //colocar por ordem Desc 
        usort($searchPost->result, function ($first, $second) {
            return $first->Vote->upVote < $second->Vote->upVote;
        });

        $result = json_encode($searchPost->result, JSON_UNESCAPED_UNICODE);
        echo $result;
    }
}

function AddComment($comment, $User_Id)
{
    //variável para aceder a base de dados
    global $conn;

    $stmt = $conn->stmt_init();

    //cria os "votes" para o "Post"
    $stmt->prepare("INSERT INTO votes (User_Id, Up, Down, Modifying) VALUES (?, ?, ?, ?)");
    $zero = 0; //é necessário fazer isto pois o "bind_param" não aceita valores se não forem variáveis
    $one = 1;
    $stmt->bind_param("iiii", $User_Id, $zero, $zero, $one);

    $stmt->execute();

    $vote_id = $conn->insert_id;

    //coloca a query numa variável
    if ($stmt->prepare("INSERT INTO comments (User_Id, Votes_Id, Post_Id, Text) VALUES (?, ?, ?, ?)")) {
        $stmt->bind_param("iiis", $User_Id, $vote_id, $comment->postId, $comment->commentText);
        $stmt->execute();

        $stmt->prepare("INSERT INTO uservote (User_Id, Vote_Id, VoteType) VALUES (?, ?, ?)");
        $zero = 0;
        $stmt->bind_param("iii", $User_Id, $vote_id, $zero);
        $stmt->execute();

        $stmt->prepare("UPDATE votes SET Modifying=0 WHERE Id=" . $vote_id);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
}

function LoadComments($post)
{
    //variável para aceder a base de dados
    global $conn;

    //coloca a query numa variável
    $sql = "SELECT * FROM comments WHERE Post_Id=" . $post->id;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        //guarda todos os posts no array
        while ($row = $result->fetch_assoc()) {

            //vai buscar o "user" associado ao "post"
            $sql = "SELECT Username FROM user WHERE id=" . $row["User_Id"];
            $username = $conn->query($sql);

            if ($usernameRow = $username->fetch_assoc()) {
                $username = $usernameRow["Username"];
            }

            //vai buscar o "vote" associado ao "post"
            $sql = "SELECT Up, Down FROM votes WHERE id=" . $row["Votes_Id"];
            $vote = $conn->query($sql);

            if ($rowVote = $vote->fetch_assoc()) {
                $vote = new Vote($rowVote["Up"], $rowVote["Down"]);
            }

            //guardar o valor que foi buscar no objeto
            $comment = new Comment($row["Id"], $username, $row["Text"], $vote);
            array_push($post->comments, $comment);
        }

        //colocar por ordem Desc 
        usort($post->comments, function ($first, $second) {
            return $first->Vote->upVote < $second->Vote->upVote;
        });

        $result = json_encode($post->comments, JSON_UNESCAPED_UNICODE);
        echo $result;
    }
}

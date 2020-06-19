<?php

//necessita a ligação para funcionar
require("../../db/connection.php");

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
            $post = new Post($row["Id"], $username, $row["Title"], "", $vote);
            array_push($posts, $post);
        }

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
    }

    $stmt->prepare("UPDATE votes SET Modifying=0 WHERE Id=" . $vote_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    echo "Post criado com exito!";
}

function DeletePost($id, $User_Id)
{
    //variável para aceder a base de dados
    global $conn;
    $stmt = $conn->stmt_init();

    //cria os "votes" para o "Post"
    $stmt->prepare("DELETE FROM post WHERE id=" . $id);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    echo "Post Eliminado com exito!";
}

function GetPost($postId, $User_Id)
{
    //variável para aceder a base de dados
    global $conn;

    //coloca a query numa variável
    $sql = "SELECT * FROM post WHERE Id=" . $postId;
    $result = $conn->query($sql);

    //cria um objeto vazio
    $post = new stdClass();

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
            $post = new Post($row["Id"], $username, $row["Title"], $row["Description"], $vote, );
        }

        $result = json_encode($post, JSON_UNESCAPED_UNICODE);
        echo $result;
    }
}
?>
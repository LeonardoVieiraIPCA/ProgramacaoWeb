$(document).ready(function () {

    //da refresh aos posts
    RefreshFeed();

    //impede que o form apareça
    //Dialog(false);

    function RefreshFeed() {
        $.ajax({

            //faz o GET com um array vazio para o ficheiro "post.php"
            type: "GET",
            url: "./backend/controller/post.php",

            //o ficheiro "post.php" vai procurar por um GET request com o nome de "refresh"
            data: { refresh: JSON.stringify([]) },
            cache: false,

            dataType: "json",
            //o valor recebido vai ser transformado no tipo JSON
            success: function (data) {
                let posts = data;

                //limpa os posts antigos
                $('#posts > div').remove();

                //atualiza os posts
                posts.forEach(post => {
                    $("#posts").append($('<div class="row">'
                        + '<form id=' + post.id + '>'
                        + '<h2 class="postTitle">' + post.title + '</h2>'
                        + '<p>Username: ' + post.username + '</p>'
                        + '<p>' + post.description + '</p>'
                        + '<div class="voteClick">'
                        + '<a href="#"><img class="upVote" src="./img/up-arrow.png"></a> <label>' + post.Vote.upVote + '</label><br>'
                        + '<a href="#"><img class="downVote" src="./img/down-arrow.png"></a> <label>' + post.Vote.downVote + '</label>'
                        + '</div>'
                        + '</form>'
                        + '</div>'
                    ));
                });

                CreatePostHTML();
                EnterPost();
                Votes();
                Search();
                VerifyUser();
            },
            error: function (data) {
                //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                alert(data.responseText);
            }
        })


    }
    function VerifyUser() {
        $.ajax({

            //faz o GET com um array vazio para o ficheiro "post.php"
            type: "GET",
            url: "./backend/controller/verifyUser.php",

            //o ficheiro "post.php" vai procurar por um GET request com o nome de "refresh"
            data: { verifyUser: JSON.stringify() },
            cache: false,

            dataType: "json",
            //o valor recebido vai ser transformado no tipo JSON
            success: function (data) {
                let verifyUser = data;
                if (verifyUser.login == 1) {
                    $("#login").css("visibility", "hidden");
                    $("#logout").css("visibility", "visible");
                } else {
                    $("#login").css("visibility", "visible");
                    $("#logout").css("visibility", "hidden");
                }
            },
            error: function (data) {
                //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                alert(data.responseText);
            }
        })
    }

    function Post(postId) {
        $.ajax({

            //faz o GET com um array vazio para o ficheiro "post.php"
            type: "GET",
            url: "./backend/controller/post.php",

            //o ficheiro "post.php" vai procurar por um GET request com o nome de "refresh"
            data: { getPost: JSON.stringify(postId) },
            cache: false,

            dataType: 'json',
            //o valor recebido vai ser transformado no tipo JSON
            success: function (data) {
                let post = data;

                $('#posts > div').remove();

                $("#posts").append($('<div class="row" id="post">'
                    + '<form id=' + post.id + '>'
                    + '<h1 class="postTitle">' + post.title + '</h1>'
                    + '<span>' + post.username + '</span>'
                    + '<br>'
                    + '<a class="delete">Delete</a><br>'
                    + '<div class="voteClick">'
                    + '<a href="#"><img class="upVote" src="./img/up-arrow.png"></a> <label>' + post.Vote.upVote + '</label><br>'
                    + '<a href="#"><img class="downVote" src="./img/down-arrow.png"></a> <label>' + post.Vote.downVote + '</label>'
                    + '</div>'
                    + '<p>' + post.description + '</p>'
                    + '</form>'
                    + '</div>'
                    + '<a href="index.html"><button>Go Back</button></a>'


                    + '<div class="row">'
                    + '<div class="col-md">'
                    + '<h1>Comment:</h1>'
                    + '<form id="addComment" method="POST">'
                    + '<textarea required type="text" name="text" id="commentText"></textarea>'
                    + '<br>'
                    + '<button class="btn btn-secondary" id="btn-insert">Submeter</button>'
                    + '</form>'
                    + '</div>'
                    + '</div>'
                    + '<div class="comments"></div>'
                ));

                AddComment(postId);
                LoadComments(postId);
                Delete(postId);
                Votes();

            },
            error: function (data) {
                //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                RefreshFeed(data);
            }
        })
    }

    function EnterPost() {
        $('.postTitle').on('click', function (e) {

            let postId = $(this).parents()[0].id;

            if (postId != "") {
                Post(postId);
            }
        })
    }

    function InsertPost() {
        $("#insert").on("submit", function (e) {
            e.preventDefault();

            //vai buscar o valor de cada input
            let title = $("#title")[0].value;
            let description = $("#description")[0].value;

            //coloca esses valores num objeto
            let insertObj = {
                "title": title,
                "description": description,
            };

            //trasnforma o valor javascript em JSON
            let insertObjJSON = JSON.stringify(insertObj);

            $.ajax({
                //faz o POST do objeto JSON para o ficheiro "post.php"
                type: "POST",
                url: "./backend/controller/post.php",

                //o ficheiro "post.php" vai procurar por um POST request com o nome de "insert"
                data: { insert: insertObjJSON },
                cache: false,

                success: function (postId) {

                    //caso consiga inserir com sucesso irá avisar o ultizador
                    if (postId != "") {
                        Post(postId);

                        alert("Post criado com exito!");

                        //vai limpar o form
                        $("#title")[0].value = "";
                        $("#description")[0].value = "";
                    }
                },
                error: function (data) {
                    //em caso de erro irá avisar um possível motivo pelo qual não foi possível inserir a pessoa
                    alert("Erro: " + data.responseText);
                }
            })
        })
    }

    function Delete(postId) {

        $.ajax({

            //faz o GET com um array vazio para o ficheiro "post.php"
            type: "GET",
            url: "./backend/controller/verifyUserDelete.php",

            //o ficheiro "post.php" vai procurar por um GET request com o nome de "refresh"
            data: { verifyUserDelete: JSON.stringify(postId) },
            cache: false,

            dataType: "json",
            //o valor recebido vai ser transformado no tipo JSON
            success: function (data) {
                if (data.canDeletePost == true) {
                    $(".delete").css("visibility", "visible");
                } else {
                    $(".delete").css("visibility", "hidden");
                }

                for (let i = 0; i < data.commentsCanDelete.length; i++) {
                    $(".comments form#" + data.commentsCanDelete[i] + " a.delete").css("visibility", "visible");
                }

            },
            error: function (data) {
                //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                alert(data.responseText);
                $(".delete").css("visibility", "hidden");
            }
        })

        //ao carregar no botão delete da tabela vai eliminar a respetiva pessoa 
        $('#post').on('click', '.delete', function (e) {
            e.preventDefault();

            //vai buscar o "id" do respetivo utilziador
            let id = $(this).parents()[0].id;

            $.ajax({
                //faz o POST do "id" para o ficheiro "post.php"
                type: "POST",
                url: "./backend/controller/post.php",

                //o ficheiro "post.php" vai procurar por um POST request com o nome de delete
                data: { delete: JSON.stringify(id) },
                cache: false,

                success: function (msg) {

                    alert("Mensagem: " + msg);

                    //e irá recarregar a tabela
                    RefreshFeed();
                },
                error: function (data) {
                    //em caso de erro irá avisar um possível motivo pelo qual não foi possível eliminar a pessoa
                    alert("Erro: " + data.responseText);
                }
            })
        });

        $('.comments').on('click', '.delete', function (e) {
            e.preventDefault();

            //vai buscar o "id" do respetivo utilziador
            let id = $(this).parents()[0].id;

            $.ajax({
                //faz o POST do "id" para o ficheiro "post.php"
                type: "POST",
                url: "./backend/controller/post.php",

                //o ficheiro "post.php" vai procurar por um POST request com o nome de delete
                data: { deleteComment: JSON.stringify(id) },
                cache: false,

                success: function (msg) {

                    alert("Mensagem: " + msg);

                    //e irá recarregar os comments
                    LoadComments(postId);
                },
                error: function (data) {
                    //em caso de erro irá avisar um possível motivo pelo qual não foi possível eliminar a pessoa
                    alert("Erro: " + data.responseText);
                }
            })
        });
    }

    //Sistema de "Upvote" e "DownVote"
    function Votes() {
        $(".voteClick").click(function (e) {
            e.preventDefault();

            //vai buscar o "id" do respetivo "post"
            let id = $(this).parents()[0].id;
            let typeComment = $(this).parents()[2].className;

            //valor tipo de botão que foi carregado, se foi o "upVote" ou o "downVote" 
            let voteType = e.target.className;

            /*
            valores dos 2 botões de "votes"
             vote[0] - botão upVote 
             vote[1] - botão downVote
             vote[0].attributes[0].value - valor da "class" do "upVote"
             vote[0].attributes[1].value - valor do "src" do "upVote"
             vote[1].attributes[1].value - valor do "src" do "downVote"
            */

            /*if (vote[0].attributes[0].value == voteType) {
                if (vote[0].attributes[1].value == "./img/up-arrow-check.png") {

                    vote[0].attributes[1].value = "./img/up-arrow.png";
                    numberOfUpVotes--;
                    $(this).children()[1].textContent = numberOfUpVotes.toString();

                    if (vote[1].attributes[1].value == "./img/down-arrow-check.png") {
                        vote[1].attributes[1].value = "./img/down-arrow.png";
                        numberOfDownVotes++;
                        $(this).children()[4].textContent = numberOfDownVotes.toString();
                    }

                } else {
                    vote[0].attributes[1].value = "./img/up-arrow-check.png";
                    numberOfUpVotes++;
                    $(this).children()[1].textContent = numberOfUpVotes.toString();


                    if (vote[1].attributes[1].value == "./img/down-arrow-check.png") {
                        vote[1].attributes[1].value = "./img/down-arrow.png";
                        numberOfDownVotes--;
                        $(this).children()[4].textContent = numberOfDownVotes.toString();
                    }
                }
            } else {

                if (vote[1].attributes[1].value == "./img/down-arrow-check.png") {

                    vote[1].attributes[1].value = "./img/down-arrow.png";
                    numberOfDownVotes--;
                    $(this).children()[4].textContent = numberOfDownVotes.toString();

                    if (vote[0].attributes[1].value == "./img/up-arrow-check.png") {

                        vote[0].attributes[1].value = "./img/up-arrow.png";
                        numberOfUpVotes--;
                        $(this).children()[1].textContent = numberOfUpVotes.toString();
                    }
                } else {
                    vote[1].attributes[1].value = "./img/down-arrow-check.png";
                    numberOfDownVotes++;
                    $(this).children()[4].textContent = numberOfDownVotes.toString();

                    if (vote[0].attributes[1].value == "./img/up-arrow-check.png") {
                        vote[0].attributes[1].value = "./img/up-arrow.png";
                        numberOfUpVotes--;
                        $(this).children()[1].textContent = numberOfUpVotes.toString();
                    }
                }
            }*/

            let obj = {
                "id": id,
                "voteType": voteType,
                "type": typeComment
            }

            $.ajax({

                //faz o GET com um array vazio para o ficheiro "controller.php"
                type: "POST",
                url: "./backend/controller/post.php",

                //o ficheiro "controller.php" vai procurar por um GET request com o nome de "refresh"
                data: { votesChange: JSON.stringify(obj) },
                cache: false,

                dataType: "json",
                //o valor recebido vai ser transformado no tipo JSON
                success: function (vote) {
                    $('form#' + id).children().children()[1].textContent = vote.upVote;
                    $('form#' + id).children().children()[4].textContent = vote.downVote;
                },
                error: function (data) {
                    alert("error: " + data.responseText);
                    //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                }
            })

        });
    }

    function Search() {
        $(".search").on('submit', function (e) {
            e.preventDefault();

            let search = {
                "titleSearch": $(this).children().children()[0].value,
                "result": []
            }

            $.ajax({

                //faz o GET com um array vazio para o ficheiro "post.php"
                type: "GET",
                url: "./backend/controller/post.php",

                //o ficheiro "post.php" vai procurar por um GET request com o nome de "refresh"
                data: { search: JSON.stringify(search) },
                cache: false,

                dataType: "json",
                //o valor recebido vai ser transformado no tipo JSON
                success: function (data) {
                    let posts = data;

                    //limpa os posts antigos
                    $('#posts > div').remove();

                    //atualiza os posts
                    posts.forEach(post => {
                        $("#posts").append($('<div class="row">'
                            + '<form id=' + post.id + '>'
                            + '<h2 class="post" >' + post.title + '</h2>'
                            + '<p>Username: ' + post.username + '</p>'
                            + '<p>' + post.description + '</p>'
                            + '<div class="voteClick">'
                            + '<a href="#"><img class="upVote" src="./img/up-arrow.png"></a> <label>' + post.Vote.upVote + '</label><br>'
                            + '<a href="#"><img class="downVote" src="./img/down-arrow.png"></a> <label>' + post.Vote.downVote + '</label>'
                            + '</div>'
                            + '</form>'
                            + '</div>'
                        ));
                    });

                    InsertPost();
                    EnterPost();
                    Votes();
                },
                error: function (data) {
                    alert("erro: " + data.responseText);
                    //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                    RefreshFeed(data);
                }
            })
        })
    }

    function AddComment(postId) {

        $("#addComment").on("submit", function (e) {
            e.preventDefault();

            //vai buscar o valor de cada input
            let commentText = $("#commentText")[0].value;
            let comment = {
                "commentText": commentText,
                "postId": postId
            }

            //trasnforma o valor javascript em JSON
            let insertJSON = JSON.stringify(comment);

            $.ajax({
                //faz o POST do objeto JSON para o ficheiro "post.php"
                type: "POST",
                url: "./backend/controller/post.php",

                //o ficheiro "post.php" vai procurar por um POST request com o nome de "insert"
                data: { addComment: insertJSON },
                cache: false,

                success: function (data) {

                    LoadComments(postId);
                },
                error: function (data) {
                    //em caso de erro irá avisar um possível motivo pelo qual não foi possível inserir a pessoa
                    alert("Erro: " + data.responseText);
                }
            })
        })

    }

    function LoadComments(postId) {
        let post = {
            "id": postId,
            "comments": []
        };

        $.ajax({

            //faz o GET com um array vazio para o ficheiro "post.php"
            type: "GET",
            url: "./backend/controller/post.php",

            //o ficheiro "post.php" vai procurar por um GET request com o nome de "refresh"
            data: { loadComments: JSON.stringify(post) },
            cache: false,

            dataType: "json",
            //o valor recebido vai ser transformado no tipo JSON
            success: function (data) {
                let comments = data;

                //limpa os posts antigos
                $('.comments > div').remove();

                //atualiza os posts
                comments.forEach(comment => {
                    $(".comments").append($('<div class="row">'
                        + '<form id=' + comment.id + '>'
                        + '<p>Username: ' + comment.username + '</p>'
                        + '<a href="#" class="delete">Delete</a><br>'
                        + '<p>' + comment.text + '</p>'
                        + '<div class="voteClick">'
                        + '<a href="#"><img class="upVote" src="./img/up-arrow.png"></a> <label>' + comment.Vote.upVote + '</label><br>'
                        + '<a href="#"><img class="downVote" src="./img/down-arrow.png"></a> <label>' + comment.Vote.downVote + '</label>'
                        + '</div>'
                        + '</form>'
                        + '</div>'
                    ));
                });

                Votes();
            }
        })
    }

    function CreatePostHTML() {
        $(".CreatePostHTML").click(function () {

            $('#posts > div').remove();

            $("#posts").append($(
                '<div class="row">'
                + '<div class="col-md">'
                + '<h1>Post:</h1>'
                + '<form id="insert" method="POST">'
                + '<label for="title">Title:</label>'
                + '<input required type="text" name="title" id="title">'

                + '<br><br>'

                + '<label for="description">Description:</label>'
                + '<br>'
                + '<textarea required type="text" name="description" id="description"></textarea>'

                + '<br>'
                + '<button class="btn btn-secondary" id="btn-insert">Submeter</button>'
                + '</form>'
                + '</div>'
                + '</div>'
            ));
            InsertPost();


        })
    }
})
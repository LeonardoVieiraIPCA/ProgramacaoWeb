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
                        + '<h2 class="post" >' + post.title + '</h2>'
                        + '<!--<a class="delete">Delete</a><br>-->'
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
                alert(data.responseText);
                //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                RefreshFeed(data);
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

                $('body > div').remove();

                $("body").append($('<div id="posts">'
                    + '<div class="row">'
                    + '<form class="post" id=' + data.id + '>'
                    + '<h1>' + data.title + '</h1>'
                    + '<span>' + data.username + '</span>'
                    + '<br>'
                    + '<a class="delete">Delete</a><br>'
                    + '<div class="voteClick">'
                    + '<a href="#"><img class="upVote" src="./img/up-arrow.png"></a><label>' + data.Vote.upVote + '</label><br>'
                    + '<a href="#"><img class="downVote" src="./img/down-arrow.png"></a><label>' + data.Vote.downVote + '</label>'
                    + '</div>'
                    + '<p>' + data.description + '</p>'
                    + '</form>'
                    + '</div>'
                    + '<button onClick="window.location.reload();">Go Back</button>'
                    + '</div>'
                ));

                Delete();
                Votes();

            },
            error: function (data) {
                //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                RefreshFeed(data);
            }
        })
    }

    function EnterPost() {
        $('.post').on('click', function (e) {

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

    function Delete() {
        //ao carregar no botão delete da tabela vai eliminar a respetiva pessoa 
        $('#posts').on('click', '.delete', function (e) {
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

                    alert(msg);

                    //e irá recarregar a tabela
                    RefreshFeed()
                },
                error: function (data) {
                    //em caso de erro irá avisar um possível motivo pelo qual não foi possível eliminar a pessoa
                    alert("Erro: " + data.responseText);
                }
            })
        });
    }

    function Votes() {
        //NOTA: É obrigatório começar com um "id" caso contrário não entra na função
        //Sistema de "Upvote" e "DownVote"
        $(".voteClick").click(function (e) {
            e.preventDefault();

            //vai buscar o "id" do respetivo "post"
            let id = $(this).parents()[0].id;

            //convert para Int
            let numberOfUpVotes = parseInt($(this).children()[1].textContent);
            let numberOfDownVotes = parseInt($(this).children()[4].textContent);

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
            let vote = $('form#' + id).children().children().children();

            if (vote[0].attributes[0].value == voteType) {
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
            }

            let post = {
                "id": id,
                "voteType": voteType
            }

            $.ajax({

                //faz o GET com um array vazio para o ficheiro "controller.php"
                type: "POST",
                url: "./backend/controller/post.php",

                //o ficheiro "controller.php" vai procurar por um GET request com o nome de "refresh"
                data: { votesChange: JSON.stringify(post) },
                cache: false,

                //dataType: "json",
                //o valor recebido vai ser transformado no tipo JSON
                success: function (data) {

                    alert(data);

                },
                error: function (data) {
                    alert(data);
                    //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                    RefreshFeed(data);
                }
            })

        });
    }
})
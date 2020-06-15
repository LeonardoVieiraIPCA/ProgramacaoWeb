$(document).ready(function () {

    //da refresh aos posts
    RefreshFeed();

    //impede que o form apareça
    //Dialog(false);

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

            success: function (msg) {

                //caso consiga inserir com sucesso irá avisar o ultizador
                alert(msg);

                RefreshFeed();

                //vai limpar o form
                $("#title")[0].value = "";
                $("#description")[0].value = "";
            },
            error: function (data) {
                //em caso de erro irá avisar um possível motivo pelo qual não foi possível inserir a pessoa
                alert("Erro: " + data.responseText);
            }
        })
    })

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
                        + '<form class="post" id=' + post.id + '>'
                        + '<h2>' + post.title + '</h2>'
                        + '<!--<a class="delete">Delete</a><br>-->'
                        + '<label><a class="changeVotesColor" href="#"><img class="upVote" src="./img/up-arrow.png"></a>' + post.Vote.upVote + '</label><br>'
                        + '<label><a class="changeVotesColor" href="#"><img class="downVote" src="./img/down-arrow.png"></a>' + post.Vote.downVote + '</label>'
                        + '</form>'
                        + '</div>'
                    ));
                });
            },
            error: function (data) {
                //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                RefreshFeed(data);
            }
        })
    }

    $('#posts').on('click', function (e) {
        let post = e.target;
        if (post.className == "post" && post.id != "") {

            $.ajax({

                //faz o GET com um array vazio para o ficheiro "post.php"
                type: "GET",
                url: "./backend/controller/post.php",

                //o ficheiro "post.php" vai procurar por um GET request com o nome de "refresh"
                data: { getPost: JSON.stringify(post.id) },
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
                        + '<label><a class="changeVotesColor" href="#"><img class="upVote" src="./img/up-arrow.png"></a>' + data.Vote.upVote + '</label><br>'
                        + '<label><a class="changeVotesColor" href="#"><img class="downVote" src="./img/down-arrow.png"></a>' + data.Vote.downVote + '</label>'
                        + '<p>' + data.description + '</p>'
                        + '</form>'
                        + '</div>'
                        + '<button onClick="window.location.reload();">Go Back</button>'
                        + '</div>'
                    ));

                },
                error: function (data) {
                    //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                    RefreshFeed(data);
                }
            })
        }
    })

    //NOTA: É obrigatório começar com um "id" caso contrário não entra na função
    //Sistema de "Upvote" e "DownVote"
    $('#posts').on('click', '.changeVotesColor', function (e) {
        e.preventDefault();

        //vai buscar o "id" do respetivo "post"
        let id = $(this).parents()[1].id;

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
                vote[1].attributes[1].value = "./img/down-arrow.png";
            } else {
                vote[0].attributes[1].value = "./img/up-arrow-check.png";
                vote[1].attributes[1].value = "./img/down-arrow.png";
            }
        } else {

            if (vote[1].attributes[1].value == "./img/down-arrow-check.png") {
                vote[1].attributes[1].value = "./img/down-arrow.png";
                vote[0].attributes[1].value = "./img/up-arrow.png";
            } else {
                vote[1].attributes[1].value = "./img/down-arrow-check.png";
                vote[0].attributes[1].value = "./img/up-arrow.png";
            }
        }


        // $.ajax({

        //     //faz o GET com um array vazio para o ficheiro "controller.php"
        //     type: "POST",
        //     url: "./backend/controller/post.php",

        //     //o ficheiro "controller.php" vai procurar por um GET request com o nome de "refresh"
        //     data: { refresh: JSON.stringify([]) },
        //     cache: false,

        //     dataType: "json",
        //     //o valor recebido vai ser transformado no tipo JSON
        //     success: function (data) {
        //     },
        //     error: function (data) {
        //         //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
        //         RefreshFeed(data);
        //     }
        // })

    });
})
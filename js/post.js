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

            success: function (person) {

                //caso consiga inserir com sucesso irá avisar o ultizador
                alert(person);

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
    $('#table').on('click', '.delete', function (e) {
        e.preventDefault();

        //vai buscar o id do respetivo utilziador
        let id = $(this).parents()[1].id;

        $.ajax({
            //faz o POST do id para o ficheiro "controller.php"
            type: "POST",
            url: "./backend/controller.php",

            //o ficheiro "controller.php" vai procurar por um POST request com o nome de delete
            data: { delete: JSON.stringify(id) },
            cache: false,

            //o valor recebido vai ser transformado no tipo JSON
            dataType: 'json',
            success: function (person) {

                //caso consiga eliminar com sucesso irá avisar o ultizador
                alert("A pessoa " + person.firstName + " " + person.lastName + " com o ID: " + person.personID
                    + " de " + person.age + " anos, foi eliminada!");

                //e irá recarregar a tabela
                GetPerson();
            },
            error: function (data) {
                //em caso de erro irá avisar um possível motivo pelo qual não foi possível eliminar a pessoa
                alert("Erro: " + data.responseText);
            }
        })
    });

    function RefreshFeed() {
        $.ajax({

            //faz o GET com um array vazio para o ficheiro "controller.php"
            type: "GET",
            url: "./backend/controller/post.php",

            //o ficheiro "controller.php" vai procurar por um GET request com o nome de "refresh"
            data: { refresh: JSON.stringify([]) },
            cache: false,

            dataType: "json",
            //o valor recebido vai ser transformado no tipo JSON
            success: function (data) {
                let posts = data;
                //vai atualizar a tabela com a informação recebida
                $('#posts').empty();

                //cria a tabela com cada utilizador
                posts.forEach(post => {
                    $("#posts").append($('<div class="row">'
                        + '<form id=' + post.id + '>'
                        + '<h2 class="title"><a href="#">' + post.title + '</a></h2>'
                        + '<a class="delete btn btn-danger">Delete</a><br>'
                        + '<label><a class="changeVotesColor" href="#"><img class="upVote" src="./img/up-arrow.png"></a> Up Votes: ' + post.Vote.upVote + '</label><br>'
                        + '<label><a class="changeVotesColor" href="#"><img class="downVote" src="./img/down-arrow.png"></a> Down Votes: ' + post.Vote.downVote + '</label>'
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

    //é obrigatório começar com um id caso contrário não entra na função
    $('#posts').on('click', '.changeVotesColor', function (e) {
        e.preventDefault();

        //vai buscar o "id" do respetivo "post"
        let id = $(this).parents()[1].id;

        let voteType = e.target.className;

        let vote = $('form#' + id).children().children().children("." + voteType);

        if (vote.attr("class") == "upVote") {
            vote.attr({
                src: "./img/up-arrow-check.png"
            })
        } else {
            vote.attr({
                src: "./img/down-arrow-check.png"
            })
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
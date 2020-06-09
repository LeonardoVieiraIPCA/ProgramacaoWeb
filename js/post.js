$(document).ready(function () {

    //da refresh aos posts
    RefreshFeed();

    //impede que o form apareça
    //Dialog(false);

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
                $('.posts').empty();

                //cria a tabela com cada utilizador
                posts.forEach(post => {
                    $(".posts").append($('<div class="row" id=' + post.id + '>'
                        + '<form>'
                        + '<h2 class="title">' + post.title + '</h2>'
                        + '<a class="delete btn btn-danger">Delete</a><br>'
                        + '<label>Up Votes: ' + post.Vote.upVote + '</label><br>'
                        + '<label>Down Votes: ' + post.Vote.downVote + '</label>'
                        + '</form>'
                        + '</div><br>'
                    ));
                });
            },
            error: function (data) {
                //caso contrario irá apresentar uma mensagem a dizer que não foi possível encontrar resultados
                RefreshFeed(data);
            }
        })
    }
});
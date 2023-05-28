<x-layout title="Cadastro de Livros">

    <button  style="margin-bottom: 3%;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#cadastroModal">Novo</button>

    <table id="tbl_books" class="table table-striped mt-4 text-center" >
      <thead class="thead-dark">
        <tr>
          <th style="width: 10%" >Nº de registro</th>
          <th>Titulo</th>
          <th>Autor</th>
          <th>Gênero</th>
          <th>Situação</th>
          <th>...</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="cadastroModal" tabindex="-1" role="dialog" aria-labelledby="cadastroModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="cadastroModalLabel">Formulário de Cadastro</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

              <div class="form-group">
                <label for="title">Titulo*</label>
                <input type="text" class="form-control" id="title" required>
              </div>
              <div class="form-group">
                <label for="author">Autor*</label>
                <input type="text" class="form-control" id="author" required>
              </div>

              <div class="form-group">
                <select class="form-control" id="selectGenres">
                  <option>Selecione o gênero</option>
                  @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                  @endforeach

                </select>
              </div>


              <button type="button" class="btn btn-primary" id="salvarBtn">Salvar</button>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Edição -->
<div class="modal fade" id="edicaoModal" tabindex="-1" role="dialog" aria-labelledby="edicaoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edicaoModalLabel">Editar Livro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="bookId">

        <div class="form-group">
          <label for="numeroCadastroEdit">Número de Registro*</label>
          <input disabled type="text" class="form-control" id="numeroCadastroEdit" required>
        </div>

        <div class="form-group">
          <label for="tituloEdit">Titulo*</label>
          <input type="text" class="form-control" id="tituloEdit" required>
        </div>
        <div class="form-group">
          <label for="autorEdit">Autor*</label>
          <input type="text" class="form-control" id="autorEdit" required>
        </div>

        <div class="form-group">
        <select class="form-control" id="selectGenresEdit">
            <option>Selecione o gênero</option>
            @foreach ($genres as $genre)
            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
            @endforeach

        </select>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="salvarEdicaoBtn">Salvar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal emprestar livro-->
<div class="modal fade" id="emprestarLivroModal" tabindex="-1" role="dialog" aria-labelledby="emprestarLivroModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emprestarLivroModalLabel">Empréstimo de Livro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="emprestarLivroForm">
                    <input type="hidden" id="bookIdEmprestimo">

                    <div class="form-group">
                        <label for="usuario">Pesquise o Usuário</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="usuario" placeholder="Buscar usuário" autocomplete="off">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                        <div id="resultadoUsuarios"></div>
                    </div>
                    <div class="form-group">
                        <label for="dataDevolucao">Data de Devolução</label>
                        <input type="date" class="form-control" id="dataDevolucao">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" id="emprestarLivroBtn" class="btn btn-primary" onclick="emprestarLivro()">Emprestar</button>
            </div>
        </div>
    </div>
</div>


  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>

$(document).ready(function() {
    $('#tbl_books').DataTable({
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json'
    },
    });

        function findBooks(){
            $.ajax({
            url: '{{ route('books.index') }}',
            method: 'GET',
            success: function(response) {
            var table = $('#tbl_books').DataTable();


            table.clear();

            $.each(response, function(index, book) {
                let editarBtn = '<button class="btn btn-primary btn-editar-usuario" onClick="abrirModalEdicao(' + book.id + ')"><i class="fas fa-pencil-alt"></i></button>';
                let excluirBtn = '<button class="btn btn-danger btn-excluir-usuario" data-id="' + book.id + '" onclick="excluirLivro(' + book.id + ')"><i class="fas fa-trash"></i></button>';
                let devolverBtn = '<button class="btn btn-warning btn-devolver-livro" data-id="' + book.id + '" onclick="devolverLivro(' + book.id + ')"><i class="fas fa-undo"></i></button>';
                let emprestarBtn = '<button class="btn btn-success btn-emprestar-livro"  data-id="' + book.id + '" onclick="abrirModalEmprestarLivro(' + book.id + ')"><i class="fas fa-handshake"></i></button>';

                if(book.deleted){
                    actions = ''
                    status = '<strong style="color: red" >Excluído</strong>'
                }else{

                    if(!book.available){
                        let now = new Date();
                        let date = new Date(book.return_due_date);

                        // verifica se esta atrasado

                        if(date < now){
                            status = '<strong style="color: red" >Atrasado</strong>'

                        }else{
                            status = '<strong style="color: gray" >Emprestado</strong>'
                        }


                        actions = editarBtn + ' ' + devolverBtn;

                    }else{
                        status = '<strong style="color: green" >Disponível</strong>'
                        actions = emprestarBtn + ' ' +editarBtn + ' ' + excluirBtn;
                    }

                }

                table.row.add([
                    book.id,
                    book.title,
                    book.author,
                    book.genres.name,
                    status,
                    actions
                ]).draw(false);
            });
            },
            error: function() {
                console.log('Erro na chamada AJAX');
            }
        });

      }


      findBooks();

    $('#salvarBtn').on('click', function() {
    var title = $('#title').val();
    var author = $('#author').val();
    var genre = $('#selectGenres').val();

    var data = {
        title: title,
        author: author,
        genre_id: genre
    };

      $.ajax({
        url: '{{ route('books.store') }}',
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        success: function(response) {

          console.log(response);

          $('#cadastroModal').modal('hide');

          $('#title').val('');
          $('#author').val('');

          findBooks();


        },
        error: function(xhr, status, error) {
          // Erro, trata o erro conforme necessário
          console.error(error);
        }
      });
    });



    function abrirModalEdicao(bookID) {

        $.ajax({
            url: 'api/books/' + bookID ,
            method: 'GET',
            success: function(response) {
                console.log(response);

                var book = response;

                $('#bookId').val(book.id);
                $('#tituloEdit').val(book.title);
                $('#autorEdit').val(book.author);
                $('#selectGenresEdit').val(book.genre_id);
                $('#numeroCadastroEdit').val(book.id);

                $('#edicaoModal').modal('show');

            }
        })


    }

    function abrirModalEmprestarLivro(bookID) {

      $('#bookIdEmprestimo').val(bookID);

      $('#emprestarLivroModal').modal('show');

    }


    // Evento de clique para salvar a edição do usuário
    $('#salvarEdicaoBtn').on('click', function() {
      var bookId = $('#bookId').val();
      var title = $('#tituloEdit').val();
      var author = $('#autorEdit').val();
      var genre = $('#selectGenresEdit').val();

      var data = {
        title,
        author,
        genre_id: genre
      };

      $.ajax({
        url: 'api/books/' + bookId,
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        success: function(response) {
          // Sucesso, você pode fazer o que quiser com a resposta
          console.log(response);

          // Fechar o modal após a edição bem-sucedida
            $('#edicaoModal').modal('hide');
            $('#bookId').val('');
            $('#tituloEdit').val('');
            $('#autorEdit').val('');
            findBooks();
        },

        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

function excluirLivro(bookId) {
  // Exibir um diálogo de confirmação usando o SweetAlert2
  Swal.fire({
    title: 'Tem certeza?',
    text: 'Esta ação irá excluir o livro permanentemente.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, excluir',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      // Solicitar exclusão via AJAX
      $.ajax({
        url: 'api/books/' + bookId,
        method: 'DELETE',
        success: function(response) {
          // Exibir mensagem de sucesso
          Swal.fire({
            title: 'Livro excluído!',
            text: 'O Livro foi excluído com sucesso.',
            icon: 'success'
          });

          // Atualizar a tabela de usuários
          findBooks();
        },
        error: function() {
          // Exibir mensagem de erro
          Swal.fire({
            title: 'Erro!',
            text: 'Ocorreu um erro ao excluir o livro.',
            icon: 'error'
          });
        }
      });
    }
  });
}

function devolverLivro(bookId) {
  // Exibir um diálogo de confirmação usando o SweetAlert2
  Swal.fire({
    title: 'Tem certeza?',
    text: 'Esta ação irá devolver o livro.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, devolver',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      // Solicitar exclusão via AJAX
      $.ajax({
        url: 'api/loans/' + bookId,
        method: 'PUT',
        success: function(response) {
          // Exibir mensagem de sucesso
          Swal.fire({
            title: 'Livro devolvido!',
            text: 'O Livro foi devolvido com sucesso.',
            icon: 'success'
          });

          // Atualizar a tabela de usuários
          findBooks();
        },
        error: function() {
          // Exibir mensagem de erro
          Swal.fire({
            title: 'Erro!',
            text: 'Ocorreu um erro ao excluir o livro.',
            icon: 'error'
          });
        }
      });
    }
  });
}



$('#emprestarLivroBtn').on('click', function() {
      let dataDevolucao = $('#dataDevolucao').val();
      let usuario = $('#usuario').val();
      let bookId = $('#bookIdEmprestimo').val();

        let data = {
            dataDevolucao,
            usuario,
            bookId: Number(bookId)
        };

        console.log(data)

});


// Função para realizar a busca de usuários enquanto o usuário digita
function buscarUsuarios() {
    var termo = $('#usuario').val();

    // Fazer uma requisição AJAX para a API de busca de usuários
    $.ajax({
        url: '/api/user-library/filter/'+ termo, // URL da sua API de busca de usuários
        method: 'GET',
        success: function(response) {
             // Limpa o select existente
             var selectUsuarios = '<select class="form-control" id="selectUsuarios"><option value="">Selecione um usuário</option>';

            // Iterar pelos resultados e adicionar opções ao select
            $.each(response, function(index, usuario) {
                selectUsuarios += '<option value="' + usuario.id + '">' + usuario.name + '</option>';
            });

            selectUsuarios += '</select>';

            // Atualizar o elemento #resultadoUsuarios com o select de resultados
            $('#resultadoUsuarios').html(selectUsuarios);
            $('#resultadoUsuarios').focus();
            },
            error: function() {
            console.log('Erro na chamada AJAX');
            }

    });

}


function emprestarLivro(){

        let dataDevolucao = $('#dataDevolucao').val();
        let usuario = $('#selectUsuarios').val();
        let bookId = $('#bookIdEmprestimo').val();

        if(!dataDevolucao || !usuario  || !bookId ){
            Swal.fire({
                title: 'Erro!',
                text: 'Preencha todos os campos.',
                icon: 'error'
              });
              return;
        }

        let data = {
            date_return: dataDevolucao,
            user_id: usuario,
            book_id: Number(bookId)
        };

        console.log(data)

        $.ajax({
            url: '/api/loans',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            success: function(response) {
                // Sucesso, você pode fazer o que quiser com a resposta
                console.log(response);

                // Fechar o modal após a edição bem-sucedida
                $('#emprestarLivroModal').modal('hide');
                $('#dataDevolucao').val('');
                $('#selectUsuarios').val('');
                $('#bookIdEmprestimo').val('');
                findBooks();
            },

            error: function(xhr, status, error) {
                console.error(error);
            }
        });

}


// Evento para chamar a função buscarUsuarios() enquanto o usuário digita
$('#usuario').on('input', buscarUsuarios);

window.abrirModalEdicao = abrirModalEdicao;
window.excluirLivro = excluirLivro;
window.devolverLivro = devolverLivro;
window.abrirModalEmprestarLivro = abrirModalEmprestarLivro;
window.emprestarLivro = emprestarLivro;



});


  </script>


</x-layout>

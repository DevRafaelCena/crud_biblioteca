<x-layout title="Cadastro de Usuários">

    <button  style="margin-bottom: 3%;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#cadastroModal">Novo</button>

    <table id="tbl_usuarios" class="table table-striped mt-4 text-center" >
      <thead class="thead-dark">
        <tr>
          <th style="width: 10%" >Nº de registro</th>
          <th>Nome Completo</th>
          <th>Email</th>
          <th>Status</th>
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
                <label for="nomeCompleto">Nome Completo*</label>
                <input type="text" class="form-control" id="name" required>
              </div>
              <div class="form-group">
                <label for="email">Email*</label>
                <input type="email" class="form-control" id="email" required>
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
        <h5 class="modal-title" id="edicaoModalLabel">Editar Usuário</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="usuarioId">

        <div class="form-group">
          <label for="numeroCadastroEdit">Número de Cadastro*</label>
          <input disabled type="text" class="form-control" id="numeroCadastroEdit" required>
        </div>

        <div class="form-group">
          <label for="nomeCompletoEdit">Nome Completo*</label>
          <input type="text" class="form-control" id="nomeCompletoEdit" required>
        </div>
        <div class="form-group">
          <label for="emailEdit">Email*</label>
          <input type="email" class="form-control" id="emailEdit" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="salvarEdicaoBtn">Salvar</button>
      </div>
    </div>
  </div>
</div>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function() {
      $('#tbl_usuarios').DataTable({
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json'
        },
      });

        function findUsers(){
            $.ajax({
            url: '{{ route('user-library.index') }}',
            method: 'GET',
            success: function(response) {
            var table = $('#tbl_usuarios').DataTable();


            table.clear();

            $.each(response, function(index, user) {
                let editarBtn = '<button class="btn btn-primary btn-editar-usuario" onClick="abrirModalEdicao(' + user.id + ')"><i class="fas fa-pencil-alt"></i></button>';
                let excluirBtn = '<button class="btn btn-danger btn-excluir-usuario" data-id="' + user.id + '" onclick="excluirUsuario(' + user.id + ')"><i class="fas fa-trash"></i></button>';

                let actions = user.deleted? "" : editarBtn + ' ' + excluirBtn;

                let status = user.deleted == 0 ? 'Ativo' : '<strong style="color: red" >Excluído</strong>';

                table.row.add([
                user.id,
                user.name,
                user.email,
                status,
                actions,
                ]).draw(false);
            });
            },
            error: function() {
                console.log('Erro na chamada AJAX');
            }
        });

      }


      findUsers();

    $('#salvarBtn').on('click', function() {
    var name = $('#name').val();
    var email = $('#email').val();

    var data = {
        name: name,
        email: email
    };

      $.ajax({
        url: '{{ route('user-library.store') }}',
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        success: function(response) {

          console.log(response);

          $('#cadastroModal').modal('hide');

          $('#nomeCompleto').val('');
          $('#email').val('');

          findUsers();


        },
        error: function(xhr, status, error) {
          // Erro, trata o erro conforme necessário
          console.error(error);
        }
      });
    });



    function abrirModalEdicao(usuarioID) {

        $.ajax({
            url: 'api/user-library/' + usuarioID ,
            method: 'GET',
            success: function(response) {
                console.log(response);

                var usuario = response;

                $('#usuarioId').val(usuario.id);
                $('#nomeCompletoEdit').val(usuario.name);
                $('#emailEdit').val(usuario.email);
                $('#numeroCadastroEdit').val(usuario.id);

                $('#edicaoModal').modal('show');

            }
        })


    }


    // Evento de clique para salvar a edição do usuário
    $('#salvarEdicaoBtn').on('click', function() {
      var usuarioId = $('#usuarioId').val();
      var name = $('#nomeCompletoEdit').val();
      var email = $('#emailEdit').val();

      var data = {
        name: name,
        email: email
      };

      $.ajax({
        url: 'api/user-library/' + usuarioId,
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
            $('#usuarioId').val('');
            $('#nomeCompletoEdit').val('');
            $('#emailEdit').val('');
            $('#numeroCadastroEdit').val('');
            findUsers();
        },

        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

    function excluirUsuario(userId) {
    // Exibir um diálogo de confirmação usando o SweetAlert2
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Esta ação irá excluir o usuário permanentemente.',
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
            url: 'api/user-library/' + userId,
            method: 'DELETE',
            success: function(response) {
            // Exibir mensagem de sucesso
            Swal.fire({
                title: 'Usuário excluído!',
                text: 'O usuário foi excluído com sucesso.',
                icon: 'success'
            });

            // Atualizar a tabela de usuários
            findUsers();
            },
            error: function() {
            // Exibir mensagem de erro
            Swal.fire({
                title: 'Erro!',
                text: 'Ocorreu um erro ao excluir o usuário.',
                icon: 'error'
            });
            }
        });
        }
    });
    }

    window.abrirModalEdicao = abrirModalEdicao;
    window.excluirUsuario = excluirUsuario;
});


</script>

</x-layout>

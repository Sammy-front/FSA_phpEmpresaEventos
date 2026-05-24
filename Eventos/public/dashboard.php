<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    $_SESSION['mensagem'] = "Por favor, faça login para acessar a área de eventos.";
    header('Location: ../views/auth/login.php');
    exit;
}

require __DIR__ . '/../config/conexao.php';
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  <body>
    <?php include('../views/layouts/navbar.php'); ?>
    
    <div class="container mt-4">
      <?php include('../views/layouts/mensagem.php'); ?>
      
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Olá, <?= $_SESSION['nome']; ?>! <span class="badge bg-secondary fs-6">Administrador</span></h4>
        <a href='../logout.php' class="btn btn-outline-danger">Sair da Conta</a>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
              <h4 class="mb-0 pt-1 pb-1">Lista de Eventos
                <a href="../views/eventos/evento-create.php" class="btn btn-primary float-end">Adicionar Evento</a>
              </h4>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Data Evento</th>
                    <th>Capacidade</th>
                    <th style="width: 250px;">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = 'SELECT * FROM eventos';
                  $eventos = mysqli_query($conexao, $sql);
                  
                  if (mysqli_num_rows($eventos) > 0) {
                    foreach($eventos as $evento) {
                  ?>
                  <tr>
                    <td><?=$evento['id']?></td>
                    <td><?=$evento['nome']?></td>
                    <td><?=date('d/m/Y', strtotime($evento['data_evento']))?></td>
                    <td><?=$evento['capacidade']?> pessoas</td>
                    <td>
                      <!-- CORRIGIDO: Link 'Ver' não envia mais ID desnecessário que causava falha visual -->
                      <a href="../views/eventos/evento-view.php" class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Ver Gestão Completa</a>
                      <a href="../views/eventos/evento-edit.php?id=<?=$evento['id']?>" class="btn btn-success btn-sm"><span class="bi-pencil-fill"></span>&nbsp;Editar</a>
                      <form action="../controllers/eventoControllers.php" method="POST" class="d-inline">
                        <button onclick="return confirm('Tem certeza que deseja excluir o evento <?=$evento['nome']?>?')" type="submit" name="delete_evento" value="<?=$evento['id']?>" class="btn btn-danger btn-sm">
                          <span class="bi-trash3-fill"></span>&nbsp;Deletar
                        </button>
                      </form>
                    </td>
                  </tr>
                  <?php
                    }
                  } else {
                    echo '<tr><td colspan="5" class="text-center"><h5>Nenhum evento foi agendado ainda</h5></td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
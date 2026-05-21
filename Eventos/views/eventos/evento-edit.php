<?php
session_start();
require __DIR__ . '/../../config/conexao.php';
?>


<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <title>Evento - Editar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <?php include('../layouts/navbar.php'); ?>

  <div class="container mt-5">
    <div class="card">
      <div class="card-header">
        <h4>Editar Evento <a href="../../public/index.php" class="btn btn-danger float-end">Voltar</a></h4>
      </div>
      <div class="card-body">

        <?php
          // Busca da tabela de eventos.
        if (isset($_GET['id'])) {
          $evento_id = mysqli_real_escape_string($conexao, $_GET['id']);
          $sql = "SELECT * FROM eventos WHERE id='$evento_id'";
          $query = mysqli_query($conexao, $sql);

          if (mysqli_num_rows($query) > 0) {
            $evento = mysqli_fetch_array($query);
        ?>
            <form action="../../controllers/acoes.php" method="POST">
              <!-- Identifica o ID do evento atual para atualizar a linha certa no banco -->
              <input type="hidden" name="evento_id" value="<?= $evento['id'] ?>">

              <div class="mb-3">
                <label>Nome do Evento</label>
                <input type="text" name="nome" value="<?= $evento['nome'] ?>" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Data do Evento</label>
                <input type="date" name="data_evento" value="<?= $evento['data_evento'] ?>" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Capacidade de pessoas</label>
                <input type="number" name="capacidade" value="<?= $evento['capacidade'] ?>" class="form-control" required>
              </div>
              <div class="mb-3">
                <button type="submit" name="update_evento" class="btn btn-primary">Atualizar Evento</button>
              </div>
            </form>
        <?php } else {
            echo "<h5>Evento não encontrado</h5>";
          }
        } ?>
      </div>
    </div>
  </div>
</body>

</html>
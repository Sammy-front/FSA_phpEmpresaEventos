<?php
session_start();
?>

<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Usuário - Criar Conta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php include(__DIR__ . '/../layouts/navbar.php'); ?>
  <div class="container mt-5">
    <?php include(__DIR__ . '/../layouts/mensagem.php'); ?>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">

          <div class="card-header">
            <h4>
              Cadastro de Usuário
              <a href="../auth/login.php" class="btn btn-danger float-end">Voltar para o Login</a>
            </h4>
          </div>

          <div class="card-body">
            <form action="../../controllers/usuarioControllers.php" method="POST">

              <div class="mb-3">
                <label for="nome" class="form-label">Nome Completo</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" id="senha" name="senha" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="cargo" class="form-label">Cargo</label>
                <select name="cargo" id="cargo" class="form-control" required>
                  <option value="usuario">usuario</option>
                  <option value="adm">adm</option>
                </select>
              </div>


              <div class="mb-3">
                <button type="submit" name="create_usuario" class="btn btn-primary w-100">
                  Cadastrar
                </button>
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
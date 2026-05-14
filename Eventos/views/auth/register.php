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
    <?php include('../layouts/navbar.php'); ?>
    <div class="container mt-5">
      
      <?php include('../layouts/mensagem.php'); // Exibe avisos caso haja ?>
      
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h4>Cadastro de Usuário
                <a href="../auth/login.php" class="btn btn-danger float-end">Voltar para o Login</a>
              </h4>
            </div>
            <div class="card-body">
              <!-- Aqui o formulário aponta para o acoes.php -->
              <form action="../controllers/acoes.php" method="POST">
                
                <div class="mb-3">
                  <label>Nome Completo</label>
                  <input type="text" name="nome" class="form-control" required>
                </div>
                
                <div class="mb-3">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="mb-3">
                  <label>Data de Nascimento</label>
                  <!-- Seu banco espera o padrão de data: -->
                  <input type="date" name="data_nascimento" class="form-control" required>
                </div>
                
                <div class="mb-3">
                  <label>Senha</label>
                  <input type="password" name="senha" class="form-control" required>
                </div>
                
                <div class="mb-3">
                  <!-- IMPORTANTE: o botão tem que se chamar create_usuario para bater com seu acoes.php -->
                  <button type="submit" name="create_usuario" class="btn btn-primary">Cadastrar</button>
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
<?php session_start(); ?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cadastrar Nova Conta - FSA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      background: url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070&auto=format&fit=crop') no-repeat center center/cover fixed;
      background-attachment: fixed;
    }
    .blur-bg {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
    }
  </style>
</head>

<body class="d-flex align-items-center py-5 min-vh-100">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-6">

        <div class="card border-0 rounded-4 shadow-lg blur-bg overflow-hidden">

          <div class="bg-dark p-4 text-center border-bottom border-warning border-4">
            <i class="bi bi-person-lines-fill display-4 text-warning"></i>
            <h3 class="text-white mt-2 mb-0 fw-bold">Junte-se à Nós</h3>
            <p class="text-white-50 small mb-0">Preencha todos os campos com suas informações reais.</p>
          </div>

          <div class="card-body p-4 p-md-5">

            <?php if (isset($_SESSION['mensagem'])): ?>
              <div class="alert alert-warning py-2 mb-4 border border-warning shadow-sm">
                  <i class="bi bi-exclamation-triangle"></i> <?= $_SESSION['mensagem'] ?>
              </div>
              <?php unset($_SESSION['mensagem']); ?>
            <?php endif; ?>

            <form action="../../controllers/usuarioControllers.php" method="POST">
              <div class="form-floating mb-3">
                <input type="text" id="nome" name="nome" class="form-control bg-light" required placeholder="Joao Paulo">
                <label for="nome" class="text-dark fw-bold">Nome Completo</label>
              </div>

              <div class="row">
                <div class="col-sm-7 mb-3 form-floating">
                  <input type="email" id="email" name="email" class="form-control bg-light" required placeholder="@">
                  <label for="email" class="ps-4 text-dark fw-bold">E-mail</label>
                </div>

                <div class="col-sm-5 mb-3 form-floating">
                  <input type="date" id="dt" name="data_nascimento" class="form-control bg-light border" required>
                  <label for="dt" class="ps-4 text-dark fw-bold">Data de Nascimento</label>
                </div>
              </div>

              <div class="form-floating mb-3">
                <input type="password" id="senha" name="senha" class="form-control bg-light border-dark border-opacity-25 shadow-sm" required placeholder="**">
                <label for="senha" class="text-dark fw-bold">Senha de Acesso</label>
              </div>

              <div class="d-grid mt-4">
                <button type="submit" name="create_usuario" class="btn btn-warning py-3 fw-bold fs-5 shadow border border-1 text-dark rounded-3"><i class="bi bi-check2-circle"></i> REGISTRE-SE </button>
              </div>

            </form>
          </div>

          <div class="card-footer bg-white border-0 text-center py-4 text-muted border-top border-2">
            Possui Cadastro Finalizado? <a href="login.php" class="text-primary fw-bold text-decoration-none ms-2 border-bottom">Então faça seu LOGIN agora! </a>
          </div>

        </div>

      </div>
    </div>
  </div>
</body>

</html>
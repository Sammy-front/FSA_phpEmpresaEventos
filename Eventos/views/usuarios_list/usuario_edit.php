<?php
session_start();
require __DIR__ . '/../../config/conexao.php';

if (isset($_GET['id'])) {
    $usuario_id = mysqli_real_escape_string($conexao, $_GET['id']);
    
    $sql = "SELECT * FROM usuarios WHERE id = '$usuario_id'";
    $query = mysqli_query($conexao, $sql);
    
    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_array($query);
    } else {
        $_SESSION['mensagem'] = "Usuário não encontrado.";
        // CORRIGIDO: Redirecionamento correto caso n ache
        header('Location: usuario_list.php'); 
        exit;
    }
} else {
    header('Location: usuario_list.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Perfil - FSA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  <body class="bg-light">

    <?php include('../layouts/navbar.php'); ?>

    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-7">
          
          <div class="card shadow border-0">
            <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Editar Perfil do Usuário</h5>
              <!-- CORRIGIDO: Voltar usando usuario_list.php -->
              <a href="usuario_list.php" class="btn btn-sm btn-outline-light">Voltar</a>
            </div>

            <div class="card-body p-4">
              <form action="../../controllers/usuarioControllers.php" method="POST">
                
                <input type="hidden" name="usuario_id" value="<?= $user['id']; ?>">

                <div class="row mb-3">
                  <div class="col-md-12">
                    <label class="form-label fw-bold">Nome Completo</label>
                    <input type="text" name="nome" value="<?= $user['nome']; ?>" class="form-control" required>
                  </div>
                </div>

                <div class="row mb-4">
                  <div class="col-md-7">
                    <label class="form-label fw-bold">E-mail</label>
                    <input type="email" name="email" value="<?= $user['email']; ?>" class="form-control" required>
                  </div>
                  <div class="col-md-5">
                    <label class="form-label fw-bold">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" value="<?= date('Y-m-d', strtotime($user['data_nascimento'])); ?>" class="form-control">
                  </div>
                </div>
                <hr>

                <div class="bg-light p-3 rounded border">
                    <h6 class="text-muted mb-3 text-uppercase small fw-bold"><i class="bi bi-shield-lock"></i> Configurações de Acesso</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cargo / Nível de Acesso</label>
                            <select name="cargo" class="form-select">
                                <option value="usuario" <?= $user['cargo'] == 'usuario' ? 'selected' : '' ?>>Usuário Padrão</option>
                                <option value="adm" <?= $user['cargo'] == 'adm' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nova Senha</label>
                            <input type="password" name="senha" class="form-control" placeholder="Deixe em branco para não alterar">
                        </div>
                    </div>
                    <small class="text-muted italic">Ao preencher a senha, ela será criptografada automaticamente ao salvar.</small>
                </div>

                <div class="mt-4 d-grid gap-2 d-md-flex justify-content-md-end">
                  <button type="submit" name="update_usuario" class="btn btn-primary btn-lg px-5 shadow-sm">
                    <i class="bi bi-save"></i> Salvar Alterações
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
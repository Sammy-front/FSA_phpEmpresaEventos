<?php
session_start();
require __DIR__ . '/../../config/conexao.php';

// Proteção para impedir invasão de link direto!
if (!isset($_SESSION['usuario']) || $_SESSION['cargo'] !== 'adm') {
    $_SESSION['mensagem'] = "🚫 ACESSO RESTRITO.";
    header('Location: ../../public/dashUser.php'); exit;
}

if (!isset($_GET['id'])) {
    header('Location: usuario_list.php'); exit;
}

$id_buscado = mysqli_real_escape_string($conexao, $_GET['id']);
$dadosQuery = mysqli_query($conexao, "SELECT id, nome, email, data_nascimento, cargo FROM usuarios WHERE id = '$id_buscado'");

if(mysqli_num_rows($dadosQuery) == 0){
   die('Usuário Inválido na Base.');
}

$usuario = mysqli_fetch_assoc($dadosQuery);
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Editar Usuário</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">

  <?php include('../layouts/navbar.php'); ?>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow border-0 border-top border-warning border-3">
          
          <div class="card-header bg-white pb-0">
            <h4 class="fw-light text-dark"><i class="bi bi-person-lines-fill text-warning"></i> 
                Modificar Usuário/Membro 
                <a href="usuario_list.php" class="btn btn-outline-danger btn-sm float-end mt-1"><i class="bi bi-arrow-left"></i> Cancelar / Voltar</a>
            </h4>
          </div>

          <div class="card-body mt-2">
            <!-- Aponta diretamente pro backend de Controladores Inteligente que criamos -->
            <form action="../../controllers/usuarioControllers.php" method="POST">
                <input type="hidden" name="usuario_id" value="<?=$usuario['id']?>">

                <div class="mb-3">
                    <label class="form-label text-secondary fw-bold">Nome Completo</label>
                    <input type="text" name="nome" value="<?=$usuario['nome']?>" class="form-control" required>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-7">
                        <label class="form-label text-secondary fw-bold">Email de Acesso (Conta)</label>
                        <input type="email" name="email" value="<?=$usuario['email']?>" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-secondary fw-bold">Nascimento (Y-M-D)</label>
                        <input type="date" name="data_nascimento" value="<?=$usuario['data_nascimento']?>" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary fw-bold"><i class="bi bi-shield-lock text-success"></i> Credencial Hierárquica do Banco:</label>
                    <select class="form-select border-warning shadow-sm" name="cargo" required>
                       <option value="usuario" <?= ($usuario['cargo'] == 'usuario') ? 'selected' : '' ?>>👤 Padrão (Visitante da Vitrine)</option>
                       <option value="adm" <?= ($usuario['cargo'] == 'adm') ? 'selected' : '' ?>>🛠️ MASTER (Acesso Completo DashBoards/Admin)</option>
                    </select>
                </div>

                <div class="mb-4 alert alert-secondary p-3 shadow-sm border-0">
                   <h6 class="fw-bold mb-2">Redefinição de Senha Segura <span class="badge bg-secondary">Opcional</span></h6>
                   <small class="d-block mb-3 text-muted">Caso deseje forçar alteração. Para <b>manter a atual, deixe completamente em branco!</b></small>
                   <input type="password" name="senha" class="form-control" placeholder="*************">
                </div>

                <div class="d-grid mt-4">
                   <button type="submit" name="update_usuario" class="btn btn-warning btn-lg shadow rounded text-dark fw-bold">
                        <i class="bi bi-floppy2-fill"></i> Efetuar Atualização Restrita no Banco de Dados
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
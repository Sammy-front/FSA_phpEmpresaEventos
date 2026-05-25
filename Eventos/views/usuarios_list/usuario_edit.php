<?php
session_start();
require __DIR__ . '/../../config/conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['cargo'] !== 'adm') {
    $_SESSION['mensagem'] = "Acesso restrito. Apenas administradores podem gerenciar perfis.";
    header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/public/dashUser.php');
    exit;
}

if (isset($_GET['id'])) {
  $usuario_id = mysqli_real_escape_string($conexao, $_GET['id']);
  $sql = "SELECT * FROM usuarios WHERE id = '$usuario_id'";
  $query = mysqli_query($conexao, $sql);

  if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_array($query);
  } else {
    $_SESSION['mensagem'] = "Erro: Usuário não encontrado no sistema.";
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
  
  <style>
    body { background-color: #eef2f5; color: #333; }
    .admin-card { border: none; border-radius: 1rem; box-shadow: 0 15px 35px rgba(0,0,0,0.1); background: #ffffff; }
    .admin-header { 
        background: linear-gradient(135deg, #16191c 0%, #2c3136 100%); 
        color: white; 
        padding: 2.5rem 2rem; 
        border-top-left-radius: 1rem; 
        border-top-right-radius: 1rem;
        position: relative;
        overflow: hidden;
    }
    .admin-header::after {
        content: ""; position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: #ffc107;
    }
    .section-badge { 
        display: inline-block; background: #16191c; color: #ffc107; padding: 0.5rem 1.2rem; 
        border-radius: 50rem; font-size: 0.85rem; font-weight: 700; letter-spacing: 1px; 
        text-transform: uppercase; margin-bottom: 1.5rem; box-shadow: 0 4px 10px rgba(0,0,0,0.15); 
    }
    .custom-input { 
        background-color: #f4f6f9; border: 1px solid #dee2e6; border-radius: 0.6rem; padding: 0.75rem 1rem; transition: all 0.3s; color: #212529; font-weight: 500;
    }
    .custom-input:focus { 
        background-color: #ffffff; border-color: #ffc107; box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.2); outline: none;
    }

    .btn-gradient { 
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #000; border: none; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; transition: 0.3s;
    }
    .btn-gradient:hover { 
        background: linear-gradient(135deg, #ff9800 0%, #e68a00 100%); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(255, 152, 0, 0.4); 
    }
  </style>
</head>

<body class="bg-light pb-5">

  <?php include('../layouts/navbar.php'); ?>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-9 col-xl-8">

        <div class="admin-card">
          
          <div class="admin-header d-flex justify-content-between align-items-center">
            <div>
               <span class="badge bg-warning text-dark mb-2 px-2 py-1"><i class="bi bi-person-lines-fill"></i> Controle de Perfis</span>
               <h3 class="mb-0 fw-bolder text-white">Editar Perfil de Usuário</h3>
               <p class="mb-0 text-white-50 mt-1">Atualize as informações cadastrais e permissões do membro.</p>
            </div>
            <a href="usuario_list.php" class="btn btn-outline-light rounded-pill px-4 fw-bold transition"><i class="bi bi-arrow-left me-1"></i> Voltar</a>
          </div>

          <div class="card-body p-4 p-md-5">
            <form action="../../controllers/usuarioControllers.php" method="POST">

              <input type="hidden" name="usuario_id" value="<?= $user['id']; ?>">

              <!-- DADOS CADASTRAIS -->
              <div class="section-badge"><i class="bi bi-person-badge me-2"></i>1. Dados Cadastrais</div>

              <div class="row g-4 mb-5">
                <div class="col-md-12">
                  <label class="form-label fw-bold text-dark">Nome Completo</label>
                  <input type="text" name="nome" value="<?= htmlspecialchars($user['nome']); ?>" class="custom-input w-100" required>
                </div>
                
                <div class="col-md-7">
                  <label class="form-label fw-bold text-dark">Endereço de E-mail</label>
                  <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" class="custom-input w-100" required>
                </div>
                <div class="col-md-5">
                  <label class="form-label fw-bold text-dark">Data de Nascimento</label>
                  <input type="date" name="data_nascimento" value="<?= date('Y-m-d', strtotime($user['data_nascimento'])); ?>" class="custom-input w-100" required>
                </div>
              </div>

              <!-- CONFIGURAÇÕES DE ACESSO -->
              <div class="section-badge"><i class="bi bi-shield-lock-fill me-2"></i>2. Configurações de Acesso</div>

              <div class="row g-4 mb-4 bg-light p-3 rounded-4 border border-light-subtle shadow-sm">
                  <div class="col-md-6">
                    <label class="form-label fw-bold text-dark mb-2">Cargo / Nível de Acesso</label>
                    <select name="cargo" class="custom-input w-100 fw-bold">
                      <option value="usuario" <?= $user['cargo'] == 'usuario' ? 'selected' : '' ?>>👤 Usuário Padrão</option>
                      <option value="adm" <?= $user['cargo'] == 'adm' ? 'selected' : '' ?>>🛠️ Administrador</option>
                    </select>
                  </div>
                  
                  <div class="col-md-6">
                    <label class="form-label fw-bold text-dark mb-2">Redefinir Senha</label>
                    <input type="password" name="senha" class="custom-input w-100" placeholder="Deixe em branco para não alterar">
                  </div>
                  
                  <div class="col-12 mt-2">
                      <small class="text-muted"><i class="bi bi-info-circle"></i> Caso preencha o campo de senha, ela será criptografada automaticamente antes de salvar na base de dados.</small>
                  </div>
              </div>
              
              <div class="d-flex justify-content-end gap-3 border-top border-2 pt-4 mt-5">
                <button type="submit" name="update_usuario" class="btn btn-gradient btn-lg px-5 py-3 rounded-pill shadow-sm">
                  <i class="bi bi-cloud-arrow-up-fill me-2 fs-5"></i> Salvar Alterações
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
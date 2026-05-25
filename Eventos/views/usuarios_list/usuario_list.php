<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['cargo'] !== 'adm') {
    $_SESSION['mensagem'] = "Acesso negado. Área restrita a administradores.";
    header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/public/dashUser.php');
    exit;
}

require __DIR__ . '/../../config/conexao.php';
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciamento de Usuários - FSA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #eef2f5; color: #333; }
        .main-row { transition: background-color 0.2s; }
        .main-row:hover { background-color: #f1f4f8 !important; }
        .table-custom th { text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; font-weight: 700; }
        .badge-role { font-size: 0.8rem; padding: 0.4rem 0.8rem; }
    </style>
</head>

<body class="bg-light pb-5">

    <?php include('../layouts/navbar.php'); ?>

    <div class="container mt-4">
        <?php include('../layouts/mensagem.php'); ?>

        <div class="d-flex justify-content-between align-items-center mb-4 mt-3 pb-3 border-bottom border-secondary border-opacity-25">
            <h4 class="mb-0 fw-bold text-dark d-flex align-items-center">
                <i class="bi bi-people-fill fs-2 me-3 text-warning"></i> 
                Controle de Usuários
                <span class="ms-3 badge bg-dark text-warning fs-6 fw-bold shadow-sm">Administração de Perfis</span>
            </h4>
            <a href="../auth/register.php" class="btn btn-warning rounded-pill shadow-sm fw-bold px-4 border text-dark">
                <i class="bi bi-person-plus-fill me-1"></i> Cadastrar Novo Usuário
            </a>
        </div>

        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-dark p-3 text-center border-0">
                 <span class="text-white opacity-75 fst-italic tracking-wider small">Visualização completa de membros e credenciais ativas na base de dados</span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-custom">
                        <thead class="table-dark text-white border-bottom">
                            <tr>
                                <th class="ps-4 py-3">ID</th>
                                <th>Nome do Usuário</th>
                                <th>E-mail</th>
                                <th>Cargo</th>
                                <th class="text-center pe-4" width="20%">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM usuarios ORDER BY id DESC";
                            $result = mysqli_query($conexao, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while ($user = mysqli_fetch_assoc($result)) {
                                    $isAdm = ($user['cargo'] == 'adm');
                                    $cargoBadge = $isAdm ? 'bg-danger bg-opacity-10 text-danger border border-danger' : 'bg-primary bg-opacity-10 text-primary border border-primary';
                            ?>
                                    <tr class="main-row">
                                        <td class="ps-4 text-muted fw-semibold">
                                            <span class="border rounded px-2 bg-light shadow-sm">#<?= $user['id'] ?></span>
                                        </td>
                                        
                                        <td class="fw-bold text-dark">
                                            <i class="bi bi-person-fill text-secondary opacity-50 me-2"></i><?= htmlspecialchars($user['nome']) ?>
                                        </td>
                                        
                                        <td class="text-secondary">
                                            <i class="bi bi-envelope-fill text-muted opacity-50 me-1"></i><?= htmlspecialchars($user['email']) ?>
                                        </td>
                                        
                                        <td>
                                            <span class="badge rounded-pill text-uppercase badge-role <?= $cargoBadge ?>">
                                                <i class="bi <?= $isAdm ? 'bi-shield-lock-fill' : 'bi-person-fill' ?> me-1"></i><?= $user['cargo'] ?>
                                            </span>
                                        </td>
                                        
                                        <td class="text-center pe-4">
                                            <div class="d-inline-flex gap-2">
                                                <a href="usuario_edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning fw-bold text-dark px-3 rounded-pill shadow-sm">
                                                    <i class="bi bi-pencil-square me-1"></i> Editar
                                                </a>

                                                <form action="../../controllers/usuarioControllers.php" method="POST" class="m-0 p-0" onsubmit="return confirm('⚠️ ATENÇÃO: Deseja realmente excluir permanentemente a conta de [<?= htmlspecialchars($user['nome']) ?>]?\nEsta ação não poderá ser desfeita!')">
                                                    <button type="submit" name="delete_usuario" value="<?= $user['id'] ?>" class="btn btn-sm btn-danger fw-bold px-3 rounded-pill shadow-sm">
                                                        <i class="bi bi-trash3-fill me-1"></i> Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-emoji-frown fs-3 d-block mb-2"></i><h5>Nenhum usuário foi cadastrado no sistema ainda.</h5></td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
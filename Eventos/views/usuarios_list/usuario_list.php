<?php
// 1. Inicia a sessão para verificar se o usuário está logado
session_start();

// 2. Proteção de acesso: se não estiver logado, volta para o login
if (!isset($_SESSION['usuario'])) {
    $_SESSION['mensagem'] = "Acesso negado. Por favor, faça login.";
    header('Location: ../auth/login.php');
    exit;
}

// 3. Importa a conexão com o banco de dados (ajustado para o caminho do seu grupo)
require __DIR__ . '/../../config/conexao.php';

?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciamento de Usuários</title>
    <!-- CSS do Bootstrap (mesmo que o grupo usa) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Importa a Navbar padrão do projeto -->
    <?php include('../layouts/navbar.php'); ?>

    <div class="container mt-4">
        <!-- Exibe mensagens de sucesso ou erro vindas do controlador -->
        <?php include('../layouts/mensagem.php'); ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Olá, <?= htmlspecialchars($_SESSION['nome']); ?>! <span class="badge bg-dark fs-6">Administrador</span></h4>
            <a href='../../logout.php' class="btn btn-outline-danger shadow-sm"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white p-3">
                <h5 class="mb-0">Painel de Gerenciamento de Usuários</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Nome do Usuário</th>
                                <th>E-mail</th>
                                <th>Cargo</th>
                                <th class="text-center pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // 4. Busca os usuários na tabela oficial
                            $sql = "SELECT * FROM usuarios";
                            $result = mysqli_query($conexao, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while ($user = mysqli_fetch_assoc($result)) {
                            ?>
                                    <tr>
                                        <td class="ps-4 text-muted">#<?= $user['id'] ?></td>
                                        <td class="fw-bold"><?= $user['nome'] ?></td>
                                        <td><?= $user['email'] ?></td>
                                        <td><span class="badge bg-info text-dark"><?= $user['cargo'] ?></span></td>
                                        <td class="text-center pe-4">

                                            <!-- BOTÃO EDITAR: Leva para a página de edição -->
                                            <a href="usuario_edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning me-2">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>

                                            <!-- BOTÃO EXCLUIR: Formulário POST para o Controller do grupo -->
                                            <form action="../../controllers/usuarioControllers.php" method="POST" class="d-inline">
                                                <button type="submit" name="delete_usuario" value="<?= $user['id'] ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('⚠️ DUPLA CONFIRMAÇÃO:\n\nTem certeza que deseja excluir o usuário [<?= $user['nome'] ?>]?\nEssa ação não pode ser desfeita!')">
                                                    <i class="bi bi-trash"></i> Excluir
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-center py-4 text-muted">Nenhum usuário cadastrado.</td></tr>';
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
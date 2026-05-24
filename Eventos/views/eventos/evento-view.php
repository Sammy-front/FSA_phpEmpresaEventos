


<?php
session_start();
require __DIR__ . '/../../config/conexao.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Painel Administrativo - Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .accordion-icon { transition: transform 0.3s ease; }
        tr[aria-expanded="true"] .accordion-icon { transform: rotate(180deg); }
        .main-row:hover { background-color: #f8f9fa; cursor: pointer; }
    </style>
</head>
<body class="bg-light">
    <?php include('../layouts/navbar.php'); ?>
    
    <div class="container mt-4">
        <?php include('../layouts/mensagem.php'); ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Olá, <?= htmlspecialchars($_SESSION['nome']); ?>! <span class="badge bg-dark fs-6">Administrador</span></h4>
            <a href="evento-create.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-circle"></i> Adicionar Evento</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white p-3">
                <h5 class="mb-0">Gerenciar Eventos Cadastrados</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nome do Evento</th>
                                <th>Local</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM eventos ORDER BY id DESC";
                            $result = mysqli_query($conexao, $sql);
                            
                            while($evento = mysqli_fetch_assoc($result)) {
                                $collapseId = "collapseAdm" . $evento['id'];
                                $corStatus = ($evento['status_evento'] == 'fechado') ? 'bg-danger' : 'bg-success';
                            ?>
                            
                            <!-- LINHA PRINCIPAL -->
                            <tr class="main-row" data-bs-toggle="collapse" data-bs-target="#<?=$collapseId?>">
                                <td class="ps-4 fw-bold text-primary"><?=$evento['nome']?></td>
                                <td><?=$evento['localidade']?></td>
                                <td><?=date('d/m/Y', strtotime($evento['data_evento']))?></td>
                                <td><span class="badge <?=$corStatus?> text-capitalize"><?=$evento['status_evento']?></span></td>
                                <td class="text-center pe-4">
                                    <i class="bi bi-chevron-down accordion-icon text-secondary"></i>
                                </td>
                            </tr>
                            
                            <!-- DETALHES E BOTÕES -->
                            <tr>
                                <td colspan="5" class="p-0 border-0">
                                    <div class="collapse" id="<?=$collapseId?>">
                                        <div class="card card-body m-3 shadow-sm border-start border-primary border-4 bg-white">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h6 class="text-uppercase text-muted fw-bold small mb-2">Descrição e Info</h6>
                                                    <p class="mb-2 text-dark small"><?=$evento['descricao']?></p>
                                                    <div class="d-flex gap-3 small text-muted">
                                                        <span><i class="bi bi-clock"></i> <?=$evento['horario']?></span>
                                                        <span><i class="bi bi-people"></i> <?=$evento['capacidade']?> vagas</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 d-flex gap-2 justify-content-end">
                                                    <!-- BOTÃO EDITAR -->
                                                    <a href="evento-edit.php?id=<?=$evento['id']?>" class="btn btn-warning">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </a>
                                                    
                                                    <!-- BOTÃO EXCLUIR -->
                                                    <form action="../../controllers/eventoControllers.php" method="POST">
                                                        <button type="submit" name="delete_evento" value="<?=$evento['id']?>" 
                                                                class="btn btn-danger" 
                                                                onclick="return confirm('⚠️ TEM CERTEZA?\nIsso apagará o evento permanentemente!')">
                                                            <i class="bi bi-trash"></i> Excluir
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


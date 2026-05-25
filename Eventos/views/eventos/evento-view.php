<?php
session_start();
require __DIR__ . '/../../config/conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['cargo'] !== 'adm') {
    $_SESSION['mensagem'] = "Acesso negado. Restrito a administradores.";
    header('Location: ../auth/login.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Painel de Controle - FSA Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #eef2f5;
            color: #333;
        }

        .accordion-icon {
            transition: transform 0.3s;
        }

        tr[aria-expanded="true"] .accordion-icon {
            transform: rotate(180deg);
            color: #0d6efd !important;
        }

        .main-row {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .main-row:hover {
            background-color: #f1f4f8 !important;
        }

        .table-custom th {
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
    </style>
</head>

<body class="bg-light pb-5 mb-5">

    <?php include('../layouts/navbar.php'); ?>

    <div class="container mt-4 pb-3">
        <?php include('../layouts/mensagem.php'); ?>

        <div class="d-flex justify-content-between align-items-center mb-4 mt-3 pb-3 border-bottom border-secondary border-opacity-25">
            <h4 class="mb-0 fw-bold text-dark d-flex align-items-center">
                <i class="bi bi-layout-text-window-reverse fs-2 me-3 text-warning"></i>
                Painel de Controle
                <span class="ms-3 badge bg-dark text-warning fs-6 fw-bold shadow-sm">Acesso Administrativo</span>
            </h4>
            <a href="evento-create.php" class="btn btn-warning rounded-pill shadow-sm fw-bold px-4 border text-dark">
                <i class="bi bi-plus-circle-fill me-1"></i> Adicionar Novo Evento
            </a>
        </div>

        <div class="card shadow border-0 rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-dark p-3 text-center border-0">
                <span class="text-white opacity-75 fst-italic tracking-wider small">Clique sobre a linha do evento para expandir detalhes, editar ou excluir</span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom m-0 align-middle">

                        <thead class="table-dark text-white border-bottom">
                            <tr>
                                <th class="ps-4 py-3">Título do Evento</th>
                                <th>Localidade</th>
                                <th>Data do Show</th>
                                <th width="15%">Status Vendas</th>
                                <th class="text-center" width="8%">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $result = mysqli_query($conexao, "SELECT * FROM eventos ORDER BY id DESC");

                            while ($evento = mysqli_fetch_assoc($result)) {
                                $dropId = "collapseAdm" . $evento['id'];
                                $isFechado = ($evento['status_evento'] == 'fechado');
                                $badgeColor = $isFechado ? 'bg-danger bg-opacity-10 text-danger border border-danger' : 'bg-success bg-opacity-10 text-success border border-success';
                            ?>

                                <tr class="main-row" data-bs-toggle="collapse" data-bs-target="#<?= $dropId ?>" style="cursor: pointer;">
                                    <td class="ps-4 text-dark fw-bold fs-6 text-truncate" style="max-width:320px;">
                                        <i class="bi bi-tag-fill text-secondary opacity-50 me-2"></i><?= htmlspecialchars($evento['nome']) ?>
                                    </td>

                                    <td>
                                        <span class="text-muted fw-semibold"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($evento['localidade']) ?></span>
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2.5 py-1.5"><?= date('d/m/Y', strtotime($evento['data_evento'])) ?></span>
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill px-3 py-1.5 <?= $badgeColor ?> text-capitalize">
                                            <i class="bi <?= $isFechado ? 'bi-lock-fill' : 'bi-door-open-fill' ?> me-1"></i><?= $evento['status_evento'] ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <i class="bi bi-chevron-down accordion-icon text-dark opacity-50 fs-5"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="5" class="p-0 border-0">
                                        <div class="collapse" id="<?= $dropId ?>">
                                            <div class="card card-body m-3 shadow-sm border-start border-primary border-4 bg-white p-4">
                                                <div class="row align-items-center">

                                                    <div class="col-md-8">
                                                        <div class="d-flex align-items-center gap-2 mb-2">
                                                            <span class="badge bg-dark">ID do Evento: #<?= $evento['id'] ?></span>
                                                            <span class="text-muted small"><i class="bi bi-clock-history me-1"></i> Última atualização</span>
                                                        </div>
                                                        <h5 class="fw-bold text-dark mb-2">Descrição da Atração</h5>
                                                        <p class="text-secondary mb-3 small lh-base">
                                                            <?= !empty($evento['descricao']) ? nl2br(htmlspecialchars($evento['descricao'])) : '<em>Nenhuma descrição detalhada disponível para este evento.</em>' ?>
                                                        </p>
                                                        <div class="d-flex gap-4 text-muted small border-top pt-2">
                                                            <span><i class="bi bi-clock-fill text-primary"></i> <strong>Abertura:</strong> <?= htmlspecialchars($evento['horario']) ?></span>
                                                            <span><i class="bi bi-people-fill text-primary"></i> <strong>Capacidade Máxima:</strong> <?= $evento['capacidade'] ?> pessoas</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 text-md-end mt-3 mt-md-0 border-start ps-md-4">
                                                        <h6 class="fw-bold text-dark mb-3 text-start text-md-end"><i class="bi bi-shield-lock-fill text-warning me-1"></i>Ações de Gestão</h6>
                                                        <div class="d-grid gap-2">
                                                            <a href="evento-edit.php?id=<?= $evento['id'] ?>" class="btn btn-warning fw-bold text-dark shadow-sm">
                                                                <i class="bi bi-pencil-square me-1"></i> Editar Dados
                                                            </a>
                                                            <form action="../../controllers/eventoControllers.php" method="POST" onsubmit="return confirm('⚠️ ATENÇÃO: Tem certeza que deseja apagar este evento permanentemente?')">
                                                                <button type="submit" name="delete_evento" value="<?= $evento['id'] ?>" class="btn btn-danger w-100 fw-bold shadow-sm">
                                                                    <i class="bi bi-trash3-fill me-1"></i> Excluir Evento
                                                                </button>
                                                            </form>
                                                        </div>
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
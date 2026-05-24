<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    $_SESSION['mensagem'] = "Por favor, faça login para ver seus ingressos.";
    header('Location: ../views/auth/login.php');
    exit;
}

require __DIR__ . '/../../config/conexao.php';

$email_logado = mysqli_real_escape_string($conexao, $_SESSION['usuario']);
$query_user = mysqli_query($conexao, "SELECT id FROM usuarios WHERE email = '$email_logado' LIMIT 1");
if (mysqli_num_rows($query_user) == 0) {
    die("Falha crítica ao buscar a Sessão deste Perfil.");
}
$id_usuario = mysqli_fetch_assoc($query_user)['id'];
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minha Carteira Digital - FSA Eventos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .ticket-card {
            background: #fff;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
            transition: 0.3s;
        }

        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .ticket-header {
            background-color: #212529;
            color: white;
            padding: 15px;
            border-bottom: 3px dashed #6c757d;
        }

        .ticket-body {
            padding: 20px;
        }

        .ticket-footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .barcode-fake {
            font-family: 'Libre Barcode 39 Extended Text', 'Courier New', monospace;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #343a40;
        }

        .stamp {
            position: absolute;
            top: 30%;
            left: 10%;
            transform: rotate(-15deg);
            opacity: 0.2;
            pointer-events: none;
        }
    </style>
</head>

<body class="bg-light">
    <?php include('../../views/layouts/navbar.php'); ?>

    <div class="container mt-4 mb-5">
        <?php include('../../views/layouts/mensagem.php'); ?>

        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2><i class="bi bi-wallet2 text-primary"></i> Meus Ingressos</h2>
            <a href="../../public/dashUser.php" class="btn btn-outline-dark shadow-sm"><i class="bi bi-calendar2-event"></i> Explorar Novos Eventos</a>
        </div>

        <div class="row g-4">
            <?php
            $sql = "SELECT 
                    i.id AS numero_ingresso,
                    i.status_inscricao,
                    i.data_inscricao,
                    t.nome_ingresso AS setor_ingresso,
                    e.nome AS nome_evento,
                    e.data_evento,
                    e.horario,
                    e.localidade,
                    c.data_entrada AS feito_checkin
                  FROM inscricoes i
                  INNER JOIN eventos e ON i.id_evento = e.id
                  INNER JOIN tipos_ingressos t ON i.id_tipo_ingresso = t.id
                  LEFT JOIN check_ins c ON c.id_inscricao = i.id
                  WHERE i.id_usuario = '$id_usuario'
                  ORDER BY i.id DESC";
            $resultado_ingressos = mysqli_query($conexao, $sql);

            if (mysqli_num_rows($resultado_ingressos) > 0) {
                while ($ingresso = mysqli_fetch_assoc($resultado_ingressos)) {
                    $status = $ingresso['status_inscricao'];
                    $checkin = $ingresso['feito_checkin'];
            ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="ticket-card h-100 d-flex flex-column shadow-sm">

                            <div class="ticket-header">
                                <h5 class="m-0 text-uppercase text-truncate" title="<?= $ingresso['nome_evento'] ?>">
                                    <?= $ingresso['nome_evento'] ?>
                                </h5>
                            </div>

                            <div class="ticket-body flex-grow-1 position-relative">

                                <?php if ($status == 'cancelada'): ?>
                                    <h1 class="text-danger stamp fw-bold display-4">CANCELADO</h1>
                                <?php elseif (!empty($checkin)): ?>
                                    <h1 class="text-success stamp fw-bold display-4">UTILIZADO</h1>
                                <?php endif; ?>

                                <p class="mb-1 text-muted small text-uppercase fw-bold"><i class="bi bi-tag-fill"></i> Categoria Adquirida:</p>
                                <p class="fs-5 text-primary fw-bold"><?= $ingresso['setor_ingresso'] ?></p>

                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><i class="bi bi-calendar3"></i> <b>Data:</b> <?= date('d/m/Y', strtotime($ingresso['data_evento'])) ?> às <?= $ingresso['horario'] ?></li>
                                    <li class="mb-2"><i class="bi bi-geo-alt-fill"></i> <b>Local:</b> <?= $ingresso['localidade'] ?></li>

                                    <li class="mt-4 pt-3 border-top">
                                        <b class="text-dark">Situação Financeira:</b>
                                        <?php if ($status == 'paga'): ?>
                                            <span class="badge bg-success ms-1"><i class="bi bi-check-circle"></i> APROVADO</span>
                                        <?php elseif ($status == 'cancelada'): ?>
                                            <span class="badge bg-danger ms-1">Recusado/Cancelado</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning ms-1">Processando..</span>
                                        <?php endif; ?>
                                    </li>

                                    <li class="mt-2">
                                        <b class="text-dark">Status Catraca:</b>
                                        <?php if (!empty($checkin)): ?>
                                            <span class="text-success fw-bold d-block mt-1">
                                                <i class="bi bi-door-open-fill"></i> Bipado Entrada: <br> <small class="text-secondary">(<?= date('d/m/Y \à\s H:i:s', strtotime($checkin)) ?>)</small>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted d-block mt-1"><i class="bi bi-dash-circle"></i> Você ainda não entrou no evento.</span>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>

                            <div class="ticket-footer d-flex flex-column align-items-center">
                                <small class="text-muted text-uppercase fw-bold mb-1">Apresente sua Identificação</small>
                                <div class="barcode-fake user-select-all px-2 border border-dark rounded">#<?= str_pad($ingresso['numero_ingresso'], 6, '0', STR_PAD_LEFT); ?></div>
                                <small class="text-secondary mt-1">Data da compra: <?= date('d/m/y \- H:i', strtotime($ingresso['data_inscricao'])) ?></small>
                            </div>
                        </div>
                    </div>

                <?php
                }
            } else {
                ?>
                <div class="col-12 mt-5 text-center p-5 bg-white shadow-sm border rounded">
                    <i class="bi bi-ticket-perforated-fill text-muted mb-3 d-block" style="font-size: 4rem;"></i>
                    <h4 class="text-dark fw-light">Sua carteira está vazia!</h4>
                    <p class="text-muted">Você ainda não se inscreveu ou garantiu sua presença em nenhum evento do sistema.</p>
                    <a href="../../public/dashUser.php" class="btn btn-primary mt-3 px-5 py-2">Explorar Festas / Palestras</a>
                </div>
            <?php } ?>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
// Arquivo: views/ingressos/ingressos.php
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
        body {
            background-color: #eef2f5;
            color: #333;
        }
        .ticket-card {
            background: #fff;
            border: none;
            border-radius: 1.25rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }
        .ticket-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        .ticket-header {
            background: linear-gradient(135deg, #16191c 0%, #2c3136 100%);
            color: white;
            padding: 20px;
            position: relative;
            border-bottom: 3px dashed #eef2f5;
        }
        .ticket-header::before,
        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            width: 20px;
            height: 20px;
            background-color: #eef2f5;
            border-radius: 50%;
            z-index: 10;
        }
        .ticket-header::before {
            left: -10px;
        }
        .ticket-header::after {
            right: -10px;
        }
        .ticket-body {
            padding: 25px;
        }
        .ticket-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        .barcode-fake {
            font-family: monospace;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 4px;
            color: #212529;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
        }
        .stamp {
            position: absolute;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-12deg);
            font-weight: 900;
            font-size: 2.2rem;
            letter-spacing: 2px;
            border: 4px double;
            padding: 5px 15px;
            border-radius: 8px;
            opacity: 0.15;
            pointer-events: none;
        }
        .stamp-utilized {
            color: #198754;
            border-color: #198754;
        }
        .stamp-cancelled {
            color: #dc3545;
            border-color: #dc3545;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #000;
            border: none;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: 0.3s;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #ff9800 0%, #e68a00 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 152, 0, 0.4);
            color: #000;
        }
    </style>
</head>

<body class="bg-light">
    <?php include('../../views/layouts/navbar.php'); ?>

    <div class="container mt-4 mb-5">
        <?php include('../../views/layouts/mensagem.php'); ?>

        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary border-opacity-25 pb-3">
            <h2 class="fw-bold text-dark m-0"><i class="bi bi-wallet2 text-warning me-2"></i> Meus Ingressos</h2>
            <a href="../../public/dashUser.php" class="btn btn-outline-dark shadow-sm rounded-pill fw-bold px-3"><i class="bi bi-calendar2-event me-1"></i> Explorar Novos Eventos</a>
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
                                <h5 class="m-0 text-uppercase text-truncate fw-bold" title="<?= $ingresso['nome_evento'] ?>">
                                    <?= $ingresso['nome_evento'] ?>
                                </h5>
                            </div>

                            <div class="ticket-body flex-grow-1 position-relative">

                                <?php if ($status == 'cancelada'): ?>
                                    <div class="stamp stamp-cancelled">CANCELADO</div>
                                <?php elseif (!empty($checkin)): ?>
                                    <div class="stamp stamp-utilized">UTILIZADO</div>
                                <?php endif; ?>

                                <p class="mb-1 text-muted small text-uppercase fw-bold"><i class="bi bi-tag-fill"></i> Categoria Adquirida:</p>
                                <p class="fs-5 text-primary fw-bold"><?= $ingresso['setor_ingresso'] ?></p>

                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2 text-dark"><i class="bi bi-calendar3 text-muted me-1"></i> <b>Data:</b> <?= date('d/m/Y', strtotime($ingresso['data_evento'])) ?> às <?= $ingresso['horario'] ?></li>
                                    <li class="mb-2 text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i> <b>Local:</b> <?= $ingresso['localidade'] ?></li>

                                    <li class="mt-4 pt-3 border-top">
                                        <b class="text-dark">Situação Financeira:</b>
                                        <?php if ($status == 'paga'): ?>
                                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success px-3 ms-1"><i class="bi bi-check-circle"></i> APROVADO</span>
                                        <?php elseif ($status == 'cancelada'): ?>
                                            <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger px-3 ms-1">Cancelado</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning px-3 ms-1">Processando..</span>
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

                            <!-- Rodapé do Bilhete com Identificação Serial -->
                            <div class="ticket-footer d-flex flex-column align-items-center">
                                <small class="text-muted text-uppercase fw-bold mb-1">Apresente sua Identificação</small>
                                <div class="barcode-fake user-select-all px-3 py-1">#<?= str_pad($ingresso['numero_ingresso'], 6, '0', STR_PAD_LEFT); ?></div>
                                <small class="text-secondary mt-1">Data da compra: <?= date('d/m/y \- H:i', strtotime($ingresso['data_inscricao'])) ?></small>
                            </div>
                        </div>
                    </div>

                <?php
                }
            } else {
                ?>
                <div class="col-12 mt-5 text-center p-5 bg-white shadow border-0 rounded-4">
                    <i class="bi bi-ticket-perforated-fill text-muted mb-3 d-block" style="font-size: 4.5rem;"></i>
                    <h4 class="text-dark fw-bold mb-2">Sua carteira está vazia!</h4>
                    <p class="text-muted mb-4">Você ainda não se inscreveu ou garantiu sua presença em nenhum evento do sistema.</p>
                    <a href="../../public/dashUser.php" class="btn btn-gradient btn-lg px-5 py-3 rounded-pill shadow-sm"><i class="bi bi-compass-fill me-1"></i> Explorar Eventos</a>
                </div>
            <?php } ?>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
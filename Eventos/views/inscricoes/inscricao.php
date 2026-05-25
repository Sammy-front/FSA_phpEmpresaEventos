<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    $_SESSION['mensagem'] = 'Você precisa estar logado para comprar/se inscrever.';
    header('Location: ../auth/login.php');
    exit;
}

require __DIR__ . '/../../config/conexao.php';

if (!isset($_GET['id'])) {
    header('Location: ../../public/dashUser.php');
    exit;
}

$id_evento = mysqli_real_escape_string($conexao, $_GET['id']);
$dadosDoEvento = mysqli_query($conexao, "SELECT * FROM eventos WHERE id='$id_evento' LIMIT 1");

if (mysqli_num_rows($dadosDoEvento) == 0) {
    die("Evento não encontrado no catálogo.");
}

$evento = mysqli_fetch_array($dadosDoEvento);
$capacidade_maxima = $evento['capacidade'];
$query_vendidos = mysqli_query($conexao, "SELECT COUNT(id) as total FROM inscricoes WHERE id_evento='$id_evento' AND status_inscricao != 'cancelada'");
$dados_vendidos = mysqli_fetch_assoc($query_vendidos);
$ingressos_vendidos = $dados_vendidos['total'];
$restantes = $capacidade_maxima - $ingressos_vendidos;
if ($restantes < 0) $restantes = 0;
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Garantir sua Entrada - FSA Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #eef2f5;
            color: #333;
        }

        .purchase-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            background: #ffffff;
            overflow: hidden;
        }

        .custom-buy-box {
            background: #ffffff;
            border: none;
            border-radius: 1rem;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            border-top: 4px solid #ffc107;
        }

        .custom-input {
            background-color: #f4f6f9;
            border: 1px solid #dee2e6;
            border-radius: 0.6rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
            color: #212529;
            font-weight: 500;
        }

        .custom-input:focus {
            background-color: #ffffff;
            border-color: #ffc107;
            box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.2);
            outline: none;
        }

        .badge-status {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
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
    <?php include('../layouts/navbar.php'); ?>

    <div class="container mt-5">
        <?php include('../layouts/mensagem.php'); ?>

        <div class="row g-4 align-items-center justify-content-center">

            <div class="col-md-5">
                <div class="purchase-card">
                    <img src="https://via.placeholder.com/600x200/16191c/FFF?text=<?php echo urlencode($evento['nome']); ?>" class="card-img-top" alt="...">
                    <div class="card-body mt-2 text-center p-4">
                        <h2 class="card-title text-uppercase fw-bold text-dark mb-3"><?= $evento['nome'] ?></h2>
                        <h6 class="text-secondary mb-4"><i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($evento['localidade']) ?></h6>

                        <hr class="opacity-25 mb-4">

                        <h5 class="fw-medium text-muted small text-uppercase">Agendado Para:</h5>
                        <div class="display-6 fw-bold text-primary mt-1 mb-4">
                            <i class="bi bi-calendar3"></i>
                            <?= date('d/m/Y', strtotime($evento['data_evento'])); ?>
                        </div>

                        <?php if ($restantes > 0 && $restantes <= 20): ?>
                            <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning badge-status"><i class="bi bi-fire me-1"></i>Corra! Restam apenas <?= $restantes ?> vagas</span>
                        <?php elseif ($restantes == 0): ?>
                            <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger badge-status w-100"><i class="bi bi-slash-circle me-1"></i>ESGOTADO! Não há mais vagas</span>
                        <?php else: ?>
                            <span class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info badge-status"><i class="bi bi-door-open-fill me-1"></i>Lotação liberada! Garanta o seu</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- FORMULÁRIO DE COMPRA -->
            <div class="col-md-5">
                <div class="custom-buy-box">
                    <h4 class="fw-bold text-dark mb-2">Fazer Inscrição</h4>
                    <p class="text-muted text-sm mb-4">Olá, <strong class="text-dark"><?= htmlspecialchars($_SESSION['nome']); ?></strong>! Escolha sua categoria de acesso e a quantidade desejada de convites.</p>

                    <form action="../../controllers/inscricaoControllers.php" method="POST">
                        <input type="hidden" name="id_evento" value="<?= $evento['id']; ?>">

                        <!-- TIPO DE INGRESSO -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2">Categoria de Acesso <span class="text-danger">*</span></label>
                            <select class="custom-input w-100" id="ticket" name="id_tipo_ingresso" required <?= ($restantes == 0) ? 'disabled' : '' ?>>
                                <option selected value="" disabled>Selecione seu setor...</option>
                                <?php
                                $tiposQuery = "SELECT id, nome_ingresso, valor FROM tipos_ingressos WHERE id_evento='$id_evento'";
                                $get_tipos = mysqli_query($conexao, $tiposQuery);

                                if (mysqli_num_rows($get_tipos) > 0) {
                                    while ($ticket = mysqli_fetch_assoc($get_tipos)) {
                                        $precoBR = number_format($ticket['valor'], 2, ',', '.');
                                ?>
                                        <option value="<?= $ticket['id'] ?>">🎟️ <?= htmlspecialchars($ticket['nome_ingresso']) ?> — R$: <?= $precoBR; ?> / unid.</option>
                                <?php
                                    }
                                } else {
                                    echo '<option value="" disabled>Lote Pendente. Faltam opções de ticket base!</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="qtd" class="form-label fw-bold text-dark mb-2"><i class="bi bi-123"></i> Quantidade de Convites <span class="text-danger">*</span></label>
                            <input type="number" id="qtd" name="quantidade" class="custom-input w-100 text-center fw-bold fs-5 shadow-sm" value="1" min="1" max="<?= $restantes ?>" required <?= ($restantes == 0) ? 'disabled' : '' ?>>
                            <small class="text-muted d-block mt-2" style="font-size:0.8rem">Cada ingresso gerará um código individual e exclusivo na sua conta.</small>
                        </div>

                        <div class="d-grid mt-5">
                            <?php if ($restantes > 0): ?>
                                <button type="submit" name="realizar_inscricao" class="btn btn-gradient btn-lg py-3 rounded-3 shadow">
                                    <i class="bi bi-cart-check-fill me-1"></i> Confirmar Inscrição
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-secondary btn-lg py-3 rounded-3 shadow opacity-50" disabled>Lotação Máxima Atingida</button>
                            <?php endif; ?>

                            <a href="../../public/dashUser.php" class="btn btn-outline-secondary mt-3 rounded-3">Voltar ao Painel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
// Arquivo: views/inscricoes/inscricao.php
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

// ==============================================================
// VERIFICAR INGRESSOS RESTANTES PARA AVISAR O USUÁRIO E TRAVAR INPUT
// ==============================================================
$capacidade_maxima = $evento['capacidade'];
$query_vendidos = mysqli_query($conexao, "SELECT COUNT(id) as total FROM inscricoes WHERE id_evento='$id_evento' AND status_inscricao != 'cancelada'");
$dados_vendidos = mysqli_fetch_assoc($query_vendidos);
$ingressos_vendidos = $dados_vendidos['total'];

$restantes = $capacidade_maxima - $ingressos_vendidos;

// Se for menor que 0, garante que mostre 0
if ($restantes < 0) $restantes = 0;
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Garantir sua Entrada - Comprar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .custom-buy-box {
            background: #fff;
            border: 1px solid #ddd;
            padding: 25px;
            border-radius: 8px;
        }
    </style>
</head>

<body class="bg-light">
    <?php include('../layouts/navbar.php'); ?>

    <div class="container mt-5">
        <?php include('../layouts/mensagem.php'); ?>

        <div class="row g-4 align-items-center justify-content-center">

            <!-- LADO ESQUERDO DA COMPRA - RESUMO DO EVENTO -->
            <div class="col-md-5">
                <div class="card shadow border-0">
                    <img src="https://via.placeholder.com/600x200/0d6efd/FFF?text=<?php echo urlencode($evento['nome']); ?>" class="card-img-top" alt="...">
                    <div class="card-body mt-2 text-center">
                        <h2 class="card-title text-uppercase"><?= $evento['nome'] ?></h2>
                        <h6 class="text-secondary"><i class="bi bi-geo-alt-fill text-danger"></i> <?= $evento['localidade'] ?></h6>
                        <hr class="mt-3">
                        <h5 class="fw-light">Agendado Para:</h5>
                        <div class="display-6 fw-bold text-primary mt-1 mb-2">
                            <i class="bi bi-calendar3"></i>
                            <?= date('d/m/Y', strtotime($evento['data_evento'])); ?>
                        </div>

                        <!-- ALERTA DE LOTAÇÃO DA FESTA -->
                        <?php if ($restantes > 0 && $restantes <= 20): ?>
                            <span class="badge bg-warning text-dark p-2">Corra! Restam apenas <?= $restantes ?> vagas.</span>
                        <?php elseif ($restantes == 0): ?>
                            <span class="badge bg-danger p-2 w-100 fs-6">ESGOTADO! Não há mais vagas.</span>
                        <?php else: ?>
                            <span class="badge bg-info p-2 text-dark">Lotação liberada! Compre agora.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- LADO DIREITO - CARRINHO / QUANTIDADE -->
            <div class="col-md-5">
                <div class="custom-buy-box shadow-sm border-top border-primary border-4">
                    <h4>Fazer Inscrição:</h4>
                    <p class="text-muted text-sm">Olá <?= $_SESSION['nome']; ?>, escolha seu setor e quantos ingressos deseja.</p>

                    <form action="../../controllers/inscricaoControllers.php" method="POST">
                        <input type="hidden" name="id_evento" value="<?= $evento['id']; ?>">

                        <div class="form-floating mb-3 mt-3">
                            <select class="form-select border-primary" id="ticket" name="id_tipo_ingresso" required <?= ($restantes == 0) ? 'disabled' : '' ?>>
                                <option selected value="" disabled>Selecione a categoria de acesso:</option>
                                <?php
                                $tiposQuery = "SELECT id, nome_ingresso, valor FROM tipos_ingressos WHERE id_evento='$id_evento'";
                                $get_tipos = mysqli_query($conexao, $tiposQuery);

                                if (mysqli_num_rows($get_tipos) > 0) {
                                    while ($ticket = mysqli_fetch_assoc($get_tipos)) {
                                        $precoBR = number_format($ticket['valor'], 2, ',', '.');
                                ?>
                                        <option value="<?= $ticket['id'] ?>">🎟️ <?= $ticket['nome_ingresso'] ?> — R$: <?= $precoBR; ?> / unid.</option>
                                <?php
                                    }
                                } else {
                                    echo '<option value="" disabled>Lote Pendente. Faltam opções de ticket base!</option>';
                                }
                                ?>
                            </select>
                            <label for="ticket">Tipo de Ingresso</label>
                        </div>

                        <!-- CAMPO NOVO DE QUANTIDADE -->
                        <div class="form-group mb-3 mt-3">
                            <label for="qtd" class="fw-bold"><i class="bi bi-123"></i> Quantidade de Convites:</label>
                            <!-- AQUI NÓS COLOCAMOS MAX PARA BARRAR NO HTML -->
                            <input type="number" id="qtd" name="quantidade" class="form-control form-control-lg border-primary" value="1" min="1" max="<?= $restantes ?>" required <?= ($restantes == 0) ? 'disabled' : '' ?>>
                            <small class="text-muted">A quantidade é vinculada em códigos individuais na sua conta.</small>
                        </div>

                        <div class="d-grid mt-4">
                            <?php if ($restantes > 0): ?>
                                <button type="submit" name="realizar_inscricao" class="btn btn-success btn-lg shadow rounded">
                                    <i class="bi bi-cart-check-fill"></i> Efetuar Pagamento / Confirmar
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-danger btn-lg shadow rounded" disabled>Lotação Máxima Atingida</button>
                            <?php endif; ?>

                            <a href="../../public/dashUser.php" class="btn btn-outline-secondary mt-3">Voltar ao Painel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
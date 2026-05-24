<?php
session_start();
require __DIR__ . '/../../config/conexao.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: evento-view.php');
    exit;
}

$evento_id = mysqli_real_escape_string($conexao, $_GET['id']);
$sql = "SELECT * FROM eventos WHERE id = '$evento_id'";
$query = mysqli_query($conexao, $sql);

if (mysqli_num_rows($query) == 0) {
    echo "Evento não encontrado.";
    exit;
}

$evento = mysqli_fetch_assoc($query);
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Editar Evento - Administração</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">
    <?php include('../layouts/navbar.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-warning p-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square"></i> Editar Dados do Evento</h5>
                        <a href="evento-view.php" class="btn btn-sm btn-dark">Cancelar</a>
                    </div>
                    <div class="card-body p-4">
                        <form action="../../controllers/eventoControllers.php" method="POST">

                            <input type="hidden" name="evento_id" value="<?= $evento['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nome do Evento</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($evento['nome']) ?>" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Descrição</label>
                                <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($evento['descricao']) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Data</label>
                                    <input type="date" name="data_evento" value="<?= $evento['data_evento'] ?>" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Horário</label>
                                    <input type="text" name="horario" value="<?= htmlspecialchars($evento['horario']) ?>" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Localidade</label>
                                <input type="text" name="localidade" value="<?= htmlspecialchars($evento['localidade']) ?>" class="form-control" required>
                            </div>

                            <hr>

                            <div class="row align-items-end">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Capacidade</label>
                                    <input type="number" name="capacidade" value="<?= $evento['capacidade'] ?>" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Status do Evento</label>
                                    <select name="status_evento" class="form-select fw-bold">
                                        <option value="aberto" <?= $evento['status_evento'] == 'aberto' ? 'selected' : '' ?>>Aberto</option>
                                        <option value="fechado" <?= $evento['status_evento'] == 'fechado' ? 'selected' : '' ?>>Fechado</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3 d-grid">
                                    <button type="submit" name="update_evento" class="btn btn-success btn-lg shadow">
                                        <i class="bi bi-save"></i> Atualizar Dados
                                    </button>
                                </div>
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
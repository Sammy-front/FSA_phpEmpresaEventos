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
  <title>Cadastrar Evento - FSA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">
  <?php include('../layouts/navbar.php'); ?>

  <div class="container mt-5 mb-5">
    <div class="row justify-content-center">
      <div class="col-md-9">
        <div class="card shadow border-0">
          <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar-plus"></i> Novo Cadastro de Evento</h5>
            <a href="evento-view.php" class="btn btn-sm btn-outline-light">Voltar para Lista</a>
          </div>
          <div class="card-body p-4">

            <form action="../../controllers/eventoControllers.php" method="POST">

              <div class="mb-3">
                <label class="form-label fw-bold">Título do Evento</label>
                <input type="text" name="nome" class="form-control" placeholder="Nome da Palestra ou Festa" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">Descrição</label>
                <textarea name="descricao" class="form-control" rows="2" placeholder="Breve resumo do evento..."></textarea>
              </div>

              <div class="row mb-3">
                <div class="col-md-4">
                  <label class="form-label fw-bold">Data</label>
                  <input type="date" name="data_evento" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-bold">Horário</label>
                  <input type="text" name="horario" class="form-control" placeholder="Ex: 19h - 22h" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-bold">Capacidade</label>
                  <input type="number" name="capacidade" class="form-control" required>
                </div>
              </div>

              <div class="row mb-4">
                <div class="col-md-8">
                  <label class="form-label fw-bold">Localidade / Endereço</label>
                  <input type="text" name="localidade" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-bold">Status Inicial</label>
                  <select name="status_evento" class="form-select border-primary fw-bold">
                    <option value="aberto">Aberto</option>
                    <option value="fechado">Fechado</option>
                  </select>
                </div>
              </div>

              <!-- CONFIGURAÇÃO DE INGRESSOS (Flexível) -->
              <div class="p-4 bg-light border rounded mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-tags"></i> Ingressos e Valores (R$)</h6>
                <p class="small text-muted">Preencha pelo menos um. Use <strong>0</strong> para Grátis.</p>

                <div class="row g-2 mb-2 fw-bold small text-muted">
                  <div class="col-7">Nome da Categoria (Ex: VIP, Meia, Geral)</div>
                  <div class="col-5">Preço (R$)</div>
                </div>

                <div class="row g-2 mb-3">
                  <div class="col-7"><input type="text" name="ticket_nome[]" class="form-control border-primary" placeholder="Ex: Pista Padrão" required></div>
                  <div class="col-5"><input type="number" step="0.01" name="ticket_valor[]" class="form-control border-primary" placeholder="0.00" required></div>
                </div>

                <hr>
                <p class="small text-muted mb-2">Categorias Opcionais:</p>

                <div class="row g-2 mb-2">
                  <div class="col-7"><input type="text" name="ticket_nome[]" placeholder="Opcional (Ex: VIP)" class="form-control"></div>
                  <div class="col-5"><input type="number" step="0.01" name="ticket_valor[]" class="form-control" placeholder="0.00"></div>
                </div>
                <div class="row g-2 mb-2">
                  <div class="col-7"><input type="text" name="ticket_nome[]" placeholder="Opcional (Ex: Meia)" class="form-control"></div>
                  <div class="col-5"><input type="number" step="0.01" name="ticket_valor[]" class="form-control" placeholder="0.00"></div>
                </div>
              </div>

              <div class="d-grid">
                <button type="submit" name="create_evento" class="btn btn-primary btn-lg shadow">
                  <i class="bi bi-check-circle"></i> Criar Evento Agora
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
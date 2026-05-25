<?php
session_start();
require __DIR__ . '/../../config/conexao.php';
if (!isset($_SESSION['usuario']) || $_SESSION['cargo'] !== 'adm') {
  header('Location: ../auth/login.php');
  exit;
}
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <title>Cadastrar Evento - Administração</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      background-color: #eef2f5;
      color: #333;
    }

    .admin-card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      background: #ffffff;
    }

    .admin-header {
      background: linear-gradient(135deg, #16191c 0%, #2c3136 100%);
      color: white;
      padding: 2.5rem 2rem;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
      position: relative;
      overflow: hidden;
    }

    .admin-header::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: #ffc107;
    }

    .section-badge {
      display: inline-block;
      background: #16191c;
      color: #ffc107;
      padding: 0.5rem 1.2rem;
      border-radius: 50rem;
      font-size: 0.85rem;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
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

    .ticket-box {
      background: #ffffff;
      border: 1px solid #e9ecef;
      border-radius: 0.75rem;
      padding: 1.5rem;
      position: relative;
      transition: 0.3s;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
    }

    .ticket-box:hover {
      border-color: #ced4da;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      transform: translateY(-2px);
    }

    .ticket-indicator-main {
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 6px;
      background-color: #ffc107;
      border-top-left-radius: 0.75rem;
      border-bottom-left-radius: 0.75rem;
    }

    .ticket-indicator-opt {
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 6px;
      background-color: #adb5bd;
      border-top-left-radius: 0.75rem;
      border-bottom-left-radius: 0.75rem;
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
    }
  </style>
</head>

<body>
  <?php include('../layouts/navbar.php'); ?>

  <div class="container mt-5 mb-5 pb-4">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-xl-9">

        <div class="admin-card">

          <div class="admin-header d-flex justify-content-between align-items-center">
            <div>
              <span class="badge bg-warning text-dark mb-2 px-2 py-1"><i class="bi bi-star-fill"></i> Módulo de Criação</span>
              <h3 class="mb-0 fw-bolder text-white">Novo Evento / Show</h3>
              <p class="mb-0 text-white-50 mt-1">Configure os dados abaixo para lançar a atração no sistema.</p>
            </div>
            <a href="evento-view.php" class="btn btn-outline-light rounded-pill px-4 fw-bold transition"><i class="bi bi-x-lg me-1"></i> Cancelar</a>
          </div>

          <div class="card-body p-4 p-md-5">

            <form action="../../controllers/eventoControllers.php" method="POST">

              <div class="section-badge"><i class="bi bi-card-heading me-2"></i>1. Dados Principais</div>

              <div class="row g-4 mb-5">
                <div class="col-md-7">
                  <label class="form-label fw-bold text-dark">Nome do Evento <span class="text-danger">*</span></label>
                  <input type="text" name="nome" class="custom-input w-100" placeholder="Ex: Festival de Inverno 2026" required>
                </div>

                <div class="col-md-5">
                  <label class="form-label fw-bold text-dark">Localidade <span class="text-danger">*</span></label>
                  <input type="text" name="localidade" class="custom-input w-100" placeholder="Ex: Arena Corinthians, SP" required>
                </div>

                <div class="col-12 mt-3">
                  <label class="form-label fw-bold text-dark">Descrição Completa</label>
                  <textarea name="descricao" class="custom-input w-100" rows="4" placeholder="Atrações, horários de abertura, regras do local..."></textarea>
                </div>
              </div>

              <!-- AGENDA E LOGÍSTICA -->
              <div class="section-badge"><i class="bi bi-calendar-range me-2"></i>2. Logística & Status</div>

              <div class="row g-4 mb-5 bg-light p-3 rounded-4 border border-light-subtle shadow-sm">
                <div class="col-md-4">
                  <label class="form-label fw-bold text-dark">Data <span class="text-danger">*</span></label>
                  <input type="date" name="data_evento" class="custom-input w-100" required>
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-bold text-dark">Abertura <span class="text-danger">*</span></label>
                  <input type="text" name="horario" class="custom-input w-100" required>
                </div>

                <div class="col-md-2">
                  <label class="form-label fw-bold text-dark">Capacidade <span class="text-danger">*</span></label>
                  <input type="number" name="capacidade" class="custom-input w-100 text-center" placeholder="0" required>
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-bold text-dark">Visibilidade <span class="text-danger">*</span></label>
                  <select name="status_evento" class="custom-input w-100 fw-bold">
                    <option value="aberto">🟢 Publicado (Aberto)</option>
                    <option value="fechado">🔴 Oculto (Fechado)</option>
                  </select>
                </div>
              </div>

              <!-- INGRESSOS -->
              <div class="section-badge"><i class="bi bi-ticket-detailed-fill me-2"></i>3. Carga de Ingressos</div>
              <p class="text-muted mb-4 fw-medium">Configure os setores. Para gerar ingressos <b class="text-success">Gratuitos</b>, preencha o valor com 0.</p>

              <div class="ticket-box mb-3">
                <div class="ticket-indicator-main"></div>
                <div class="row g-3 align-items-center ms-1">
                  <div class="col-md-8">
                    <label class="form-label fw-bold text-dark mb-1">Setor Principal (Obrigatório) <span class="text-danger">*</span></label>
                    <input type="text" name="ticket_nome[]" class="custom-input w-100" placeholder="Ex: Pista Geral / Entrada Única" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label fw-bold text-dark mb-1">Valor Unitário (R$) <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="form-label text-muted fw-bold small mb-1">R$</span>
                      <input type="number" step="0.01" name="ticket_valor[]" class="custom-input w-100" placeholder="0.00" required>
                    </div>
                  </div>
                </div>
              </div>

              <div class="ticket-box mb-3">
                <div class="ticket-indicator-opt"></div>
                <div class="row g-3 align-items-center ms-1">
                  <div class="col-md-8">
                    <label class="form-label text-muted fw-bold small mb-1">Setor Secundário (Opcional)</label>
                    <input type="text" name="ticket_nome[]" class="custom-input w-100" placeholder="Ex: Área VIP / Camarote">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label text-muted fw-bold small mb-1">Valor (R$)</label>
                    <input type="number" step="0.01" name="ticket_valor[]" class="custom-input w-100" placeholder="0.00">
                  </div>
                </div>
              </div>

              <div class="ticket-box mb-4">
                <div class="ticket-indicator-opt"></div>
                <div class="row g-3 align-items-center ms-1">
                  <div class="col-md-8">
                    <label class="form-label text-muted fw-bold small mb-1">Setor Terciário (Opcional)</label>
                    <input type="text" name="ticket_nome[]" class="custom-input w-100" placeholder="Ex: Meia-Entrada Estudante">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label text-muted fw-bold small mb-1">Valor (R$)</label>
                    <input type="number" step="0.01" name="ticket_valor[]" class="custom-input w-100" placeholder="0.00">
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-end mt-5 pt-4 border-top border-2">
                <button type="submit" name="create_evento" class="btn btn-gradient btn-lg px-5 py-3 rounded-pill">
                  <i class="bi bi-cloud-arrow-up-fill me-2 fs-5"></i> Lançar Evento no Sistema
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
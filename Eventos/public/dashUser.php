<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  $_SESSION['mensagem'] = "Por favor, faça login para acessar a área de eventos.";
  header('Location: ../views/auth/login.php');
  exit;
}

require __DIR__ . '/../config/conexao.php';
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vitrine de Eventos - FSA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    /* Estilos Premium e de Alto Contraste */
    body {
      background-color: #eef2f5;
      color: #333;
    }

    .user-card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
      background: #ffffff;
    }

    .main-row {
      transition: background-color 0.2s;
      cursor: pointer;
    }

    .main-row:hover {
      background-color: #f1f4f8 !important;
    }

    .accordion-icon {
      transition: transform 0.3s ease;
    }

    tr[aria-expanded="true"] .accordion-icon {
      transform: rotate(180deg);
      color: #ffc107 !important;
    }

    .table-custom th {
      text-transform: uppercase;
      font-size: 0.8rem;
      letter-spacing: 0.5px;
      font-weight: 700;
    }

    .badge-status {
      font-size: 0.8rem;
      padding: 0.4rem 0.8rem;
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

<body class="bg-light pb-5">
  <?php include('../views/layouts/navbar.php'); ?>

  <div class="container mt-4">
    <?php include('../views/layouts/mensagem.php'); ?>

    <!-- Seção de Boas-Vindas ao Usuário -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2 pb-3 border-bottom border-secondary border-opacity-25">
      <h4 class="mb-0 fw-bold text-dark d-flex align-items-center">
        <i class="bi bi-person-fill text-warning me-2 fs-2"></i>Olá, <?= htmlspecialchars($_SESSION['nome']); ?>!
        <span class="ms-3 badge bg-dark text-white fs-6 fw-normal shadow-sm">Membro FSA</span>
      </h4>
      <a href='../logout.php' class="btn btn-outline-danger shadow-sm rounded-pill fw-bold px-3"><i class="bi bi-box-arrow-right"></i> Sair da Conta</a>
    </div>

    <!-- Card Principal da Vitrine -->
    <div class="card user-card overflow-hidden">
      <div class="card-header bg-dark text-white p-3 text-center border-0">
        <h5 class="mb-0 fw-bold small text-white-50 text-uppercase tracking-wider">Selecione um Evento para Garantir sua Vaga</h5>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 table-custom">
            <thead class="table-dark text-white border-bottom">
              <tr>
                <th class="ps-4 py-3">Nome do Evento</th>
                <th>Localização</th>
                <th>Data</th>
                <th>Status (Disponibilidade)</th>
                <th class="text-center pe-4" width="8%">Detalhes</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = 'SELECT * FROM eventos ORDER BY id DESC';
              $eventos = mysqli_query($conexao, $sql);

              if (mysqli_num_rows($eventos) > 0) {
                foreach ($eventos as $evento) {
                  $collapseId = "collapseEvento" . $evento['id'];
                  $id_atual = $evento['id'];
                  $statusAtual = strtolower(trim($evento['status_evento']));
                  $capacidade = $evento['capacidade'];
                  $consulta_ocupacao = mysqli_query($conexao, "SELECT COUNT(id) AS qtd_comprados FROM inscricoes WHERE id_evento='$id_atual' AND status_inscricao != 'cancelada'");
                  $dados_ocupacao = mysqli_fetch_assoc($consulta_ocupacao);
                  $total_comprados = $dados_ocupacao['qtd_comprados'];
                  $vagas_restantes = $capacidade - $total_comprados;

                  if ($vagas_restantes < 0) {
                    $vagas_restantes = 0;
                  }

                  $evento_esgotado = ($vagas_restantes == 0);
              ?>

                  <tr class="main-row" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>">
                    <td class="ps-4 fw-bold text-primary fs-6">
                      <i class="bi bi-tag-fill text-secondary opacity-50 me-2"></i><?= $evento['nome'] ?>
                      <?php if ($vagas_restantes > 0 && $vagas_restantes <= 10): ?>
                        <span class="text-danger small fw-normal ms-2 animate-pulse"><i class="bi bi-fire"></i> Últimas Vagas!</span>
                      <?php endif; ?>
                    </td>
                    <td><span class="text-muted"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= $evento['localidade'] ?></span></td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2.5 py-1.5"><?= date('d/m/Y', strtotime($evento['data_evento'])) ?></span></td>

                    <td>
                      <?php
                      if ($statusAtual == 'fechado') {
                        echo '<span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger badge-status"><i class="bi bi-lock-fill me-1"></i>Inscrições Bloqueadas</span>';
                      } elseif ($evento_esgotado) {
                        echo '<span class="badge rounded-pill bg-dark bg-opacity-10 text-dark border border-dark badge-status"><i class="bi bi-slash-circle me-1"></i>Esgotado</span>';
                      } else {
                        echo '<span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success badge-status"><i class="bi bi-door-open-fill me-1"></i>Disponível</span>';
                      }
                      ?>
                    </td>

                    <td class="text-center pe-4">
                      <i class="bi bi-chevron-down fs-5 text-secondary accordion-icon"></i>
                    </td>
                  </tr>

                  <tr>
                    <td colspan="5" class="p-0 border-0">
                      <div class="collapse" id="<?= $collapseId ?>">
                        <div class="card card-body m-3 shadow-sm border-start border-warning border-4 bg-white p-4">
                          <div class="row align-items-center">

                            <div class="col-md-9">
                              <h6 class="text-uppercase text-muted fw-bold small mb-2"><i class="bi bi-info-circle-fill"></i> Detalhes do Evento</h6>
                              <p class="mb-3 text-secondary small lh-base">
                                <?= !empty($evento['descricao']) ? nl2br(htmlspecialchars($evento['descricao'])) : '<em>Nenhuma descrição detalhada disponível para este evento.</em>' ?>
                              </p>

                              <div class="d-flex flex-wrap gap-4 text-dark small p-3 bg-light rounded-3 border">
                                <span><i class="bi bi-clock-fill text-primary"></i> <strong>Abertura do Evento:</strong> <?= nl2br(htmlspecialchars($evento['horario'])) ?></span>
                                <span>
                                  <i class="bi bi-people-fill text-primary"></i> <strong>Capacidade Máxima:</strong> <?= $evento['capacidade'] ?> pessoas |
                                  <strong class="text-danger ms-1">Vagas Restantes: <?= $vagas_restantes ?></strong>
                                </span>
                              </div>
                            </div>
                            
                            <div class="col-md-3 d-flex align-items-center justify-content-center mt-3 mt-md-0 border-start ps-md-4">
                              <?php if ($statusAtual == 'fechado'): ?>
                                <div class="w-100 text-center p-3 bg-light border border-danger-subtle rounded-3 text-danger opacity-75">
                                  <i class="bi bi-lock-fill fs-4 d-block mb-1"></i>
                                  <strong class="small">Inscrições Encerradas</strong>
                                </div>
                              <?php elseif ($evento_esgotado): ?>
                                <div class="w-100 text-center p-3 bg-light border border-secondary-subtle rounded-3 text-secondary opacity-75">
                                  <i class="bi bi-emoji-frown fs-4 d-block mb-1"></i>
                                  <strong class="small">Esgotado</strong>
                                </div>
                              <?php else: ?>
                                <a href="../views/inscricoes/inscricao.php?id=<?= $evento['id'] ?>" class="btn btn-gradient btn-lg shadow w-100 py-3 rounded-3 d-flex flex-column align-items-center justify-content-center">
                                  <i class="bi bi-check2-circle fs-3 mb-1"></i> Quero Participar!
                                </a>
                              <?php endif; ?>
                            </div>

                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>

              <?php
                }
              } else {
                echo '<tr><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-emoji-frown fs-3 d-block mb-2"></i><h5>Nenhum evento foi agendado ou programado ainda</h5></td></tr>';
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
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
  <title>Painel de Eventos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    .accordion-icon {
      transition: transform 0.3s ease;
    }

    tr[aria-expanded="true"] .accordion-icon {
      transform: rotate(180deg);
    }

    /* Melhora o destaque da linha ao passar o mouse */
    .table-hover tbody tr.main-row:hover {
      background-color: #f8f9fa;
    }

    /* Badge pra chamar atenção pro ESGOTADO */
    .bg-esgotado {
      background-color: #343a40 !important;
      color: white !important;
    }
  </style>

</head>

<body class="bg-light">
  <?php include('../views/layouts/navbar.php'); ?>

  <div class="container mt-4">
    <?php include('../views/layouts/mensagem.php'); ?>

    <!-- Informações do Usuário Logado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>Olá, <?= htmlspecialchars($_SESSION['nome']); ?>! <span class="badge bg-secondary fs-6">Usuário</span></h4>
      <a href='../logout.php' class="btn btn-outline-danger shadow-sm"><i class="bi bi-box-arrow-right"></i> Sair da Conta</a>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-dark text-white p-3">
            <h5 class="mb-0">Selecione um Evento para Participar</h5>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4">Nome do Evento</th>
                    <th>Localização</th>
                    <th>Data</th>
                    <th>Status (Disponibilidade)</th>
                    <th class="text-center pe-4">Detalhes</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = 'SELECT * FROM eventos';
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

                      <tr class="main-row" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>" style="cursor: pointer;">
                        <td class="ps-4 fw-bold text-primary">

                          <?= $evento['nome'] ?>
                          <?php if ($vagas_restantes > 0 && $vagas_restantes <= 10): ?>
                            <span class="text-danger small fw-normal ms-2"><i class="bi bi-fire"></i> Acabando!</span>
                          <?php endif; ?>

                        </td>
                        <td><i class="bi bi-geo-alt text-muted"></i> <?= $evento['localidade'] ?></td>
                        <td><i class="bi bi-calendar-event text-muted"></i> <?= date('d/m/Y', strtotime($evento['data_evento'])) ?></td>

                        <td>

                          <?php
                          if ($statusAtual == 'fechado') {
                            echo '<span class="badge bg-danger">Inscrição Bloqueada / Cancelado</span>';
                          } elseif ($evento_esgotado) {
                            echo '<span class="badge bg-esgotado shadow"><i class="bi bi-slash-circle"></i> Esgotado</span>';
                          } else {
                            echo '<span class="badge bg-success shadow"><i class="bi bi-door-open"></i> Abertas</span>';
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
                            <div class="card card-body m-3 shadow-sm border-start border-primary border-4 bg-white">
                              <div class="row">
                                <div class="col-md-9">
                                  <h6 class="text-uppercase text-muted fw-bold mb-2">Sobre o Evento</h6>

                                  <p class="mb-3">
                                    <?= !empty($evento['descricao']) ? nl2br(htmlspecialchars($evento['descricao'])) : '<em>Nenhuma descrição detalhada disponível para este evento.</em>' ?>
                                  </p>

                                  <div class="d-flex gap-4 text-dark small p-2 bg-light rounded align-items-center">
                                    <span><i class="bi bi-clock fs-5 text-primary"></i> <strong>Abre às:</strong> <?= nl2br(htmlspecialchars($evento['horario'])) ?></span>
                                    <span>
                                      <i class="bi bi-people-fill fs-5 text-primary"></i> <strong>Total do Ambiente:</strong> <?= $evento['capacidade'] ?> Capacidade |
                                      <b class="text-danger ms-1">Temos <?= $vagas_restantes ?> disponíveis hoje.</b>
                                    </span>
                                  </div>

                                </div>

                                <div class="col-md-3 d-flex align-items-center justify-content-end mt-3 mt-md-0 border-start ps-4">
                                  <?php if ($statusAtual == 'fechado'): ?>

                                    <div class="w-100 text-center p-3 bg-light border border-danger-subtle rounded text-danger opacity-75">
                                      <i class="bi bi-lock-fill fs-4 d-block mb-1"></i>
                                      <strong>Encerrado / Oculto por ordem do Adm.</strong>
                                    </div>

                                  <?php elseif ($evento_esgotado): ?>

                                    <div class="w-100 text-center p-3 bg-dark border border-secondary-subtle shadow rounded text-white opacity-75">
                                      <i class="bi bi-emoji-frown fs-4 d-block mb-1"></i>
                                      <strong>Sold-Out! Esgotado! Lotação Total Alcançada.</strong>
                                    </div>

                                  <?php else: ?>

                                    <a href="../views/inscricoes/inscricao.php?id=<?= $evento['id'] ?>" class="btn btn-primary btn-lg shadow w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center">
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
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
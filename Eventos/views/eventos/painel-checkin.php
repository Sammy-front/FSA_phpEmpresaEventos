<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['cargo'] !== 'adm') {
  $_SESSION['mensagem'] = "Sem Privilégio! Acesso negado à portaria e check-in.";
  header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/public/dashUser.php');
  exit;
}
require __DIR__ . '/../../config/conexao.php';
?>

<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Staff & Portaria - Gestão de Check-in</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    body {
      background-color: #eef2f5;
      color: #333;
    }
    .scanner-card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
      background: #ffffff;
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
    .accordion-item {
      border: none;
      border-radius: 0.75rem !important;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
      margin-bottom: 0.75rem;
    }
    .accordion-button {
      font-weight: 700;
      color: #212529;
      padding: 1.2rem 1.5rem;
    }
    .accordion-button:not(.collapsed) {
      background-color: #16191c;
      color: #ffc107;
      box-shadow: none;
    }
    .accordion-button:not(.collapsed) .bi-star-fill {
      color: #ffc107 !important;
    }
    .accordion-button:not(.collapsed) .text-muted {
      color: rgba(255, 255, 255, 0.6) !important;
    }
    .bg-tabela-destaque {
      background-color: #f8f9fa;
    }
    .table-custom th {
      text-transform: uppercase;
      font-size: 0.8rem;
      letter-spacing: 0.5px;
      font-weight: 700;
    }
    .status-check {
      min-width: 140px;
      text-align: center;
    }
  </style>
</head>

<body class="bg-light pb-5">

  <?php include('../layouts/navbar.php'); ?>

  <div class="container mt-5">
    <?php include('../layouts/mensagem.php'); ?>

    <div class="row justify-content-center mb-5">
      <div class="col-md-8">
        <div class="scanner-card text-center p-4 p-md-5">
          <h4 class="text-dark fw-bold mb-2">
            <i class="bi bi-upc-scan fs-1 align-middle text-warning me-2"></i> Portaria e Check-in Digital
          </h4>
          <p class="text-muted mb-4">Insira o código numérico do ingresso ou escaneie o código de barras.</p>

          <form action="/FSA/FSA_phpEmpresaEventos/Eventos/controllers/checkinControllers.php" method="POST" class="d-flex justify-content-center align-items-center gap-2">
            <input type="number" name="id_inscricao" class="custom-input w-50 text-center fw-bold fs-5" placeholder="Nº do Ingresso. Ex: 101" required autofocus>
            <button type="submit" name="realizar_checkin" class="btn btn-warning btn-lg fw-bold px-4 rounded-3 text-dark shadow-sm">
              <i class="bi bi-check-circle-fill me-1"></i> Autorizar Entrada
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="section-badge"><i class="bi bi-calendar2-check-fill me-2"></i>Relação de Eventos e Participantes</div>

    <div class="accordion border-0" id="accordionFestasList">
      <?php
      $queryEventos = mysqli_query($conexao, "SELECT id, nome, data_evento FROM eventos ORDER BY data_evento DESC");

      if (mysqli_num_rows($queryEventos) > 0) {
        $counter = 0;

        while ($evento = mysqli_fetch_assoc($queryEventos)) {
          $idFesta = $evento['id'];
          $nomeFesta = htmlspecialchars($evento['nome']);
          $data_eventoFormat = date('d/m/Y', strtotime($evento['data_evento']));
          $countVendidos = mysqli_query($conexao, "SELECT COUNT(id) AS qtd FROM inscricoes WHERE id_evento='$idFesta' AND status_inscricao != 'cancelada'");
          $totalInscritos = mysqli_fetch_assoc($countVendidos)['qtd'];
      ?>

          <div class="accordion-item">
            <h2 class="accordion-header" id="heading_<?= $counter ?>">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $counter ?>" aria-expanded="false" aria-controls="collapse_<?= $counter ?>">

                <i class="bi bi-star-fill text-warning me-3"></i> <?= $nomeFesta ?>
                <span class="ms-3 fw-normal text-muted fst-italic fs-6 border-start ps-3">Data: <?= $data_eventoFormat ?></span>

                <?php if ($totalInscritos > 0): ?>
                  <span class="badge rounded-pill bg-success ms-auto">+<?= $totalInscritos ?> Inscritos</span>
                <?php else: ?>
                  <span class="badge rounded-pill bg-secondary ms-auto">Nenhuma Inscrição</span>
                <?php endif; ?>

              </button>
            </h2>

            <div id="collapse_<?= $counter ?>" class="accordion-collapse collapse" aria-labelledby="heading_<?= $counter ?>" data-bs-parent="#accordionFestasList">
              <div class="accordion-body p-4 bg-white">

                <?php if ($totalInscritos == 0): ?>
                  <div class="alert alert-secondary d-flex align-items-center mb-0 rounded-3 border-0 bg-light" role="alert">
                    <i class="bi bi-info-circle-fill fs-4 me-3 text-secondary"></i>
                    <div class="small fw-semibold text-secondary">Ainda não existem participantes confirmados para este evento. As opções de portaria estarão disponíveis assim que houverem ingressos vendidos.</div>
                  </div>
                <?php else: ?>
                  <div class="table-responsive bg-white rounded-3 border">
                    <table class="table table-hover text-center align-middle mb-0 table-custom">
                      <thead class="table-dark text-white border-bottom">
                        <tr>
                          <th class="ps-4">Nº Ticket</th>
                          <th>Visitante Convidado</th>
                          <th>Situação Financeira</th>
                          <th class="status-check">Status Catraca</th>
                          <th class="pe-4" width="25%">Ação Portaria</th>
                        </tr>
                      </thead>
                      <tbody>

                        <?php
                        $sqlListagem_Pessoas = "SELECT 
                        i.id AS id_ingresso,
                        i.status_inscricao,
                        u.nome AS nome_participante,
                        c.data_entrada,
                        c.id AS id_checkin_realizado 
                        FROM inscricoes i
                        INNER JOIN usuarios u ON i.id_usuario = u.id
                        LEFT JOIN check_ins c ON c.id_inscricao = i.id
                        WHERE i.id_evento = '$idFesta' 
                        ORDER BY u.nome ASC";

                        $query_pessoas = mysqli_query($conexao, $sqlListagem_Pessoas);

                        while ($participante = mysqli_fetch_assoc($query_pessoas)) {
                          $fezCheckin = !empty($participante['data_entrada']);
                        ?>
                          <tr class="<?= $fezCheckin ? 'bg-tabela-destaque' : '' ?>">

                            <!-- ID do Ticket -->
                            <td class="fw-bold text-dark fs-5 ps-3">
                              <span class="border rounded px-2 bg-light shadow-sm">#<?= $participante['id_ingresso'] ?></span>
                            </td>

                            <!-- Nome -->
                            <td class="text-start fw-semibold p-3 text-dark">
                              <i class="bi bi-person-badge text-secondary me-2"></i> <?= htmlspecialchars($participante['nome_participante']) ?>
                            </td>

                            <!-- Situação -->
                            <td>
                              <?php if ($participante['status_inscricao'] === 'cancelada'): ?>
                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger px-3">Cancelado / Reembolsado</span>
                              <?php else: ?>
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success px-3">Aprovado (Pago)</span>
                              <?php endif; ?>
                            </td>

                            <!-- Status Catraca -->
                            <td class="status-check bg-light border-end border-start">
                              <?php if ($fezCheckin): ?>
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success px-3 d-inline-block">
                                  <i class="bi bi-door-open-fill me-1"></i>Entrou
                                </span>
                                <small class="text-muted d-block mt-1" style="font-size:0.75rem;">
                                  <?= date('H:i \h\s', strtotime($participante['data_entrada'])) ?>
                                </small>
                              <?php else: ?>
                                <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border border-secondary px-3">
                                  <i class="bi bi-dash-circle me-1"></i>Fora
                                </span>
                              <?php endif; ?>
                            </td>

                            <!-- Ações -->
                            <td class="pe-4">
                              <?php if ($participante['status_inscricao'] === 'cancelada'): ?>

                                <button disabled class="btn btn-secondary btn-sm rounded-pill px-4 opacity-50"><i class="bi bi-ban"></i> Ticket Inválido</button>

                              <?php elseif (!$fezCheckin): ?>

                                <form action="/FSA/FSA_phpEmpresaEventos/Eventos/controllers/checkinControllers.php" method="POST" class="d-inline">
                                  <input type="hidden" name="id_inscricao" value="<?= $participante['id_ingresso'] ?>">
                                  <button type="submit" name="realizar_checkin" class="btn btn-success btn-sm px-4 shadow-sm rounded-pill fw-bold">
                                    <i class="bi bi-upc-scan me-1"></i> Confirmar Entrada
                                  </button>
                                </form>

                              <?php else: ?>

                                <form action="/FSA/FSA_phpEmpresaEventos/Eventos/controllers/checkinControllers.php" method="POST" class="d-inline">
                                  <input type="hidden" name="desfazer_checkin" value="<?= $participante['id_checkin_realizado'] ?>">
                                  <button type="submit" name="desfazer_checkin" onclick="return confirm('ATENÇÃO PORTARIA:\nDeseja realmente cancelar o Check-In deste participante? Ele precisará ter seu acesso validado novamente para entrar.')" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-arrow-counterclockwise"></i> Desfazer Leitura
                                  </button>
                                </form>

                              <?php endif; ?>
                            </td>

                          </tr>
                        <?php } ?>

                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>

              </div>
            </div>
          </div>
      <?php
          $counter++;
        }
      } else {
        echo '<div class="text-center py-5 my-4 bg-white border border-light shadow-sm rounded-4"><i class="bi bi-clipboard-x fs-1 text-muted d-block mb-3"></i><h4 class="text-muted fw-light">Sua grade de Eventos está vazia!</h4><p class="text-secondary mb-0">Adicione um novo Evento no painel administrativo principal antes de gerenciar a portaria.</p></div>';
      }
      ?>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
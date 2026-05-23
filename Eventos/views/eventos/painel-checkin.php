<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: ../auth/login.php');
  exit;
}

require __DIR__ . '/../../config/conexao.php';
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel de Portaria - Check-ins</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    .badge-status {
      font-size: 0.9em;
      padding: 0.5em 0.8em;
    }

    .scanner-box {
      background: #f8f9fa;
      border: 2px dashed #0d6efd;
      padding: 20px;
      border-radius: 8px;
    }
  </style>

</head>

<body class="bg-light">
  <?php include('../layouts/navbar.php'); ?>

  <div class="container mt-5">
    <?php include('../layouts/mensagem.php'); ?>

    <!-- Leitor Simulado -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-6">
        <div class="card shadow-sm text-center scanner-box">
          <h5 class="text-primary"><i class="bi bi-qr-code-scan"></i> Bipar Ingresso (Entrada Rápida)</h5>
          <p class="text-muted small">Digite o número de identificação da Inscrição (Simulação de pistola Leitora)</p>
          <form action="../../controllers/CheckinController.php" method="POST" class="d-flex justify-content-center">
            <input type="number" name="id_inscricao" class="form-control w-50" placeholder="Ex: 15..." autofocus required>
            <button type="submit" name="realizar_checkin" class="btn btn-primary ms-2 border-dark text-light"><i class="bi bi-door-open-fill"></i> Autorizar</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Tabela Listagem das Inscrições (Pra buscar quem perdeu papel/codigo etc) -->
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-people"></i> Listagem de Ingressos e Status da Porta</h4>
        <a href="../../public/index.php" class="btn btn-outline-light btn-sm text-white border-white border">Voltar ao Admin</a>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-bordered text-center align-middle">
            <thead class="table-light">
              <tr>
                <th>Nº INGRESSO</th>
                <th>Participante</th>
                <th>Evento Relacionado</th>
                <th>Status Pagamento</th>
                <th>Status Catraca</th>
                <th>Ações Portaria</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // ==============================================================
              // A Super Busca SQL (Mágica dos JOINs cruzando dados!)
              // ==============================================================
              $sql = "SELECT 
                              i.id AS id_ingresso,
                              i.status_inscricao,
                              u.nome AS participante,
                              e.nome AS evento_nome,
                              c.data_entrada AS checou_em,
                              c.id AS checkin_ID
                          FROM inscricoes i
                          INNER JOIN usuarios u ON i.id_usuario = u.id
                          INNER JOIN eventos e ON i.id_evento = e.id
                          LEFT JOIN check_ins c ON c.id_inscricao = i.id
                          ORDER BY i.id DESC"; // Mostra os mais recentes em cima

              $resultado = mysqli_query($conexao, $sql);

              // Se houver algum ingresso vendido/cadastrado..
              if ($resultado && mysqli_num_rows($resultado) > 0):
                while ($linha = mysqli_fetch_assoc($resultado)):

                  // VERIFICA SE JÁ ENTROU PELA TABELA DE CHECKIN:
                  $ja_entrou = !empty($linha['checou_em']) ? true : false;
              ?>

                  <tr>
                    <td><strong>#<?= $linha['id_ingresso'] ?></strong></td>
                    <td class="text-start"><?= $linha['participante'] ?></td>
                    <td class="text-start"><?= $linha['evento_nome'] ?></td>

                    <td>
                      <!-- Analisando pagamento para a catraca  -->
                      <?php if ($linha['status_inscricao'] === 'cancelada'): ?>
                        <span class="badge bg-danger badge-status">CANCELADA</span>
                      <?php elseif ($linha['status_inscricao'] === 'pendente'): ?>
                        <span class="badge bg-warning badge-status">A PAGAR</span>
                      <?php else: ?>
                        <span class="badge bg-success badge-status">PAGO</span>
                      <?php endif; ?>
                    </td>

                    <!-- A MAGIA VISUAL: MOSTRAR VERDE SE ENTROU -->
                    <td>
                      <?php if ($ja_entrou): ?>
                        <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> JÁ ENTROU <br><small class="text-muted">(<?= date('H:i:s', strtotime($linha['checou_em'])) ?>)</small></span>
                      <?php else: ?>
                        <span class="text-secondary"><i class="bi bi-dash-circle"></i> FORA DA FESTA</span>
                      <?php endif; ?>
                    </td>

                    <td>
                      <!-- SISTEMA DE BLOQUEIO DE BOTÕES INTELIGENTE -->
                      <?php if ($linha['status_inscricao'] === 'cancelada'): ?>
                        <button disabled class="btn btn-secondary btn-sm"><i class="bi bi-slash-circle"></i> Inutilizável</button>

                      <?php elseif (!$ja_entrou): ?>
                        <!-- BOTÃO BOTA PARA DENTRO DO EVENTO -->
                        <form action="../../controllers/CheckinController.php" method="POST" class="d-inline">
                          <input type="hidden" name="id_inscricao" value="<?= $linha['id_ingresso'] ?>">
                          <button type="submit" name="realizar_checkin" class="btn btn-success btn-sm"><i class="bi bi-qr-code"></i> Liberar Entrada</button>
                        </form>

                      <?php else: ?>
                        <!-- CASO TENHA COMETIDO UM ERRO, MOSTRA O BOTÃO DESFAZER/ESTORNAR DE DELETAR DA TABELA check_in -->
                        <form action="../../controllers/CheckinController.php" method="POST" class="d-inline">
                          <input type="hidden" name="desfazer_checkin" value="<?= $linha['checkin_ID'] ?>">
                          <button type="submit" name="desfazer_checkin" onclick="return confirm('Deseja ESTORNAR/CANCELAR a liberação? A pessoa poderá entrar com o papel de novo!')" class="btn btn-outline-danger btn-sm text-black">❌ Estornar Bipagem</button>
                        </form>
                      <?php endif; ?>
                    </td>
                  </tr>

                <?php
                endwhile;
              else:
                ?>
                <tr>
                  <td colspan="6" class="p-4 text-muted">Ainda não existe nenhum ingresso/inscrição comprado em nenhum evento para mostrar na catraca.</td>
                </tr>
              <?php endif; ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <!-- Script Javascript Obrigatório do Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
// Arquivo: views/eventos/painel-checkin.php
session_start();

// O Painel do STAFF EXIGE Login de Administrador
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
    /* Estilos Customizados para deixar a tabela com visual corporativo */
    .scanner-box { border: 2px dashed #0d6efd; background-color: #f8f9fa; }
    .bg-tabela-destaque { background-color: #f1f4f8; }
    .accordion-button:not(.collapsed) { background-color: #e9ecef; color: #000; box-shadow: none; }
    .status-check { min-width: 130px; text-align: center; }
  </style>
</head>

<body class="bg-light">
  
  <?php include('../layouts/navbar.php'); ?>

  <div class="container mt-5 mb-5">
    <?php include('../layouts/mensagem.php'); ?>

    <!-- Leitor Rápido Central / Simulador QR CODE -->
    <div class="row justify-content-center mb-5">
      <div class="col-md-7">
        <div class="card shadow-sm text-center scanner-box p-4 rounded-4">
          <h4 class="text-primary fw-bold mb-3">
             <i class="bi bi-upc-scan fs-2 align-middle text-danger me-2"></i> Simulador de Leitura (Catraca)
          </h4>
          <p class="text-muted small">Passe a leitora no código do ingresso impresso (ID da Inscrição)</p>
          
          <form action="/FSA/FSA_phpEmpresaEventos/Eventos/controllers/checkinControllers.php" method="POST" class="d-flex justify-content-center align-items-center">
            <input type="number" name="id_inscricao" class="form-control form-control-lg w-50 border-dark text-center shadow-sm" placeholder="ID. Ex: 105" required autofocus>
            <button type="submit" name="realizar_checkin" class="btn btn-primary btn-lg ms-3 shadow-sm px-4">
                 <i class="bi bi-check-circle-fill me-1"></i> Autorizar Entrada
            </button>
          </form>
        </div>
      </div>
    </div>


    <div class="d-flex align-items-center border-bottom pb-2 mb-3">
        <i class="bi bi-calendar2-check text-dark fs-3 me-3"></i>
        <h3 class="mb-0 text-dark fw-bold">Eventos Programados - Lista de Convidados</h3>
    </div>

    <!-- SANFONA / ACCORDION BOOTSTRAP PARA AGRUPAR LISTAS DE FESTAS -->  
    <div class="accordion shadow-sm border-0" id="accordionFestasList">

      <?php
      // 1. Buscamos as festas ordenando pelas mais recentes primeiro
      $queryEventos = mysqli_query($conexao, "SELECT id, nome, data_evento FROM eventos ORDER BY data_evento DESC");

      if (mysqli_num_rows($queryEventos) > 0) {
        $counter = 0; // Usado pra controlar o drop-down do sanfona 
        
        while ($evento = mysqli_fetch_assoc($queryEventos)) {
          $idFesta = $evento['id'];
          $nomeFesta = htmlspecialchars($evento['nome']);
          $data_eventoFormat = date('d/m/Y', strtotime($evento['data_evento']));
          
          // Conta quantidade de ingressos vendidos nesse evento especifico
          $countVendidos = mysqli_query($conexao, "SELECT COUNT(id) AS qtd FROM inscricoes WHERE id_evento='$idFesta'");
          $totalInscritos = mysqli_fetch_assoc($countVendidos)['qtd'];
      ?>

          <div class="accordion-item border-bottom">
            <h2 class="accordion-header" id="heading_<?=$counter?>">
              <button class="accordion-button collapsed fw-bold fs-6 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?=$counter?>" aria-expanded="false" aria-controls="collapse_<?=$counter?>">
                 
                 <i class="bi bi-star-fill text-warning me-3"></i> <?= $nomeFesta ?>
                 <span class="ms-3 fw-normal text-muted fst-italic fs-6 border-start ps-3"> Agendado para: <?= $data_eventoFormat ?></span>
                 
                 <?php if($totalInscritos > 0): ?>
                      <span class="badge rounded-pill bg-success ms-auto">+<?= $totalInscritos ?> Inscritos</span>
                 <?php else: ?>
                      <span class="badge rounded-pill bg-secondary ms-auto">Nenhuma Venda Registrada</span>
                 <?php endif; ?>
                 
              </button>
            </h2>

            <div id="collapse_<?=$counter?>" class="accordion-collapse collapse bg-light" aria-labelledby="heading_<?=$counter?>" data-bs-parent="#accordionFestasList">
              <div class="accordion-body p-4">
                 
                <?php if ($totalInscritos == 0): ?>
                    <!-- CARD EVENTO VAZIO -->
                     <div class="alert alert-secondary d-flex align-items-center mb-0" role="alert">
                         <i class="bi bi-info-circle-fill fs-3 me-3 text-secondary"></i>
                         <div>Ainda não existem participantes confirmados (inscrições/compras efetuadas) para este evento específico. Fique atento às futuras vendas.</div>
                     </div>
                <?php else: ?>    
                     
                    <!-- TABELA CORPORATIVA DOS INSCRITOS -->   
                    <div class="table-responsive bg-white rounded shadow-sm border">
                      <table class="table table-hover text-center align-middle mb-0">
                        <thead class="table-dark">
                          <tr>
                            <th>Nº Ticket</th>
                            <th>Visitante (Convidado)</th>
                            <th>Pagamento DB</th>
                            <th class="status-check">Status Entrada</th>
                            <th>Operação Portaria</th>
                          </tr>
                        </thead>
                        <tbody>
                            
                          <?php
                          // =========================================================================
                          // LÓGICA SQL LIMPA - Cruza Venda com Usuário e a Catraca
                          // =========================================================================
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
                              // Flag Booleana Verdadeira se já bateu check-in!
                              $fezCheckin = !empty($participante['data_entrada']);
                          ?>
                              <tr class="<?= $fezCheckin ? 'bg-tabela-destaque' : '' ?>">
                                
                                <!-- Coluna NOME DO INGRESSO/ID -->
                                <td class="fw-bold text-dark fs-5">
                                    <span class="border rounded px-2 bg-light shadow-sm">#<?= $participante['id_ingresso'] ?></span>
                                </td>
                                
                                <!-- Coluna PARTICIPANTE -->
                                <td class="text-start fw-semibold p-3 text-dark">
                                    <i class="bi bi-person-badge text-secondary me-2"></i> <?= htmlspecialchars($participante['nome_participante']) ?>
                                </td>
                                
                                <!-- Coluna PAGAMENTO -->
                                <td>
                                  <?php if ($participante['status_inscricao'] === 'cancelada'): ?>
                                    <span class="badge bg-danger">Reembolsado/Cancelado</span>
                                  <?php else: ?>
                                    <span class="badge bg-success px-3">Autorizado</span>
                                  <?php endif; ?>
                                </td>

                                <!-- Coluna CHECK-IN ENTRADA -->
                                <td class="status-check bg-light border-end border-start">
                                  <?php if ($fezCheckin): ?>
                                      <i class="bi bi-door-open-fill text-success fs-4 d-block mt-2"></i>
                                      <span class="text-success fw-bold d-block mt-1">Check-in OK!</span>
                                      <small class="badge bg-success-subtle text-success-emphasis border border-success mt-1">
                                        <?= date('H:i \h\s', strtotime($participante['data_entrada'])) ?>
                                      </small>
                                  <?php else: ?>
                                      <span class="text-muted d-block mt-3"><i class="bi bi-dash-circle"></i> Fora do Evento</span> 
                                  <?php endif; ?>
                                </td>

                                <!-- Coluna BOTÕES DE STAFF/OPERAÇÃO -->
                                <td style="width: 250px;">
                                  <?php if ($participante['status_inscricao'] === 'cancelada'): ?>
                                      
                                      <button disabled class="btn btn-secondary btn-sm px-4 opacity-50"><i class="bi bi-ban"></i> Ticket Inválido</button>
                                  
                                  <?php elseif (!$fezCheckin): ?>

                                    <!-- CHECK-IN / ENTRADA --> 
                                    <form action="/FSA/FSA_phpEmpresaEventos/Eventos/controllers/checkinControllers.php" method="POST" class="d-inline">
                                      <input type="hidden" name="id_inscricao" value="<?= $participante['id_ingresso'] ?>">
                                      <button type="submit" name="realizar_checkin" class="btn btn-success btn-sm px-3 shadow-sm rounded-pill fw-bold">
                                            <i class="bi bi-upc-scan me-1"></i> Confirmar Entrada
                                      </button>
                                    </form>

                                  <?php else: ?>
                                     
                                     <!-- ESTORNO DE PORTA / DESCULPA O CLIQUE ERRADO --> 
                                     <form action="/FSA/FSA_phpEmpresaEventos/Eventos/controllers/checkinControllers.php" method="POST" class="d-inline">
                                        <input type="hidden" name="desfazer_checkin" value="<?= $participante['id_checkin_realizado'] ?>">  
                                        <button type="submit" name="desfazer_checkin" onclick="return confirm('ATENÇÃO ADMINISTRADOR:\nDeseja cancelar o Check-In deste ingresso? A pulseira de entrada deverá ser removida, pois ele será considerado fora da festa!')" class="btn btn-outline-danger btn-sm rounded-pill px-3">
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
        // QUANDO NÃO HÁ SHOW/FESTA NENHUMA NO BD 
        echo '"<div class="text-center py-5 my-4 bg-white border border-light shadow-sm rounded-4"><i class="bi bi-clipboard-x fs-1 text-muted d-block mb-3"></i><h4 class="text-muted fw-light">Sua grade de Eventos e Projetos está zerada!</h4><p class="text-secondary mb-0">Adicione um novo Evento no painel Principal Administrador antes de gerir uma portaria ou lista física.</p></div>"';
      }
      ?>
      
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['cargo'] !== 'adm') {
   $_SESSION['mensagem'] = "Acesso Restrito: Faça login como Administrador.";
   header('Location: ../views/auth/login.php');
   exit;
}
require __DIR__ . '/../config/conexao.php';
$cnt1 = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT COUNT(*) c FROM eventos"))['c'];
$cnt2 = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT COUNT(*) c FROM usuarios"))['c'];
$cnt3 = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT COUNT(*) c FROM inscricoes WHERE status_inscricao = 'paga'"))['c'];

$sqlListar = 'SELECT * FROM eventos ORDER BY data_evento DESC';
$eventos = mysqli_query($conexao, $sqlListar);
?>
<!doctype html>
<html lang="pt-br">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Dashboard Administrativa - FSA</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

   <style>
      body {
         background-color: #f4f6f9;
      }

      .widget-card {
         transition: all 0.2s ease;
         border-radius: 12px;
      }

      .widget-card:hover {
         transform: translateY(-3px);
         box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
      }

      .table-modern th {
         background-color: #f8f9fc;
         text-transform: uppercase;
         font-size: 0.8rem;
         letter-spacing: 0.5px;
         color: #6c757d;
         font-weight: 700;
         border-bottom: 2px solid #e3e6f0;
      }

      .table-modern td {
         vertical-align: middle;
         font-size: 0.95rem;
         border-bottom: 1px solid #f1f3f5;
      }

      .btn-acao {
         transition: all 0.2s;
         border-radius: 6px;
         padding: 0.35rem 0.6rem;
         font-weight: 500;
         font-size: 0.85rem;
      }
   </style>
</head>

<body>

   <?php include('../views/layouts/navbar.php'); ?>

   <div class="container mt-4 mb-5 pb-5">

      <!-- Cabeçalho Principal -->
      <div class="row align-items-end mb-4">
         <div class="col-md-8 mb-3 mb-md-0">
            <h2 class="fw-bold text-dark m-0"><i class="bi bi-grid-1x2-fill text-primary me-2"></i> Painel de Gerenciamento</h2>
            <p class="text-muted mb-0 mt-1">Supervisão de métricas e administração do catálogo de eventos da FSA.</p>
         </div>
         <div class="col-md-4 text-md-end">
            <a href="../views/eventos/evento-create.php" class="btn btn-primary px-4 shadow-sm fw-bold rounded-pill">
               <i class="bi bi-calendar2-plus me-1"></i> Agendar Novo Evento
            </a>
         </div>
      </div>

      <div class="row mb-5 g-4">
         <div class="col-md-4">
            <div class="card widget-card border-0 shadow-sm border-bottom border-warning border-3 h-100">
               <div class="card-body p-4 d-flex align-items-center justify-content-between">
                  <div>
                     <h6 class="text-muted fw-bold mb-1 text-uppercase" style="font-size:0.8rem">Shows Registrados</h6>
                     <h2 class="mb-0 fw-bolder text-dark"><?= $cnt1 ?></h2>
                  </div>
                  <div class="bg-warning-subtle text-warning p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                     <i class="bi bi-collection-play-fill fs-2"></i>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-md-4">
            <div class="card widget-card border-0 shadow-sm border-bottom border-primary border-3 h-100">
               <div class="card-body p-4 d-flex align-items-center justify-content-between">
                  <div>
                     <h6 class="text-muted fw-bold mb-1 text-uppercase" style="font-size:0.8rem">Usuários na Base</h6>
                     <h2 class="mb-0 fw-bolder text-dark"><?= $cnt2 ?></h2>
                  </div>
                  <div class="bg-primary-subtle text-primary p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                     <i class="bi bi-people-fill fs-2"></i>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-md-4">
            <div class="card widget-card border-0 shadow-sm border-bottom border-success border-3 h-100">
               <div class="card-body p-4 d-flex align-items-center justify-content-between">
                  <div>
                     <h6 class="text-muted fw-bold mb-1 text-uppercase" style="font-size:0.8rem">Vendas Confirmadas</h6>
                     <h2 class="mb-0 fw-bolder text-dark"><?= $cnt3 ?></h2>
                  </div>
                  <div class="bg-success-subtle text-success p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                     <i class="bi bi-cash-stack fs-2"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- AREA DA TABELA PRINCIPAL -->
      <div class="card shadow-sm border-0 rounded-3">

         <?php include('../views/layouts/mensagem.php'); ?>

         <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <i class="bi bi-list-task fs-4 text-primary me-2"></i>
            <h5 class="fw-bold mb-0 text-dark">Relação Completa de Eventos</h5>
         </div>

         <div class="card-body p-0">
            <div class="table-responsive">
               <table class="table table-hover table-modern text-center m-0">
                  <thead>
                     <tr>
                        <th width="8%">Cod ID</th>
                        <th class="text-start ps-4">Nome Comercial do Evento</th>
                        <th>Data Programada</th>
                        <th>Capacidade</th>
                        <th>Status (Sistema)</th>
                        <th width="30%" class="text-end pe-4">Controles Admin</th>
                     </tr>
                  </thead>
                  <tbody>

                     <?php if (mysqli_num_rows($eventos) > 0) {
                        foreach ($eventos as $ev) {
                     ?>

                           <tr>
                              <td class="text-muted fw-semibold">
                                 <?= "#" . str_pad($ev['id'], 3, '0', STR_PAD_LEFT); ?>
                              </td>

                              <td class="text-start ps-4 text-dark fw-bold">
                                 <?= $ev['nome'] ?>
                              </td>

                              <td class="text-secondary fw-medium">
                                 <i class="bi bi-calendar3 me-1"></i> <?= date('d/m/Y', strtotime($ev['data_evento'])); ?>
                              </td>

                              <td class="text-secondary">
                                 <i class="bi bi-people-fill me-1"></i> <?= $ev['capacidade'] ?>
                              </td>

                              <td>
                                 <?php if ($ev['status_evento'] == 'fechado'): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3">
                                       <i class="bi bi-lock-fill"></i> Fechado
                                    </span>
                                 <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">
                                       <i class="bi bi-globe"></i> Aberto
                                    </span>
                                 <?php endif; ?>
                              </td>

                              <td class="text-end pe-3">
                                 <div class="d-inline-flex gap-2">
                                    <a href="../views/eventos/evento-view.php" class="btn btn-light btn-acao border shadow-sm text-secondary" title="Gerenciar Evento em detalhes">
                                       <i class="bi bi-gear-fill me-1"></i> Gestão
                                    </a>

                                    <a href="../views/eventos/evento-edit.php?id=<?= $ev['id'] ?>" class="btn btn-warning btn-acao shadow-sm text-dark" title="Modificar Textos, Preços e Datas">
                                       <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="../controllers/eventoControllers.php" method="POST" class="m-0 p-0" onsubmit="return confirm('ALERTA: Confirmar Exclusão Definitiva?\nTodos os convites comprados atrelados a essa base também se perderão permanentemente.');">
                                       <button type="submit" value="<?= $ev['id'] ?>" name="delete_evento" class="btn btn-danger btn-acao shadow-sm" title="Apagar Registro Completo">
                                          <i class="bi bi-trash3-fill"></i>
                                       </button>
                                    </form>
                                 </div>
                              </td>

                           </tr>

                     <?php }
                     } else {
                        echo '<tr><td colspan="6" class="p-5 text-muted fst-italic"><i class="bi bi-exclamation-circle fs-4 d-block mb-2"></i> Nada no Banco de Dados! Registre o seu primeiro Evento.</tr></td>';
                     } ?>

                  </tbody>
               </table>
            </div>
         </div>
      </div>

   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
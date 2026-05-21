<?php session_start(); ?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Início - Empresa de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .hero-section { background-color: #f8f9fa; padding: 60px 0; text-align: center; border-bottom: 2px solid #dee2e6; }
      .hero-section h1 { font-weight: bold; margin-bottom: 20px; }
      .features { margin-top: 40px; }
      .feature-box { text-align: center; padding: 20px; }
    </style>
  </head>
  <body>
    <?php include('../views/layouts/navbar.php'); ?>

    <div class="hero-section">
      <div class="container">
        <h1 class="display-4 text-dark">Bem-vindo à FSA Eventos</h1>
        <p class="lead text-secondary">
          Especialistas em criar momentos inesquecíveis! O sistema completo e inteligente <br> para a gestão dos seus melhores eventos.
        </p>
        <div class="mt-4">
          <a href="../views/auth/login.php" class="btn btn-primary btn-lg me-2">Acessar Sistema</a>
          <a href="../views/auth/register.php" class="btn btn-outline-dark btn-lg">Quero criar minha conta</a>
        </div>
      </div>
    </div>

    <div class="container features">
      <div class="row text-center">
        <div class="col-md-4 feature-box">
          <h3>Planejamento</h3>
          <p class="text-muted">Gerencie a capacidade, datas e informações essenciais num painel totalmente flexível para eventos da sua empresa ou show pessoal.</p>
        </div>
        <div class="col-md-4 feature-box">
          <h3>Simplicidade</h3>
          <p class="text-muted">Acessibilidade, praticidade e clareza. Você poderá editar tudo facilmente de forma moderna pelo sistema!</p>
        </div>
        <div class="col-md-4 feature-box">
          <h3>Exclusividade</h3>
          <p class="text-muted">Você não precisa dividir planilhas com terceiros. A FSA traz seu sistema direto para a palma da mão, fechado somente para administradores credenciados.</p>
        </div>
      </div>
    </div>

    <footer class="text-center mt-5 py-4 bg-light text-secondary">
        <p>&copy; <?= date('Y'); ?> Sistema de Eventos FSA - Desenvolvido pela Equipe 3B</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
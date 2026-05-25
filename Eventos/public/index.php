<?php 
session_start(); 
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FSA Eventos - Seu melhor momento</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    .hero-section {
      background: linear-gradient(135deg, rgba(29, 38, 113, 0.9) 0%, rgba(195, 55, 100, 0.9) 100%), url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070&auto=format&fit=crop') no-repeat center center/cover;
      color: white;
      padding: 100px 0;
      position: relative;
    }
    .hero-section::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 50px;
      background: linear-gradient(to top, #f8f9fa, transparent);
    }
    .hero-section h1 {
      font-weight: 800;
      font-size: 3.5rem;
      text-shadow: 2px 4px 10px rgba(0, 0, 0, 0.5);
    }
    .feature-card {
      border: none;
      border-radius: 15px;
      transition: 0.3s;
      background: white;
      padding: 30px 20px;
    }
    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    .feature-icon {
      font-size: 3.5rem;
      background: -webkit-linear-gradient(#C33764, #1D2671);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
  </style>
</head>

<body class="bg-light">

  <?php include('../views/layouts/navbar.php'); ?>

  <div class="hero-section text-center">
    <div class="container pb-5">
      <h1 class="display-3 mb-3">Viva a experiência<br>que você sempre quis!</h1>
      <p class="lead mb-4 fw-light text-white-50 mx-auto" style="max-width: 600px;">
        Gestão completa, praticidade na palma da mão e ingressos de forma fácil. Seja na organização do seu show ou prestigiando artistas: FSA Eventos faz tudo isso pra você.
      </p>

      <div class="mt-4 gap-3 d-flex justify-content-center flex-wrap">
        <?php if (!isset($_SESSION['usuario'])): ?>
          <a href="../views/auth/register.php" class="btn btn-warning btn-lg fw-bold rounded-pill px-5 shadow"><i class="bi bi-person-plus-fill me-2"></i>Criar Minha Conta</a>
          <a href="../views/auth/login.php" class="btn btn-outline-light btn-lg rounded-pill px-5 border-2 shadow">Já tenho acesso</a>
        <?php else: ?>
          <a href="../public/dashUser.php" class="btn btn-warning btn-lg fw-bold rounded-pill px-5 shadow"><i class="bi bi-ticket-perforated-fill me-2"></i>Comprar Ingressos</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="container my-5 pb-4">
    <div class="text-center mb-5 mt-4">
      <span class="text-primary fw-bold text-uppercase tracking-wider">A Plataforma ideal</span>
      <h2 class="fw-bold">Para quem Exige Qualidade</h2>
    </div>

    <div class="row g-4 text-center">
      <div class="col-md-4">
        <div class="card shadow-sm feature-card h-100">
          <i class="bi bi-laptop feature-icon mb-3"></i>
          <h4 class="fw-bold text-dark">Organização Limpa</h4>
          <p class="text-muted small">Crie festas, adicione categorias de convites com preços, monitore vagas. O sistema avisa todos se está tudo Lotado em instantes.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm feature-card h-100">
          <i class="bi bi-wallet2 feature-icon mb-3"></i>
          <h4 class="fw-bold text-dark">Sua Carteira Digital</h4>
          <p class="text-muted small">Mande seus clientes pararem de levar ingressos molhados de chuva! Com o checkin digital e leitura virtual via Painéis exclusivos para Eventos físicos.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm feature-card h-100">
          <i class="bi bi-shield-check feature-icon mb-3"></i>
          <h4 class="fw-bold text-dark">Blindado Contra Hacker</h4>
          <p class="text-muted small">Proteções severas nos Formulários impedindo compra após esgotar evento. Cadastro travados pra impedir Inserção SQL. 100% formal.</p>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-center py-4 bg-dark text-white-50">
    <div class="container">
      <i class="bi bi-brilliance text-warning fs-4 mb-2"></i>
      <p class="mb-0 fw-light small">&copy; <?= date('Y'); ?> Eventos FSA</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<!-- Arquivo: views/layouts/navbar.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container-md">
		<a class="navbar-brand text-warning fw-bold shadow-sm" href="#"><i class="bi bi-star-fill text-warning me-2"></i>FSA Eventos</a>

		<!-- Botão pro mobile (hamburger dropdown) -->
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSup" aria-controls="navbarSup" aria-expanded="false" aria-label="Navegar">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse mt-2 mt-lg-0" id="navbarSup">
			<ul class="navbar-nav me-auto">
				<!-- A Navbar pode apontar dinamicamente para onde o cara deve voltar pelo nivel de cargo -->
				<?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'adm'): ?>
					<!-- Admins vêem painel administrador e tela portaria -->
					<li class="nav-item"><a class="nav-link" href="../../Eventos/public/dashboard.php"><i class="bi bi-tools"></i> Controle (Painel-Admin)</a></li>
					<li class="nav-item"><a class="nav-link" href="../../Eventos/views/eventos/painel-checkin.php"><i class="bi bi-upc-scan"></i> Staff da Portaria</a></li>
				<?php elseif (isset($_SESSION['usuario'])): ?>
					<!-- Users comuns Vêem as vendas e a carteira deles -->
					<li class="nav-item"><a class="nav-link" href="../../Eventos/public/dashUser.php"><i class="bi bi-shop"></i> Vitrine de Eventos</a></li>

					<!-- ESTE É O NOVO BOTÃO BRILHANTE NA BARRA DE CIMA -->
					<li class="nav-item"><a class="nav-link fw-bold text-info border-start ms-2 ps-3" href="../../Eventos/views/ingressos/ingressos.php"><i class="bi bi-wallet2 text-white pe-1"></i> Minha Carteira</a></li>
				<?php endif; ?>
			</ul>

			<span class="navbar-text">
				<?php if (isset($_SESSION['nome'])): ?>
					Você entrou como: <span class="text-light fw-bold text-capitalize pe-3"> <?= $_SESSION['nome'] ?></span>
				<?php endif; ?>
			</span>
		</div>

	</div>
</nav>
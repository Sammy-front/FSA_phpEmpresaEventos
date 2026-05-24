<!-- Arquivo: views/layouts/navbar.php -->
<!-- Repare nos links. A Barra Inicial "/" guia o link sem erros de pastamento -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container-md">
		<a class="navbar-brand text-warning fw-bold shadow-sm" href="#"><i class="bi bi-star-fill text-warning me-2"></i>FSA Eventos</a>

		<!-- Botão pro mobile (hamburger dropdown) -->
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSup" aria-controls="navbarSup" aria-expanded="false" aria-label="Navegar">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse mt-2 mt-lg-0" id="navbarSup">
			<ul class="navbar-nav me-auto">
				<!-- LÓGICA: Nível ADMINISTRADOR -->
				<?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'adm'): ?>
					
                    <li class="nav-item"><a class="nav-link" href="/FSA/FSA_phpEmpresaEventos/Eventos/public/dashboard.php"><i class="bi bi-tools"></i> Eventos Cadastrados</a></li>
					
                    <li class="nav-item"><a class="nav-link" href="/FSA/FSA_phpEmpresaEventos/Eventos/views/usuarios_list/usuario_list.php"><i class="bi bi-people-fill"></i> Gerenciar Usuários</a></li>

					<li class="nav-item"><a class="nav-link" href="/FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php"><i class="bi bi-upc-scan"></i> Portaria / Catraca</a></li>
				
                <!-- LÓGICA: Nível USUÁRIO COMUM -->
                <?php elseif (isset($_SESSION['usuario'])): ?>
					
                    <li class="nav-item"><a class="nav-link" href="/FSA/FSA_phpEmpresaEventos/Eventos/public/dashUser.php"><i class="bi bi-shop"></i> Vitrine de Eventos</a></li>

					<li class="nav-item"><a class="nav-link fw-bold text-info border-start ms-2 ps-3" href="/FSA/FSA_phpEmpresaEventos/Eventos/views/ingressos/ingressos.php"><i class="bi bi-wallet2 text-white pe-1"></i> Minha Carteira</a></li>
				
                <?php endif; ?>
			</ul>

			<span class="navbar-text">
				<?php if (isset($_SESSION['nome'])): ?>
					Você logou como: <span class="text-light fw-bold text-capitalize pe-3"> <?= $_SESSION['nome'] ?></span>
				<?php endif; ?>
			</span>
		</div>
	</div>
</nav>
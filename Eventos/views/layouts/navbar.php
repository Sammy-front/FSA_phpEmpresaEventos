<?php
// Tenta buscar informações extras caso a pessoa atualize a si mesma na base
require __DIR__ . '/../../config/conexao.php';
$modalUser = [];
if (isset($_SESSION['usuario'])) {
    $mailConsulta = mysqli_real_escape_string($conexao, $_SESSION['usuario']);
    $queryBusca = mysqli_query($conexao, "SELECT * FROM usuarios WHERE email = '$mailConsulta'");
    if (mysqli_num_rows($queryBusca) > 0) {
        $modalUser = mysqli_fetch_assoc($queryBusca);
    }
}
?>

<!-- NAVBAR GLOBAL E ELEGANTE -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top" style="background: rgba(22, 25, 28, 0.95); backdrop-filter: blur(10px); border-bottom: 2px solid #ffc107;">
    <div class="container-md py-1">

        <a class="navbar-brand text-white fw-bold fs-4 d-flex align-items-center" style="letter-spacing: -0.5px;">
            <div class="bg-warning text-dark rounded d-inline-flex p-2 me-2 shadow-sm"><i class="bi bi-star-fill"></i></div>
            FSA<span class="text-warning ms-1">Eventos</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSup">
            <i class="bi bi-list fs-1 text-warning"></i>
        </button>

        <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarSup">
            <ul class="navbar-nav me-auto fw-medium fs-6 gap-1 px-3">

                <!-- LINKS ADMIN -->
                <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'adm'): ?>
                    <li class="nav-item">
                        <a class="nav-link px-3 btn-hover text-white-50 rounded" href="/FSA/FSA_phpEmpresaEventos/Eventos/public/dashboard.php"><i class="bi bi-calendar2-event me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 btn-hover text-white-50 rounded" href="/FSA/FSA_phpEmpresaEventos/Eventos/views/usuarios_list/usuario_list.php"><i class="bi bi-people-fill me-1"></i> Perfis</a>
                    </li>
                    <li class="nav-item border-start border-secondary ms-2 ps-2">
                        <a class="nav-link text-warning px-3 fw-bold rounded hover-golden" href="/FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php"><i class="bi bi-upc-scan fs-5 align-middle"></i> Catraca Inteligente</a>
                    </li>

                <!-- LINKS USER -->
                <?php elseif (isset($_SESSION['usuario'])): ?>
                    <li class="nav-item">
                        <a class="nav-link px-3 btn-hover text-light rounded" href="/FSA/FSA_phpEmpresaEventos/Eventos/public/dashUser.php"><i class="bi bi-shop text-warning me-1"></i> Ingressos à Venda</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="nav-link text-dark fw-bold bg-warning rounded-pill px-3 shadow-sm ticket-pulse" href="/FSA/FSA_phpEmpresaEventos/Eventos/views/ingressos/ingressos.php">
                            <i class="bi bi-ticket-detailed-fill me-1"></i> Meus Ingressos
                        </a>
                    </li>
                <?php endif; ?>

            </ul>

            <!-- IDENTIDADE / CONTA -->
            <div class="d-flex align-items-center mt-3 mt-lg-0 gap-3 border-start border-secondary ps-lg-4 ms-lg-2 pt-3 pt-lg-0">
                <?php if (isset($_SESSION['usuario'])): ?>

                    <!-- Disparador da Modal -->
                    <button class="btn btn-dark rounded-pill px-4 d-flex align-items-center gap-2 border border-warning shadow-sm user-badge transition" type="button" data-bs-toggle="modal" data-bs-target="#modalMinhaConta">
                        <i class="bi bi-person-circle fs-5 text-warning"></i>
                        <span class="text-light fw-semibold text-truncate" style="max-width: 150px;"><?= $_SESSION['nome'] ?></span>
                    </button>

                    <!-- Desconectar -->
                    <a href="/FSA/FSA_phpEmpresaEventos/Eventos/logout.php" title="Sair do Sistema" class="btn btn-outline-danger border-0 rounded-circle p-2 px-3 shadow-sm d-flex align-items-center justify-content-center transition"><i class="bi bi-power fs-5"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- MODAL MINHA CONTA INTEGRADORA -->
<?php if (isset($_SESSION['usuario'])): ?>
    <div class="modal fade" id="modalMinhaConta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <div class="modal-header bg-dark text-white border-bottom border-warning border-3 px-4 py-3">
                    <h5 class="modal-title d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-shield-check text-warning fs-3"></i> Detalhes do Perfil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="/FSA/FSA_phpEmpresaEventos/Eventos/controllers/usuarioControllers.php">
                    <div class="modal-body px-4 py-4 bg-light">

                        <!-- Campos Preenchidos com Lógica Bootstrap 'form-floating' -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control fw-bold text-dark shadow-sm" id="u_nome" name="nome" placeholder="..." value="<?= htmlspecialchars($modalUser['nome']) ?>" required>
                            <label for="u_nome" class="text-secondary"><i class="bi bi-person"></i> Nome do Portador</label>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6 form-floating">
                                <input type="date" class="form-control shadow-sm" id="u_data" name="data_nascimento" value="<?= htmlspecialchars($modalUser['data_nascimento']) ?>" required>
                                <label for="u_data" class="ms-2 text-secondary">Nascimento</label>
                            </div>

                            <div class="col-md-6 form-floating">
                                <input type="text" class="form-control bg-transparent fw-bold text-primary shadow-sm" value="<?= strtoupper($_SESSION['cargo']) ?>" disabled readonly>
                                <label class="ms-2 text-secondary">Privilégio da Conta</label>
                            </div>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="email" class="form-control shadow-sm" id="u_mail" name="email" placeholder="..." value="<?= htmlspecialchars($modalUser['email']) ?>" required>
                            <label for="u_mail" class="text-secondary"><i class="bi bi-envelope"></i> E-mail (Seu Login)</label>
                        </div>

                        <!-- Zona de Perigo -->
                        <div class="p-3 border rounded-3 bg-white shadow-sm mt-4">
                            <label class="form-label fw-bold text-danger"><i class="bi bi-key-fill"></i> Substituir Credencial:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" name="senha" placeholder="Digite apenas caso vá alterar sua senha">
                            </div>
                            <small class="text-muted d-block mt-2" style="font-size:0.8rem">Ocultando ou Deixando em branco a criptografia original atual continuará ativa na DB.</small>
                        </div>

                    </div>

                    <div class="modal-footer bg-light px-4 pb-4 pt-0 border-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Manter como Está</button>
                        <button type="submit" name="atualizar_minha_conta" class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm text-dark"><i class="bi bi-database-check me-1"></i> Confirmar Saldo BD</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Estilos Auxiliares (Sinta a Mágica das classes Hover no Mouse!) -->
<style>
    .btn-hover { transition: color 0.2s, background-color 0.2s; }
    .btn-hover:hover { color: #fff !important; background-color: rgba(255, 255, 255, 0.1); }
    .hover-golden { transition: all 0.3s; }
    .hover-golden:hover { background: #ffc107 !important; color: #000 !important; box-shadow: 0 0 10px rgba(255, 193, 7, 0.5); }
    .user-badge:hover { background-color: rgba(255, 255, 255, 0.1); }
    .transition { transition: all 0.3s ease-in-out; }
    .ticket-pulse:hover { transform: translateY(-2px); filter: brightness(1.05); }
</style>
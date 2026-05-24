<?php
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

<!-- NAVBAR GLOBAL  -->
<nav class="navbar navbar-expand-lg navbar-dark shadow" style="background: linear-gradient(135deg, #1f2326, #16191c); border-bottom: 2px solid #ffc107;">
    <div class="container-md py-1">

        <a class="navbar-brand text-warning fw-bold fs-4 d-flex align-items-center" href="/FSA/FSA_phpEmpresaEventos/Eventos/public/index.php">
            <i class="bi bi-star-fill me-2 fs-3 text-warning drop-shadow"></i>
            FSA Eventos
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSup">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarSup">
            <ul class="navbar-nav me-auto fw-medium fs-6 gap-2">

                <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'adm'): ?>
                    <li class="nav-item">
                        <a class="nav-link px-3 btn-hover" href="/FSA/FSA_phpEmpresaEventos/Eventos/public/dashboard.php"><i class="bi bi-calendar-event"></i> Gestão de Shows</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 btn-hover" href="/FSA/FSA_phpEmpresaEventos/Eventos/views/usuarios_list/usuario_list.php"><i class="bi bi-people-fill"></i> Perfis e Permissões</a>
                    </li>
                    <li class="nav-item border-start border-secondary ms-1 ps-2">
                        <a class="nav-link text-warning px-3 fw-bold" href="/FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php"><i class="bi bi-upc-scan fs-5 align-middle"></i> Painel da Catraca</a>
                    </li>

                <?php elseif (isset($_SESSION['usuario'])): ?>
                    <li class="nav-item">
                        <a class="nav-link px-3 text-light" href="/FSA/FSA_phpEmpresaEventos/Eventos/public/dashUser.php"><i class="bi bi-shop text-warning"></i> Loja/Vitrine</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-info fw-bold bg-dark rounded px-3 border border-secondary shadow-sm" href="/FSA/FSA_phpEmpresaEventos/Eventos/views/ingressos/ingressos.php">
                            <i class="bi bi-wallet2 text-white pe-1"></i> Minha Carteira / QR's
                        </a>
                    </li>
                <?php endif; ?>

            </ul>

            <div class="d-flex align-items-center mt-3 mt-lg-0 gap-3 border-start border-secondary ps-4 ms-2">
                <?php if (isset($_SESSION['usuario'])): ?>

                    <button class="btn btn-outline-light rounded-pill px-4 d-flex align-items-center gap-2 border-opacity-25 border shadow-sm transition" type="button" data-bs-toggle="modal" data-bs-target="#modalMinhaConta" style="backdrop-filter: blur(5px); background: rgba(255, 255, 255, 0.05);">
                        <i class="bi bi-person-circle fs-5 text-warning"></i>
                        <span><?= $_SESSION['nome'] ?></span>
                    </button>

                    <a href="/FSA/FSA_phpEmpresaEventos/Eventos/logout.php" title="Sair do Sistema Seguro" class="btn btn-danger rounded-circle px-2 py-1 shadow-sm"><i class="bi bi-power fs-5"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<?php if (isset($_SESSION['usuario'])): ?>
    <div class="modal fade" id="modalMinhaConta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <div class="modal-header bg-dark text-white border-bottom border-warning border-3 px-4 py-3">
                    <h5 class="modal-title d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-shield-check text-warning fs-3"></i> Configurações Pessoais
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="/FSA/FSA_phpEmpresaEventos/Eventos/controllers/usuarioControllers.php">
                    <div class="modal-body px-4 py-4 bg-light">

                        <div class="alert alert-secondary d-flex align-items-center p-2 mb-4">
                            <i class="bi bi-info-circle fs-3 text-secondary me-3 ms-2"></i>
                            <small>Para gerenciar seus ingressos comprados e ver Códigos Vá para: <br><b class="text-dark">Minha Carteira > Histórico.</b></small>
                        </div>

                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" id="u_nome" name="nome" placeholder="..." value="<?= htmlspecialchars($modalUser['nome']) ?>" required>
                            <label for="u_nome" class="fw-bold"><i class="bi bi-person"></i> Nome de Exibição / Bilhete</label>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="u_data" class="form-label text-muted small fw-bold">Nascimento (Muda perfil faixa-etaria)</label>
                                <input type="date" class="form-control" id="u_data" name="data_nascimento" value="<?= htmlspecialchars($modalUser['data_nascimento']) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold"><i class="bi bi-patch-check-fill text-primary"></i> Nível Sistema</label>
                                <input type="text" class="form-control bg-transparent text-muted" value="<?= strtoupper($_SESSION['cargo']) ?>" readonly disabled>
                            </div>
                        </div>

                        <div class="mb-4 form-floating">
                            <input type="email" class="form-control" id="u_mail" name="email" placeholder="..." value="<?= htmlspecialchars($modalUser['email']) ?>" required>
                            <label for="u_mail" class="fw-bold text-dark"><i class="bi bi-envelope"></i> E-mail de Conexão DB</label>
                        </div>

                        <div class="border-top pt-3 pb-2 mt-4 px-2">
                            <label for="u_pass" class="form-label fw-bold"><i class="bi bi-key"></i> Solicitar Troca de Senha Criptografada:</label>
                            <input type="password" class="form-control border-dark border-opacity-25 border-2 shadow-sm" id="u_pass" name="senha" placeholder="Digite apenas para Trocar a existente...">
                            <div class="form-text mt-2"><i class="bi bi-lock-fill"></i> Caso não queira sofrer com logs desatualizados deixe vazio pra sua hash atual valer!.</div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 border-0 mb-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fechar Modal</button>
                        <button type="submit" name="atualizar_minha_conta" class="btn btn-warning fw-bold px-4">Modificar Seguraça / Database</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .btn-hover {
        transition: 0.3s;
        color: #adb5bd;
    }

    .btn-hover:hover {
        color: #fff;
        transform: translateY(-2px);
    }
</style>
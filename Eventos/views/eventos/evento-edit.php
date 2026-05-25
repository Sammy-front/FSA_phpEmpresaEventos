<?php
session_start();
require __DIR__ . '/../../config/conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['cargo'] !== 'adm') {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: evento-view.php');
    exit;
}

$evento_id = mysqli_real_escape_string($conexao, $_GET['id']);
$sql = "SELECT * FROM eventos WHERE id = '$evento_id'";
$query = mysqli_query($conexao, $sql);

if (mysqli_num_rows($query) == 0) {
    $_SESSION['mensagem'] = "Erro: Evento não encontrado para edição.";
    header('Location: evento-view.php');
    exit;
}

$evento = mysqli_fetch_assoc($query);
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Editar Evento - Administração</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #eef2f5; color: #333; }
        .admin-card { border: none; border-radius: 1rem; box-shadow: 0 15px 35px rgba(0,0,0,0.1); background: #ffffff; }
        .admin-header { 
            background: linear-gradient(135deg, #16191c 0%, #2c3136 100%); 
            color: white; 
            padding: 2.5rem 2rem; 
            border-top-left-radius: 1rem; 
            border-top-right-radius: 1rem;
            position: relative;
            overflow: hidden;
        }
        .admin-header::after {
            content: ""; position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: #ffc107;
        }
        .section-badge { 
            display: inline-block; background: #16191c; color: #ffc107; padding: 0.5rem 1.2rem; 
            border-radius: 50rem; font-size: 0.85rem; font-weight: 700; letter-spacing: 1px; 
            text-transform: uppercase; margin-bottom: 1.5rem; box-shadow: 0 4px 10px rgba(0,0,0,0.15); 
        }
        .custom-input { 
            background-color: #f4f6f9; border: 1px solid #dee2e6; border-radius: 0.6rem; padding: 0.75rem 1rem; transition: all 0.3s; color: #212529; font-weight: 500;
        }
        .custom-input:focus { 
            background-color: #ffffff; border-color: #ffc107; box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.2); outline: none;
        }
        .btn-gradient { 
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #000; border: none; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; transition: 0.3s;
        }
        .btn-gradient:hover { 
            background: linear-gradient(135deg, #ff9800 0%, #e68a00 100%); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(255, 152, 0, 0.4); 
        }
    </style>
</head>

<body class="bg-light">
    <?php include('../layouts/navbar.php'); ?>

    <div class="container mt-5 mb-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="admin-card">
                    
                    <div class="admin-header d-flex justify-content-between align-items-center">
                        <div>
                             <span class="badge bg-warning text-dark mb-2 px-2 py-1"><i class="bi bi-pencil-square"></i> Modo de Edição</span>
                             <h3 class="mb-0 fw-bolder text-white">Editar Evento</h3>
                             <p class="mb-0 text-white-50 mt-1">Atualize as informações do evento selecionado.</p>
                        </div>
                        <a href="evento-view.php" class="btn btn-outline-light rounded-pill px-4 fw-bold transition"><i class="bi bi-x-lg me-1"></i> Cancelar</a>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">

                        <div class="alert alert-warning border border-warning border-opacity-50 d-flex gap-3 mb-4 rounded-3 p-3 text-dark align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-3 text-warning ms-1"></i> 
                            <div class="fs-6 lh-sm">
                                <strong class="d-block pb-1">Atenção ao alterar dados ativos!</strong> 
                                Alterações de nome, local ou data refletirão imediatamente na vitrine pública dos usuários e nos ingressos já comprados. Administre com cautela.
                            </div> 
                        </div>  
                            
                        <form action="../../controllers/eventoControllers.php" method="POST">

                            <input type="hidden" name="evento_id" value="<?= $evento['id'] ?>">

                            <div class="section-badge"><i class="bi bg-transparent bi-card-heading me-2"></i>1. Dados Principais</div>

                            <div class="row g-4 mb-5">
                               <div class="col-md-8">
                                    <label class="form-label fw-bold text-dark">Nome do Evento <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" value="<?= htmlspecialchars($evento['nome']) ?>" class="custom-input w-100 fw-bold" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-dark">Localidade <span class="text-danger">*</span></label>
                                    <input type="text" name="localidade" value="<?= htmlspecialchars($evento['localidade']) ?>" class="custom-input w-100" required>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label fw-bold text-dark">Descrição do Evento</label>
                                    <textarea name="descricao" class="custom-input w-100" rows="4" required><?= htmlspecialchars($evento['descricao']) ?></textarea>
                                </div>
                            </div>

                            <div class="section-badge"><i class="bi bg-transparent bi-calendar-range me-2"></i>2. Logística & Status</div>
                            
                            <div class="row g-4 mb-4 bg-light p-3 rounded-4 border border-light-subtle shadow-sm align-items-end">
                                
                                <div class="col-md-4">
                                   <label class="form-label fw-bold text-dark mb-2">Data do Evento <span class="text-danger">*</span></label> 
                                   <input type="date" name="data_evento" value="<?= $evento['data_evento'] ?>" class="custom-input w-100" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-dark mb-2">Horário de Abertura <span class="text-danger">*</span></label>
                                    <input type="text" name="horario" value="<?= htmlspecialchars($evento['horario']) ?>" class="custom-input w-100 text-center fw-bold" required>
                                </div>

                                <div class="col-md-2">
                                   <label class="form-label fw-bold text-dark mb-2">Capacidade <span class="text-danger">*</span></label> 
                                   <input type="number" name="capacidade" value="<?= $evento['capacidade'] ?>" class="custom-input w-100 text-center fw-bold" required>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-dark mb-2">Status de Visibilidade <span class="text-danger">*</span></label>
                                    <select name="status_evento" class="custom-input w-100 fw-bold">
                                        <option value="aberto" <?= $evento['status_evento'] == 'aberto' ? 'selected' : '' ?>>🟢 Publicado (Aberto)</option>
                                        <option value="fechado" <?= $evento['status_evento'] == 'fechado' ? 'selected' : '' ?>>🔴 Oculto (Fechado)</option>
                                    </select>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end gap-3 border-top border-2 pt-4 mt-5">
                                <button type="submit" name="update_evento" class="btn btn-gradient btn-lg px-5 py-3 rounded-pill shadow-sm">
                                   <i class="bi bi-cloud-arrow-up-fill me-2 fs-5"></i> Salvar e Atualizar Evento
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
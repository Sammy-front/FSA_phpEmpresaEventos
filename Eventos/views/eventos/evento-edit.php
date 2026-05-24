<?php
session_start();
require __DIR__ . '/../../config/conexao.php';
if (!isset($_SESSION['usuario'])) { header('Location: ../auth/login.php'); exit; }

if (!isset($_GET['id'])) { header('Location: evento-view.php'); exit; }
$evento_id = mysqli_real_escape_string($conexao, $_GET['id']);
$query = mysqli_query($conexao, "SELECT * FROM eventos WHERE id = '$evento_id'");
if (mysqli_num_rows($query) == 0) { die("Ficha ID N/ Encotrad! Cancel... Sistema de falha de segurança "); }
$evO = mysqli_fetch_assoc($query);
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Definições Gerais Edit. Atuais de <?= htmlspecialchars($evO['nome']) ?> / ADMIN Edit!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">
    <?php include('../layouts/navbar.php'); ?>

    <div class="container mt-5 mb-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-dark border-bottom border-danger border-4 text-white p-4 d-flex justify-content-between align-items-center">
                        <div>
                             <h4 class="mb-0 text-white"><i class="bi bi-pencil-square text-danger me-2"></i> Configurações: Show Edit. </h4>
                        </div>
                        <a href="evento-view.php" class="btn btn-outline-light btn-sm px-4 rounded-pill">Volta Para View Gestao / Cencela!</a>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">

                        <div class="alert alert-secondary border d-flex gap-3 mb-4 rounded-3 p-3 text-muted align-items-center"><i class="bi bi-exclamation-triangle fs-1"></i> <div class="fs-6 lh-sm"><strong class="d-block text-dark fw-bold pb-1 "> Alterar Base do evento Vivo.. ? / Ríscido </strong> Ao realizar edição das coisas Atuais sobre esse ID Event. O Frontend Vitrines da "Store" altera autommatic e Clientes poderão visualizar q o Event Ocutlado Canselou Ou Alterou-Data!! Tome decissions precisas meste.! </div> </div>  
                            
                        <form action="../../controllers/eventoControllers.php" method="POST">
                            <!-- Injeção q liga tudo Pro BD Acionar a Update (Magica oculta Front): -->
                            <input type="hidden" name="evento_id" value="<?= $evO['id'] ?>">

                            <div class="row g-3 mb-3">
                               <div class="col-md-8 form-floating">
                                    <input type="text" id="enome" name="nome" value="<?= htmlspecialchars($evO['nome']) ?>" class="form-control fw-bold border-dark shadow-sm" required>
                                    <label for="enome" class="ms-2">Alterar/Confirm: O Nome Event / Vitrine</label>
                                </div>
                                <div class="col-md-4 form-floating">
                                    <input type="text" id="elocal" name="localidade" value="<?= htmlspecialchars($evO['localidade']) ?>" class="form-control" required>
                                    <label for="elocal" class="ms-2 text-danger">Modifi. Espaço Cidedades</label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted ps-1 small"><i class="bi bi-pen me-1"></i>Texto Publico Ocorrida do Show Explicacional::</label>
                                <textarea name="descricao" class="form-control shadow-sm border border-secondary" rows="4"><?= htmlspecialchars($evO['descricao']) ?></textarea>
                            </div>

                            <hr class="text-secondary opacity-25">
                            
                            <div class="row align-items-end g-3">
                                
                                <div class="col-sm-6 col-md-3">
                                   <label class="form-label text-muted fw-bold ps-1 mb-1 small">Datas Oficial Show</label> 
                                   <input type="date" name="data_evento" value="<?= $evO['data_evento'] ?>" class="form-control p-3 bg-light border border-dark" required>
                                </div>

                                <div class="col-sm-6 col-md-2 form-floating">
                                    <input type="text" id="tpoo" name="horario" value="<?= htmlspecialchars($evO['horario']) ?>" class="form-control border fw-bold text-center p-2 mt-4 text-primary bg-light" required>
                                </div>

                                <div class="col-md-3">
                                   <label class="form-label text-danger fw-bolder mb-1 small ms-1 ">Modf. Máxima Absolut / FIsica!</label> 
                                    <div class="input-group"> 
                                      <span class="input-group-text bg-white"><i class="bi bi-people"></i></span> 
                                      <input type="number" name="capacidade" value="<?= $evO['capacidade'] ?>" class="form-control fw-bold shadow-sm p-3 " required>
                                    </div> 
                                </div>
                                
                                <div class="col-md-4 form-floating mt-4 ">
                                     <select name="status_evento" id="fStststusBvcsfEEditavlsfsSsf2OculkSttsE" class="form-select bg-warning-subtle fw-bold text-uppercase border-dark shadow-sm h-100">
                                        <option value="aberto" <?= $evO['status_evento'] == 'aberto' ? 'selected' : '' ?>>🌐 LER / Aberto e Livre !! P' COMPR </option>
                                        <option value="fechado" <?= $evO['status_evento'] == 'fechado' ? 'selected' : '' ?>>🔴 Srr- Fech/Escond e Lockk. !! </option>
                                    </select>
                                    <label class="text-danger fw-bolder ms-2 mt-2" for="fStststusBvcsfEEditavlsfsSsf2OculkSttsE"><i class="bi bi-shield-exclamation text-dark"></i> Botã de Controle Gatilo. S  _ : </label>  
                                </div>

                            </div>
                           
                            <div class="mt-5 text-end d-flex gap-3 justify-content-end bg-light p-3 border border-bottom-0 border-end-0 border-start-0 mt-5 pt-4   rounded ">
                                <button type="submit" name="update_evento" class="btn btn-dark btn-lg shadow rounded px-5">
                                   Re Salve Banco_   Update Conforme Acimo 🚀 !! >!  
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
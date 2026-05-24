<?php
session_start();
require __DIR__ . '/../../config/conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['cargo'] !== 'adm') {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Painel Mestre - FSA Events G9!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .accordion-icon { transition: transform 0.3s; }
        tr[aria-expanded="true"] .accordion-icon { transform: rotate(180deg); color: #0d6efd!important;}
        .main-row { cursor: pointer; transition: background 0.1s;}
        .main-row:hover { background-color: #f1f4f8!important;}
    </style>
</head>
<body class="bg-light pb-5 mb-5">
    
    <?php include('../layouts/navbar.php'); ?>

    <div class="container mt-4 pb-3">
        <?php include('../layouts/mensagem.php'); ?>

        <div class="d-flex justify-content-between align-items-center mb-4 mt-3 pb-2 border-bottom border-dark border-3">
            <h4 class="mb-0 fw-bold text-dark d-flex align-items-center"><i class="bi bi-boxes fs-1 me-3 text-warning"></i> Painel Relacional <span class="ms-2 badge text-bg-warning ms-3 shadow">Aceso ROOT do System ADms DB !</span></h4>
            <a href="evento-create.php" class="btn btn-warning rounded-pill shadow-sm fw-bold px-4 border border-1 border-secondary"><i class="bi bi-calendar2-plus me-1 text-danger"></i>  NOVA Grade SHOW (add.) !!</a>
        </div>

        <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-dark p-3 text-center border-bottom border-secondary ">
                 <span class="text-white opacity-75 fst-italic tracking-wider ">Sua DB-Base Ocular. Clique na grade Row/Linha do SHOW a se analizar ,e Exapanda Visual P pudes ler ou dar DELETE_</span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped m-0 align-middle">
                        
                        <thead class="table-warning border-dark text-dark fw-bold border-bottom">
                            <tr>
                                <th class="ps-4">Vitrine do BD  Shows! -> [TITLE]</th>
                                <th>Cidade LUG 📍 </th>
                                <th>Marca Calendario Base: </th>
                                <th width="12%">Sys_Permitions Gatila :</th>
                                <th class="text-center" width="8%">Info++⬇ </th>
                            </tr>
                        </thead>

                        <tbody class="fs-6 fw-medium text-secondary text-uppercase border-light" >
                            <?php
                            $resultO  = mysqli_query($conexao, "SELECT * FROM eventos ORDER BY id DESC");
                            
                            while ($eVttBase = mysqli_fetch_assoc($resultO )) {
                                $dropIdGrdShowDsd = "collpaseRootAks" . $eVttBase['id'];
                                $ePintaCrtSttstts = ($eVttBase['status_evento'] == 'fechado') ? 'bg-danger ' : 'bg-success shadow border text-dark fw-bold';
                            ?>

                            <tr class="main-row" data-bs-toggle="collapse" data-bs-target="#<?= $dropIdGrdShowDsd ?>"  style="cursor: cell;">
                                
                                <td class="ps-4 text-dark fw-bolder fs-5 text-truncate" style="max-width:320px;" title="... "><i class="bi bi-tags opacity-25 fw-normal align-baseline ">  <br> </i> <?= htmlspecialchars($eVttBase['nome']) ?></td>
                                
                                <td> <span class=" bg-white text-muted fw-bold d-inline  rounded-pill fs-6   px-3 py-1 text-center " style="font-weight:450 " ><i class="bi bi-arrow-return-right me-1 d-none " ></i>    <?=$eVttBase['localidade']?>      </span> </td> 

                                <td class="fst-italic"><span class="badge bg-secondary p-2 " >  <?= date(' D • Y / M  . d _>_!', strtotime($eVttBase['data_evento'])) ?> </span> </td>
                                
                                <td><span class="badge fs-6 rounded p-2   <?= $ePintaCrtSttstts ?>"><i class="bi bi-disc align-text-bottom  "></i>  <?= $eVttBase['status_evento'] ?> / VIsions!!  .   </span></td>

                                <td class="text-center ">
                                      <i class="bi bi-chevron-down accordion-icon text-dark opacity-50 display-6"></i>
                                </td>

                            </tr>
                            
                            <tr >
                              <td colspan="6" class="p-0 border-0 ">
                                <!-- DROPDWN AREA SANFOSNA QUE CONTROLE ACTIONS GERAL DESTE IDS (Mascara ) ->> -->
                                <div class="collapse text-muted " id="<?= $dropIdGrdShowDsd ?>"> 
                                  
                                    <div class="m-0 mb-3 border bg-dark text-white rounded  d-flex mx-3 my-2 ms-5 me-5 shadow position-relative opacity-100">
                                       
                                        <!-- Blocl Left com DADIS TXTs Exteneso -->   
                                        <div class="p-3 w-75  " style="min-width:30%" > <h5 class="fw-bolder   opacity-50"># <u title="Texto De Front Desk descrpç"><i class="bi bi-book ms-1 d-inline px-2"> </i> <?= $eVttBase['id']?></u> <span class="d-none"> . </span> </h5>  
                                          <p class="ps-3 ms-2 mb-0  text-light fw-normal bg-secondary border border-secondary  bg-opacity-25 rounded px-2 pt-2 p-1 border-opacity-75 "><i class="bi bi-quote ms-n2 p-1 display-5 opacity-25 pe-1 pb-n2"> </i> <i class=" fst-italic " > <?=$eVttBase['descricao']?> " </i>  
                                        </p>
                                        </div>

                                        <div class=" w-25 mt-5 d-flex fs-4    ms-0 "><span class=" text-primary  pt-5 ps-4"><i class="bi bi-speedometer   text-warning   display-6 ps-2 fst-normal ps-3 lh-sm fw-bold " ><span class="ps-1 bg-white align-baseline border d-inline shadow  px-2 pb-0 pt-0 py-0 ms-1 d-block opacity-100 me-2 text-danger ms-4 " ><b class="opacity-100"> <?=$eVttBase['capacidade']?></b>  <sub> <i class="fst-italic border-bottom "> Pessoas Limiites / Max Db!.    <br >   </sub ></i> </span>   </span>   </p >  </span>     </div>
                                          

                                        <!-- Div Right CONTOL  BOTOES P EST ID LOOPED NOW   -->   
                                        <div class="p-3   m-2 shadow bg-warning   rounded flex-column gap-2 text-dark pt-5 fs-6 lh-base   w-100 ms-0 ms-5 border border-white ps-3">
                                          <h5>    O Quë tu Fars co ess base Aq! ? : Admin </h5> 
                                             <div class="text-end mb-1 mt-4 ">  
                                                 <a href="evento-edit.php?id=<?= $eVttBase['id'] ?>" class="btn w-75 border-secondary fw-bold rounded shadow-sm border border-secondary   btn-lg bg-light text-success bg-gradient-dark ms-auto mb-1  text-center   lh-base  btn-light btn" > > Alterar Valores ou Nomes Atuiasi 📝!!! </a><br>

                                                 <form action="../../controllers/eventoControllers.php" method="POST"> <button type="submit" name="delete_evento" value="<?= $eVttBase['id'] ?>" class="btn btn-danger btn-lg text-end shadow mt-2 p-3 pb-1 border btn border w-75 rounded bg-dark lh-sm ps-4 fst-normal" onclick="return confirm('ALERTA MASTER!: Tem CTZA! DESTE COMand?? Ao Confirm deletona pra SEMMMEEE os LOTE os Tickte, Cidades O ID todo Dessee do serviodR da empressa !!!! (E PESSssos que tinha eles Ficalho Tristez...) >! ;O \nConfirmms Apsgal!?!_');" >      DestruiR do Sistema Isso Agr / Trash >>>> </button>    </form>
                                                </div>  

                                          
                                          </div>
                                         <div class="pe-0 m-0 w-25 border-top d-inline rounded ps-4 mb-2 fst-normal "><b  class="fw-bold mt-2 pb-2  lh-sm " > _ Portão/Hora Fichad em banco> !  _ </b><div class=" badge display-1 p-2 pt-2 px-1 text-black fst-normal mb-5 align-middle d-block fw-light fs-1   " ><b class=" fw-bolder border border-0 px-2 py-0 border-opacity-75 ms-3 rounded mt-2 lh-lg fs-1 " style="background: yellow;">     _>_!  _  <?=$eVttBase['horario']?>h</b></div>     </div>   
                                           
                                           <div class=" d-grid h-75 p-5  mb-5 border ms-4 mt-5 rounded bg-warning text-dark"><i class="bi bi-star"> ! End da Tabele e Box ! :) ; Fimm <i class="bi-balloon display-4 border border-1 border-opacity-75 ms-4 border-end bg-gradient"> _._ </_   ></_ ><_- )>>..</div> 
                                            

                                    </div>
                                    
                                </div>

                              </td>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
    
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
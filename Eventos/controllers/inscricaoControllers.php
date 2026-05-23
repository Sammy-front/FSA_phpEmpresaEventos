<?php
// ==============================================================================
// Arquivo: controllers/inscricaoControllers.php
// ==============================================================================
session_start();
require __DIR__ . '/../config/conexao.php';

if (isset($_POST['realizar_inscricao'])) {
    
    // 1. Coleta os dados do Formulário
    $id_evento = mysqli_real_escape_string($conexao, $_POST['id_evento']);
    $id_tipo_ingresso = mysqli_real_escape_string($conexao, $_POST['id_tipo_ingresso']);
    $quantidade = (int) $_POST['quantidade'];

    if($quantidade < 1) {
        $_SESSION['mensagem'] = "Ação Inválida! A quantidade precisa ser no mínimo 1.";
        header('Location: ../public/dashUser.php'); exit;
    }

    // 2. Coletar o ID do Usuário Através do E-mail salvo no login.php
    $email_logado = $_SESSION['usuario'];
    $sql_user = mysqli_query($conexao, "SELECT id FROM usuarios WHERE email = '$email_logado' LIMIT 1");
    if(mysqli_num_rows($sql_user) == 0){
        die("Usuário inválido ou Sessão expirou.");
    }
    $id_usuario = mysqli_fetch_assoc($sql_user)['id'];

    // 3. REGRA DE NEGÓCIO DA LOTAÇÃO/CAPACIDADE ABSOLUTA 🛡️ (ANTI-SUPER LOTAÇÃO/INCENDIO!)
    // Vamos somar quantos ingressos o evento totalizou.
    $verifica_capacidade = mysqli_query($conexao, "SELECT capacidade FROM eventos WHERE id = '$id_evento' LIMIT 1");
    $capacidade_maxima = mysqli_fetch_assoc($verifica_capacidade)['capacidade'];

    $verifica_vendidos = mysqli_query($conexao, "SELECT COUNT(id) as total_vendidos FROM inscricoes WHERE id_evento = '$id_evento' AND status_inscricao != 'cancelada'");
    $vendidos = mysqli_fetch_assoc($verifica_vendidos)['total_vendidos'];

    // Conta principal de proteção
    if ( ($vendidos + $quantidade) > $capacidade_maxima ) {
        $sobrou = $capacidade_maxima - $vendidos;
        $_SESSION['mensagem'] = "❌ Venda bloqueada. O evento atingiu sua capacidade. Temos apenas $sobrou vaga(s) restante(s).";
        header("Location: ../views/inscricoes/inscricao.php?id=$id_evento");
        exit;
    }

    // 4. VERIFICAÇÃO AUTOMATICA DE LOTE (Checa se HOJE há lote ativo do preço atual)
    $id_lote = 'NULL'; 
    $check_lote = mysqli_query($conexao, "SELECT id FROM lotes WHERE id_evento = '$id_evento' AND NOW() BETWEEN data_inicio AND data_fim ORDER BY id ASC LIMIT 1");
    if (mysqli_num_rows($check_lote) > 0) {
        $loteAtivo = mysqli_fetch_assoc($check_lote);
        $id_lote = $loteAtivo['id']; // Ele marca que esses convites pertecem a Lote específico!
    }


    // 5. REGISTRAR DE FATO! (Usaremos o Laço/Loop de Repetição Baseado na quantidade pedida).
    // O Loop salva ingressos singulares no seu banco, preparando pro checkin bipar todos separadamente.
    // REGRA NO STATUS: Adicionado diretão "paga", emulação que cartão passou na hora e a pessoa tem vaga e entrada.
    $inseridos = 0;

    for ($i = 0; $i < $quantidade; $i++) {
        $sql_inserir = "INSERT INTO inscricoes (id_evento, id_usuario, id_tipo_ingresso, id_lote, status_inscricao) 
                        VALUES ('$id_evento', '$id_usuario', '$id_tipo_ingresso', $id_lote, 'paga')";
        
        mysqli_query($conexao, $sql_inserir);
        if(mysqli_affected_rows($conexao) > 0) {
            $inseridos++;
        }
    }

    // 6. Respostas ao Frontend e Retorno da Página
    if ($inseridos == $quantidade) {
        if ($quantidade == 1) {
            $_SESSION['mensagem'] = "✅ Seu Ticket para a festa foi emitido e salvo na sua carteira digital! Status: PAGO.";
        } else {
            $_SESSION['mensagem'] = "✅ Você comprou <b>$quantidade convites</b> simultâneos. Aproveite muito o Evento com seus convidados. Status: PAGOS.";
        }
    } else {
         $_SESSION['mensagem'] = "Atenção: Apenas $inseridos Ingressos registrados com sucesso! Fale com o ADM do sistema sobre instabilidade do banco!";
    }

    // O melhor cenário: após sucesso, leva de volta para a Visão dos usuários!
    header('Location: ../public/dashUser.php');
    exit;
}
?>
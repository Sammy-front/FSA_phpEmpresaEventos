<?php
// Arquivo: controllers/checkinControllers.php
session_start();
require __DIR__ . '/../config/conexao.php'; 

// ==========================================
// REALIZAR O CHECK-IN (Bipar Entrada na Catraca)
// ==========================================
if (isset($_POST['realizar_checkin'])) {
    
    $id_inscricao = mysqli_real_escape_string($conexao, $_POST['id_inscricao']);
    $operador = isset($_SESSION['nome']) ? mysqli_real_escape_string($conexao, $_SESSION['nome']) : 'Sistema';

    $sql_busca_inscricao = "SELECT id, status_inscricao FROM inscricoes WHERE id = '$id_inscricao' LIMIT 1";
    $query_inscricao = mysqli_query($conexao, $sql_busca_inscricao);

    if (mysqli_num_rows($query_inscricao) > 0) {
        $ingresso_dados = mysqli_fetch_assoc($query_inscricao);
        
        if ($ingresso_dados['status_inscricao'] === 'cancelada') {
            $_SESSION['mensagem'] = "❌ ERRO: O ingresso encontra-se CANCELADO no sistema.";
            header('Location: ../views/eventos/painel-checkin.php'); exit;
        }

        $sql_checa_bip = "SELECT id, data_entrada FROM check_ins WHERE id_inscricao = '$id_inscricao' LIMIT 1";
        $query_bip = mysqli_query($conexao, $sql_checa_bip);
        
        if (mysqli_num_rows($query_bip) > 0) {
            $dados_checkin = mysqli_fetch_assoc($query_bip);
            $horaFormatada = date('H:i:s', strtotime($dados_checkin['data_entrada']));
            $_SESSION['mensagem'] = "⚠️ BLOQUEADO! Já realizou o Check-In hoje às " . $horaFormatada;
            header('Location: ../views/eventos/painel-checkin.php'); exit;
        }

        $sql_insert = "INSERT INTO check_ins (id_inscricao, operador_porta) VALUES ('$id_inscricao', '$operador')";
        mysqli_query($conexao, $sql_insert);

        if (mysqli_affected_rows($conexao) > 0) {
            $_SESSION['mensagem'] = "✅ APROVADO: Catraca/Entrada Liberada com sucesso!";
        }
    } else {
        $_SESSION['mensagem'] = "❌ INGRESSO INEXISTENTE. Nenhuma pessoa com número ($id_inscricao)";
    }
    
    header('Location: ../views/eventos/painel-checkin.php'); 
    exit;
}

// ==========================================
// ESTORNO DE CHECK-IN (Excluir da catraca para usar dnv)
// ==========================================
if (isset($_POST['desfazer_checkin'])) {
    $id_checkin = mysqli_real_escape_string($conexao, $_POST['desfazer_checkin']); 
    $sql_delete = "DELETE FROM check_ins WHERE id = '$id_checkin'";
    mysqli_query($conexao, $sql_delete);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = "Estorno com sucesso! Pessoa pode entrar de novo com este bilhete!";
    }
    header('Location: ../views/eventos/painel-checkin.php');
    exit;
}
?>
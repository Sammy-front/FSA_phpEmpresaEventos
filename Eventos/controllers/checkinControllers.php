<?php
// Arquivo: controllers/checkinControllers.php
session_start();
require __DIR__ . '/../config/conexao.php'; 

// ==========================================
// AÇÃO 1: BATER O INGRESSO / CHECK-IN CATRACA
// ==========================================
if (isset($_POST['realizar_checkin'])) {
    
    // Captura o nº que tá oculto lá na sua página Front ou no botão
    $id_inscricao = mysqli_real_escape_string($conexao, trim($_POST['id_inscricao']));
    
    // Tenta pegar quem clicou: 
    $operador = isset($_SESSION['nome']) ? mysqli_real_escape_string($conexao, $_SESSION['nome']) : 'Admin/Sistema';

    $sql_busca = "SELECT id, status_inscricao FROM inscricoes WHERE id = '$id_inscricao' LIMIT 1";
    $query_insc = mysqli_query($conexao, $sql_busca);

    if (mysqli_num_rows($query_insc) > 0) {
        $dados_ticket = mysqli_fetch_assoc($query_insc);
        
        // Verificou? Se tiver cancelada a base impede o cara do portao clicar atoa
        if ($dados_ticket['status_inscricao'] === 'cancelada') {
            $_SESSION['mensagem'] = "❌ CUIDADO FRAUDE: O ingresso [$id_inscricao] é original porém foi CANCELADO antes da festa no sistema.";
            header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php'); exit;
        }

        // BARRAR DOUBLE-CHECKIN Mágicamente:
        $sql_verifica = "SELECT id, data_entrada FROM check_ins WHERE id_inscricao = '$id_inscricao' LIMIT 1";
        $verifica_passado = mysqli_query($conexao, $sql_verifica);
        
        if (mysqli_num_rows($verifica_passado) > 0) {
            $horaFormat = date('H:i:s', strtotime(mysqli_fetch_assoc($verifica_passado)['data_entrada']));
            $_SESSION['mensagem'] = "⚠️ BLOQUEADO E NEGADO: Uma pessoa usando a inscrição nº #$id_inscricao já foi recebida e entrou no evento ás " . $horaFormat . "hrs!";
            header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php'); exit;
        }

        // Se passar da regra de bloqueios do mal: Autorizar Catraca! (Cadastrar liberação)
        $salvar_liberacao = "INSERT INTO check_ins (id_inscricao, operador_porta) VALUES ('$id_inscricao', '$operador')";
        mysqli_query($conexao, $salvar_liberacao);

        if (mysqli_affected_rows($conexao) > 0) {
            $_SESSION['mensagem'] = "✅ APROVADO: Ingresso Validado (ID: $id_inscricao) Porta foi liberada por conta!";
        } else {
             $_SESSION['mensagem'] = "❌ ERRO! Tivemos problemas lendo o SQL e processando catraca para (ID: $id_inscricao)";
        }
    } else {
        $_SESSION['mensagem'] = "❌ ERRO GRAVE! Sistema informa NINGUÉM comprado para Bipar a Ficha de Inscrição numero: (#$id_inscricao)";
    }
    
    // Caminho da Redirecional inquebrável usando LocalRoot (Evita Tela de BUG branco):
    header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php'); 
    exit;
}

// ==========================================
// AÇÃO 2: CORREÇÃO: RETORNAR O STATUS PRA NÃO Bipado  
// ==========================================
if (isset($_POST['desfazer_checkin'])) {
    $id_checkin = mysqli_real_escape_string($conexao, trim($_POST['desfazer_checkin'])); 
    
    // Desestorna deletando apenas ele que se trata puramente da autorizaão. E fica a folha solta!
    mysqli_query($conexao, "DELETE FROM check_ins WHERE id = '$id_checkin'");

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = "Você reiniciou a leitura com exatidão da entrada e catraca do usuário que teve estorno clicado.";
    } else{
       $_SESSION['mensagem'] = "O Checkin que tentou apagar já expirou ou quebrou linha DB.."; 
    }
    
    header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php');
    exit;
}
?>
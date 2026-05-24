<?php
session_start();
require __DIR__ . '/../config/conexao.php';


// chekin dos ingressos:
if (isset($_POST['realizar_checkin'])) {
    $id_inscricao = mysqli_real_escape_string($conexao, trim($_POST['id_inscricao']));

    $operador = isset($_SESSION['nome']) ? mysqli_real_escape_string($conexao, $_SESSION['nome']) : 'Admin/Sistema';

    $sql_busca = "SELECT id, status_inscricao FROM inscricoes WHERE id = '$id_inscricao' LIMIT 1";
    $query_insc = mysqli_query($conexao, $sql_busca);

    if (mysqli_num_rows($query_insc) > 0) {
        $dados_ticket = mysqli_fetch_assoc($query_insc);

        if ($dados_ticket['status_inscricao'] === 'cancelada') {
            $_SESSION['mensagem'] = "Cancelado: O ingresso [$id_inscricao] é original porém foi CANCELADO antes da entrada, portanto não pode ser autorizado para entrar no evento.";
            header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php');
            exit;
        }

        $sql_verifica = "SELECT id, data_entrada FROM check_ins WHERE id_inscricao = '$id_inscricao' LIMIT 1";
        $verifica_passado = mysqli_query($conexao, $sql_verifica);

        if (mysqli_num_rows($verifica_passado) > 0) {
            $horaFormat = date('H:i:s', strtotime(mysqli_fetch_assoc($verifica_passado)['data_entrada']));
            $_SESSION['mensagem'] = "Bloqueado: Uma pessoa usando a inscrição nº #$id_inscricao já foi recebida ou entrou no evento ás " . $horaFormat . "horas!";
            header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php');
            exit;
        }

        $salvar_liberacao = "INSERT INTO check_ins (id_inscricao, operador_porta) VALUES ('$id_inscricao', '$operador')";
        mysqli_query($conexao, $salvar_liberacao);

        if (mysqli_affected_rows($conexao) > 0) {
            $_SESSION['mensagem'] = "Aprovado: Ingresso Validado (ID: $id_inscricao) Porta foi liberada!";
        } else {
            $_SESSION['mensagem'] = "Erro! Tivemos problemas lendo o SQL e processando catraca para (ID: $id_inscricao)";
        }
    } else {
        $_SESSION['mensagem'] = "Erro grave! Sistema informa NINGUÉM comprado para Bipar a Ficha de Inscrição numero: (#$id_inscricao)";
    }

    header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php');
    exit;
}

if (isset($_POST['desfazer_checkin'])) {
    $id_checkin = mysqli_real_escape_string($conexao, trim($_POST['desfazer_checkin']));
    mysqli_query($conexao, "DELETE FROM check_ins WHERE id = '$id_checkin'");

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = "Você reiniciou a leitura com exatidão da entrada e catraca do usuário que teve estorno clicado.";
    } else {
        $_SESSION['mensagem'] = "O Checkin que tentou apagar já expirou ou quebrou linha DB..";
    }

    header('Location: /FSA/FSA_phpEmpresaEventos/Eventos/views/eventos/painel-checkin.php');
    exit;
}

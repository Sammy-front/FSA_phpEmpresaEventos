<?php
session_start();
require __DIR__ . '/../config/conexao.php';

if (isset($_POST['realizar_inscricao'])) {

    $id_evento = mysqli_real_escape_string($conexao, $_POST['id_evento']);
    $id_tipo_ingresso = mysqli_real_escape_string($conexao, $_POST['id_tipo_ingresso']);
    $quantidade = (int) $_POST['quantidade'];

    if ($quantidade < 1) {
        $_SESSION['mensagem'] = "Ação Inválida! A quantidade precisa ser no mínimo 1.";
        header('Location: ../public/dashUser.php');
        exit;
    }

    $email_logado = $_SESSION['usuario'];
    $sql_user = mysqli_query($conexao, "SELECT id FROM usuarios WHERE email = '$email_logado' LIMIT 1");
    if (mysqli_num_rows($sql_user) == 0) {
        die("Usuário inválido ou Sessão expirou.");
    }

    $id_usuario = mysqli_fetch_assoc($sql_user)['id'];
    $verifica_capacidade = mysqli_query($conexao, "SELECT capacidade FROM eventos WHERE id = '$id_evento' LIMIT 1");
    $capacidade_maxima = mysqli_fetch_assoc($verifica_capacidade)['capacidade'];
    $verifica_vendidos = mysqli_query($conexao, "SELECT COUNT(id) as total_vendidos FROM inscricoes WHERE id_evento = '$id_evento' AND status_inscricao != 'cancelada'");
    $vendidos = mysqli_fetch_assoc($verifica_vendidos)['total_vendidos'];


    if (($vendidos + $quantidade) > $capacidade_maxima) {
        $sobrou = $capacidade_maxima - $vendidos;
        $_SESSION['mensagem'] = "Venda bloqueada. O evento atingiu sua capacidade. Temos apenas $sobrou vaga(s) restante(s).";
        header("Location: ../views/inscricoes/inscricao.php?id=$id_evento");
        exit;
    }

    $id_lote = 'NULL';
    $check_lote = mysqli_query($conexao, "SELECT id FROM lotes WHERE id_evento = '$id_evento' AND NOW() BETWEEN data_inicio AND data_fim ORDER BY id ASC LIMIT 1");
    if (mysqli_num_rows($check_lote) > 0) {
        $loteAtivo = mysqli_fetch_assoc($check_lote);
        $id_lote = $loteAtivo['id'];
    }

    $inseridos = 0;

    for ($i = 0; $i < $quantidade; $i++) {
        $sql_inserir = "INSERT INTO inscricoes (id_evento, id_usuario, id_tipo_ingresso, id_lote, status_inscricao) VALUES ('$id_evento', '$id_usuario', '$id_tipo_ingresso', $id_lote, 'paga')";
        mysqli_query($conexao, $sql_inserir);
        if (mysqli_affected_rows($conexao) > 0) {
            $inseridos++;
        }
    }

    if ($inseridos == $quantidade) {
        if ($quantidade == 1) {
            $_SESSION['mensagem'] = "Seu Ticket para a festa foi emitido e salvo na sua carteira digital! Status: PAGO.";
        } else {
            $_SESSION['mensagem'] = "Você comprou <b>$quantidade convites</b> simultâneos. Aproveite muito o Evento com seus convidados. Status: PAGOS.";
        }
    } else {
        $_SESSION['mensagem'] = "Atenção: Apenas $inseridos Ingressos registrados com sucesso! Fale com o ADM do sistema sobre instabilidade do banco!";
    }

    header('Location: ../public/dashUser.php');
    exit;
}

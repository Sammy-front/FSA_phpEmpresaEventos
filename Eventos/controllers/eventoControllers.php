<?php
session_start();
require __DIR__ . '/../config/conexao.php';

// CREATE (Criar Evento)
if (isset($_POST['create_evento'])) {
    $nome        = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
    $data_evento = mysqli_real_escape_string($conexao, trim($_POST['data_evento']));
    $horario = mysqli_real_escape_string($conexao, trim($_POST['horario']));
    $capacidade  = mysqli_real_escape_string($conexao, trim($_POST['capacidade']));
    $localidade = mysqli_real_escape_string($conexao, trim($_POST['localidade']));
    $status_evento = mysqli_real_escape_string($conexao, trim($_POST['status_evento']));

    $sql = "INSERT INTO eventos (nome, descricao, data_evento, horario, localidade, capacidade, status_evento) VALUES ('$nome', '$descricao', '$data_evento', '$horario', '$localidade', '$capacidade', '$status_evento')";
    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = 'Evento Adicionado com sucesso!';
    } else {
        $_SESSION['mensagem'] = 'Erro ao criar evento.';
    }
    header('Location: ../public/dashboard.php');
    exit;
}

// UPDATE (Editar Evento)
if (isset($_POST['update_evento'])) {
    
    $evento_id   = mysqli_real_escape_string($conexao, $_POST['evento_id']); // Vem do input HIDDEN do forms!
    $nome        = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
    $data_evento = mysqli_real_escape_string($conexao, trim($_POST['data_evento']));
    $horario = mysqli_real_escape_string($conexao, trim($_POST['horario']));
    $capacidade  = mysqli_real_escape_string($conexao, trim($_POST['capacidade']));
    $localidade = mysqli_real_escape_string($conexao, trim($_POST['localidade']));
    $status_evento = mysqli_real_escape_string($conexao, trim($_POST['status_evento']));

    // AQUI SAMUEL AQUIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
    $sql = "UPDATE eventos SET nome='$nome', descricao='$descricao', data_evento='$data_evento', horario='$horario', capacidade='$capacidade', localidade='$localidade', status_evento='$status_evento' WHERE id='$evento_id'";
    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = 'Evento atualizado com sucesso.';
    } else {
        $_SESSION['mensagem'] = 'Nenhuma alteração efetuada (Ou ocorreu um erro).';
    }
    header('Location: ../public/index.php');
    exit;
}

// DELETE (Deletar Evento vindo de Index)
if (isset($_POST['delete_evento'])) {
    $evento_id = mysqli_real_escape_string($conexao, $_POST['delete_evento']); // $_POST['delete_evento'] recebe o VALUE do botão clickado no Index

    $sql = "DELETE FROM eventos WHERE id = '$evento_id'";
    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = 'Evento EXCLUIDO com sucesso!';
    } else {
        $_SESSION['mensagem'] = 'Não foi possivel deletar o evento.';
    }
    header('Location: ../public/index.php');
    exit;
}

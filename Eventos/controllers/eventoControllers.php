<?php
session_start();
require __DIR__ . '/../config/conexao.php';


// CRIAR EVENTO E INGRESSOS
if (isset($_POST['create_evento'])) {
    $nome          = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $descricao     = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
    $data_evento   = mysqli_real_escape_string($conexao, trim($_POST['data_evento']));
    $horario       = mysqli_real_escape_string($conexao, trim($_POST['horario']));
    $localidade    = mysqli_real_escape_string($conexao, trim($_POST['localidade']));
    $capacidade    = mysqli_real_escape_string($conexao, trim($_POST['capacidade']));
    $status_evento = mysqli_real_escape_string($conexao, trim($_POST['status_evento']));

    $sql_evento = "INSERT INTO eventos (nome, descricao, data_evento, horario, localidade, capacidade, status_evento) VALUES ('$nome', '$descricao', '$data_evento', '$horario', '$localidade', '$capacidade', '$status_evento')";

    if (mysqli_query($conexao, $sql_evento)) {
        $id_novo_evento = mysqli_insert_id($conexao);

        if (isset($_POST['ticket_nome']) && isset($_POST['ticket_valor'])) {
            $nomes_ingressos   = $_POST['ticket_nome'];
            $valores_ingressos = $_POST['ticket_valor'];

            for ($i = 0; $i < count($nomes_ingressos); $i++) {
                $nome_ing  = mysqli_real_escape_string($conexao, $nomes_ingressos[$i]);
                $valor_ing = mysqli_real_escape_string($conexao, $valores_ingressos[$i]);
                if (!empty($nome_ing) && $valor_ing !== '') {
                    $sql_ing = "INSERT INTO tipos_ingressos (id_evento, nome_ingresso, valor) VALUES ('$id_novo_evento', '$nome_ing', '$valor_ing')";
                    mysqli_query($conexao, $sql_ing);
                }
            }
        }

        $_SESSION['mensagem'] = "Evento e Ingressos criados com sucesso!";
        header('Location: ../views/eventos/evento-view.php');
    } else {
        $_SESSION['mensagem'] = "Erro ao criar evento: " . mysqli_error($conexao);
        header('Location: ../views/eventos/evento-create.php');
    }
    exit;
}


// EDITAR EVENTO
if (isset($_POST['update_evento'])) {
    $evento_id     = mysqli_real_escape_string($conexao, $_POST['evento_id']);
    $nome          = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $descricao     = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
    $data_evento   = mysqli_real_escape_string($conexao, trim($_POST['data_evento']));
    $horario       = mysqli_real_escape_string($conexao, trim($_POST['horario']));
    $capacidade    = mysqli_real_escape_string($conexao, trim($_POST['capacidade']));
    $localidade    = mysqli_real_escape_string($conexao, trim($_POST['localidade']));
    $status_evento = mysqli_real_escape_string($conexao, trim($_POST['status_evento']));

    $sql = "UPDATE eventos SET 
            nome='$nome', 
            descricao='$descricao', 
            data_evento='$data_evento', 
            horario='$horario', 
            capacidade='$capacidade', 
            localidade='$localidade', 
            status_evento='$status_evento' 
            WHERE id='$evento_id'";

    if (mysqli_query($conexao, $sql)) {
        $_SESSION['mensagem'] = "Evento atualizado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar: " . mysqli_error($conexao);
    }
    header('Location: ../views/eventos/evento-view.php');
    exit;
}

// EXCLUIR EVENTO
if (isset($_POST['delete_evento'])) {
    $evento_id = mysqli_real_escape_string($conexao, $_POST['delete_evento']);

    $sql = "DELETE FROM eventos WHERE id = '$evento_id'";
    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = 'Evento excluído permanentemente!';
    } else {
        $_SESSION['mensagem'] = 'Não foi possível deletar o evento.';
    }

    // Volta para a página exata que o usuário estava (Dashboard ou Evento-View)
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: ../views/eventos/evento-view.php');
    }
    exit;
}

<?php
session_start();
require __DIR__ . '/../config/conexao.php';

// CRIAR USUÁRIO

if (isset($_POST['create_usuario'])) {
	$nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
	$email = mysqli_real_escape_string($conexao, trim($_POST['email']));
	$data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
	$senha = isset($_POST['senha']) ? mysqli_real_escape_string($conexao, password_hash(trim($_POST['senha']), PASSWORD_DEFAULT)) : '';
	$cargo = mysqli_real_escape_string($conexao, trim($_POST['cargo']));

	$sql = "INSERT INTO usuarios (nome, email, data_nascimento, senha, cargo) VALUES ('$nome', '$email', '$data_nascimento', '$senha', '$cargo')";
	mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) > 0) {
		$_SESSION['mensagem'] = 'Bem vindo! Entre com a conta recém criada.';
		header('Location: ../views/auth/login.php');
		exit;
	} else {
		$_SESSION['mensagem'] = 'Falha ao processar cadastro no banco!';
		header('Location: ../public/index.php');
		exit;
	}
}

// UPDATE PELO ADM

if (isset($_POST['update_usuario'])) {
	$usuario_id = mysqli_real_escape_string($conexao, $_POST['usuario_id']);
	$nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
	$email = mysqli_real_escape_string($conexao, trim($_POST['email']));
	$data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
	$senha = trim($_POST['senha']);
	$cargo = mysqli_real_escape_string($conexao, trim($_POST['cargo']));

	$sql = "UPDATE usuarios SET nome = '$nome', email = '$email', data_nascimento = '$data_nascimento', cargo = '$cargo'";

	if (!empty($senha)) {
		$sql .= ", senha='" . password_hash($senha, PASSWORD_DEFAULT) . "'";
	}

	$sql .= " WHERE id = '$usuario_id'";
	mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) > 0) {
		$_SESSION['mensagem'] = 'Privilégios ou Cadastro alterado pelo Gestor ADM!';
	} else {
		$_SESSION['mensagem'] = 'Nada de novo processado.';
	}
	header('Location: ../views/usuarios_list/usuario_list.php');
	exit;
}



// DELETE
if (isset($_POST['delete_usuario'])) {
	$usuario_id = mysqli_real_escape_string($conexao, $_POST['delete_usuario']);
	$sql = "DELETE FROM usuarios WHERE id = '$usuario_id'";
	mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) > 0) {
		$_SESSION['mensagem'] = 'Usuário e sua sub-permissões EXCLUÍDOS permanentement do sistema!';
	} else {
		$_SESSION['mensagem'] = 'Incapaz de ler base e confirmar Delete...';
	}
	header('Location: ../views/usuarios_list/usuario_list.php');
	exit;
}

// UPDATE PELO USER
if (isset($_POST['atualizar_minha_conta'])) {
	$sessao_email = mysqli_real_escape_string($conexao, $_SESSION['usuario']);
	$resultado_busca = mysqli_query($conexao, "SELECT id FROM usuarios WHERE email = '$sessao_email' LIMIT 1");
	$usuario_id = mysqli_fetch_assoc($resultado_busca)['id'];
	$novo_nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
	$novo_email = mysqli_real_escape_string($conexao, trim($_POST['email']));
	$nova_data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
	$nova_senha = trim($_POST['senha']);
	$sql_update = "UPDATE usuarios SET nome = '$novo_nome', email = '$novo_email', data_nascimento = '$nova_data_nascimento'";

	if (!empty($nova_senha)) {
		$senha_criptografada = password_hash($nova_senha, PASSWORD_DEFAULT);
		$sql_update .= ", senha = '$senha_criptografada'";
	}

	$sql_update .= " WHERE id = '$usuario_id'";
	mysqli_query($conexao, $sql_update);

	if (mysqli_affected_rows($conexao) > 0) {
		$_SESSION['usuario'] = $novo_email;
		$_SESSION['nome'] = $novo_nome;
		$_SESSION['mensagem'] = "Configurações da sua conta aplicadas com sucesso!";
	} else {
		$_SESSION['mensagem'] = "Nenhuma nova alteração processada em seus dados.";
	}

	$pagina_anterior = $_SERVER['HTTP_REFERER'];
	header("Location: $pagina_anterior");
	exit;
}

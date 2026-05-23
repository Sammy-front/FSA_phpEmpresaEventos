<!-- Modificar tudo para criar varios controlers para melhor visualização e praticidade. -->

<?php
session_start();

require __DIR__ . '/../config/conexao.php';

// Criar usuario
if (isset($_POST['create_usuario'])) {
	$nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
	$email = mysqli_real_escape_string($conexao, trim($_POST['email']));
	$data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
	$senha = isset($_POST['senha']) ? mysqli_real_escape_string($conexao, password_hash(trim($_POST['senha']), PASSWORD_DEFAULT)) : '';
	$cargo = mysqli_real_escape_string($conexao, trim($_POST['cargo']));

	$sql = "INSERT INTO usuarios (nome, email, data_nascimento, senha, cargo) VALUES ('$nome', '$email', '$data_nascimento', '$senha', '$cargo')";
	
	mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) > 0) {
		header('Location: ../views/auth/login.php');
		exit;
	} else {
		$_SESSION['mensagem'] = 'Usuário não foi criado';
		header('Location: ../public/index.php');
		exit;
	}
}

// Update do usuario.
if (isset($_POST['update_usuario'])) {

	$usuario_id = mysqli_real_escape_string($conexao, $_POST['usuario_id']);
	$nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
	$email = mysqli_real_escape_string($conexao, trim($_POST['email']));
	$data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
	$senha = mysqli_real_escape_string($conexao, trim($_POST['senha']));
	$cargo = mysqli_real_escape_string($conexao, trim($_POST['cargo']));

	$sql = "UPDATE login SET nome = '$nome', email = '$email', data_nascimento = '$data_nascimento', cargo = '$cargo'";
	
	if (!empty($senha)) {
		$sql .= ", senha='" . password_hash($senha, PASSWORD_DEFAULT) . "'";
	}
	
	$sql .= " WHERE id = '$usuario_id'";
	
	mysqli_query($conexao, $sql);

	// confirmação da atualização
	if (mysqli_affected_rows($conexao) > 0) {
		$_SESSION['mensagem'] = 'Usuário atualizado com sucesso';
		header('Location: ../public/index.php');
		exit;
	} else {
		$_SESSION['mensagem'] = 'Usuário não foi atualizado';
		header('Location: ../public/index.php');
		exit;
	}
}


// Delete Usuario
if (isset($_POST['delete_usuario'])) {
	$usuario_id = mysqli_real_escape_string($conexao, $_POST['delete_usuario']);
	
	$sql = "DELETE FROM login WHERE id = '$usuario_id'";
	
	mysqli_query($conexao, $sql);
	
	if (mysqli_affected_rows($conexao) > 0) {
		$_SESSION['message'] = 'Usuário deletado com sucesso';
		header('Location: ../public/index.php');
		exit;
	} else {
		$_SESSION['message'] = 'Usuário não foi deletado';
		header('Location: ../public/index.php');
		exit;
	}
}
?>

<!-- Criação do usuario, atualização e deletar usuario criados com sucesso. -->
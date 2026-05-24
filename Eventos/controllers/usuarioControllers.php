<?php
// Arquivo: controllers/usuarioControllers.php
session_start();
require __DIR__ . '/../config/conexao.php';

// ==========================================
// 1. CRIAR USUÁRIO (Tela de Register / ADM Cria)
// ==========================================
if (isset($_POST['create_usuario'])) {
	$nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
	$email = mysqli_real_escape_string($conexao, trim($_POST['email']));
	$data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
	$senha = isset($_POST['senha']) ? mysqli_real_escape_string($conexao, password_hash(trim($_POST['senha']), PASSWORD_DEFAULT)) : '';
	$cargo = mysqli_real_escape_string($conexao, trim($_POST['cargo']));

	$sql = "INSERT INTO usuarios (nome, email, data_nascimento, senha, cargo) VALUES ('$nome', '$email', '$data_nascimento', '$senha', '$cargo')";
	mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) > 0) {
        // Manda o novato pra página de logar depois de criar!
        $_SESSION['mensagem'] = 'Bem vindo! Entre com a conta recém criada.';
		header('Location: ../views/auth/login.php'); exit;
	} else {
		$_SESSION['mensagem'] = 'Falha ao processar cadastro no banco!';
		header('Location: ../public/index.php'); exit;
	}
}


// ==========================================
// 2. UPDATE PELO ADM (Quando um ADMIN clica pra alterar as coisas de algum Funcionario/User)
// ==========================================
if (isset($_POST['update_usuario'])) {
	$usuario_id = mysqli_real_escape_string($conexao, $_POST['usuario_id']);
	$nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
	$email = mysqli_real_escape_string($conexao, trim($_POST['email']));
	$data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
	$senha = trim($_POST['senha']); // Deixo string crua pra converter de dps pra n perder validção  
	$cargo = mysqli_real_escape_string($conexao, trim($_POST['cargo']));

    // UPDATE FOI CORRIGIDO DE "login" para a tabela "usuarios"!
	$sql = "UPDATE usuarios SET nome = '$nome', email = '$email', data_nascimento = '$data_nascimento', cargo = '$cargo'";
	
    // SE e APENAS SE o Adm preencheu campo nova Senha, Força reset da Criptografia..
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
	// Depois do Editar ADM - Te Joga na listinha de funcionario 
    header('Location: ../views/usuarios_list/usuario_list.php'); exit;
}


// ==========================================
// 3. DELETE (Cortando conta definitivamente!)
// ==========================================
if (isset($_POST['delete_usuario'])) {
	$usuario_id = mysqli_real_escape_string($conexao, $_POST['delete_usuario']);
	
    // ERRO VELHO ARRUAMD: DELETANDO Da nova tab usuarios!!! 
	$sql = "DELETE FROM usuarios WHERE id = '$usuario_id'";
	mysqli_query($conexao, $sql);
	
	if (mysqli_affected_rows($conexao) > 0) {
		$_SESSION['mensagem'] = 'Usuário e sua sub-permissões EXCLUÍDOS permanentement do sistema!';
	} else {
		$_SESSION['mensagem'] = 'Incapaz de ler base e confirmar Delete...';
	}
	header('Location: ../views/usuarios_list/usuario_list.php'); exit;
}


// =======================================================================
// 4. *** O NOVO BLOCO DO SEU AMIGO! (Pra editar do menu lateral de navegação NavBar/Modal)
// =======================================================================
if (isset($_POST['atualizar_minha_conta'])) {
    
    // Identificar Exatamente QUEM fez o Clique via EMAIL de logado atual.
    $email_na_sessao = mysqli_real_escape_string($conexao, $_SESSION['usuario']);
    
    // Ler a ID que o PHP ta travado para proibir eu injetar hackear Conta dos "vizinhos"  pela nav !
    $resulT = mysqli_query($conexao, "SELECT id FROM usuarios WHERE email = '$email_na_sessao' LIMIT 1");
    $euMsmNoServ = mysqli_fetch_assoc($resulT)['id'];

    $nomeO = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $emailO = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $dtNas = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
    $snNvl2 = trim($_POST['senha']);

    $Qsqq  = "UPDATE usuarios SET nome = '$nomeO', email = '$emailO', data_nascimento = '$dtNas'";
    if (!empty($snNvl2)) {
        $cifraLoka = password_hash($snNvl2, PASSWORD_DEFAULT);
        $Qsqq .= ", senha = '$cifraLoka'";
    }
    $Qsqq .= " WHERE id = '$euMsmNoServ'";
    
    mysqli_query($conexao, $Qsqq);

    // Conclusões do form popup 
    if (mysqli_affected_rows($conexao) > 0) {
        
        // Pulo do gato Master!! Como trocou de "João@bol..." por "JoãoLider@outlook.."  precisams REAJUSTAR 
        // A propria SESSSSÃAO para Navbar no Top da Tel nao bugar dizendo que loguinho deu expirou por falta de correspondÊcia.
        $_SESSION['usuario'] = $emailO; 
        $_SESSION['nome'] = $nomeO; 
        
        $_SESSION['mensagem'] = "✨ Fantástico e Perfeito. Conseguiu aplicar Updates à Configuração!";
    } else {
        $_SESSION['mensagem'] = "Você esqueceu algo.. Nada mexido de real p' mudar conta :)!";
    }

    // A Mágica do Navbar : Fique onde Voce apertou!!! HTTP referers (Ex eu lendo Dash cliqei isso; O sistema Volta p Minah Dash.  Dnv sutilmene !!)
    $paginaQoNegoApertOUaPorra = $_SERVER['HTTP_REFERER'];
    header("Location: $paginaQoNegoApertOUaPorra");
    exit;
}
?>
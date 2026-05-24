<?php
session_start();

require __DIR__ . '/../../config/conexao.php';

if (isset($_POST['login_usuario'])) {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    if (empty($usuario) || empty($senha)) {
        $_SESSION['mensagem'] = 'Preencha todos os campos.';
        header('Location: login.php');
        exit;
    }

    $sql = "SELECT id, nome, email, senha, cargo FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($resultado && mysqli_num_rows($resultado) > 0) {

            $usuarioDB = mysqli_fetch_assoc($resultado);

            if (password_verify($senha, $usuarioDB['senha'])) {
                $_SESSION['usuario'] = $usuarioDB['email'];
                $_SESSION['nome'] = $usuarioDB['nome'];
                $_SESSION['cargo'] = $usuarioDB['cargo'];

                if ($usuarioDB['cargo'] === 'adm') {
                    header('Location: ../../public/dashboard.php');
                    exit;
                } else {
                    header('Location: ../../public/dashUser.php');
                    exit;
                }
            } else {
                $_SESSION['mensagem'] = 'Senha incorreta.';
                header('Location: login.php');
                exit;
            }
        } else {
            $_SESSION['mensagem'] = 'Usuário não encontrado.';
            header('Location: login.php');
            exit;
        }
    } else {
        $_SESSION['mensagem'] = 'Erro no servidor. Tente novamente mais tarde.';
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>
    <div class="container login-container">

        <h2>Entrar no Sistema</h2>

        <?php if (isset($_SESSION['mensagem'])): ?>
            <p style="color: red; text-align:center;">
                <?php echo $_SESSION['mensagem']; ?>
            </p>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="usuario">E-mail</label>
                <input type="email" id="usuario" name="usuario" required placeholder="Digite seu e-mail">
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Digite sua senha">
            </div>

            <button type="submit" name="login_usuario" class="btn btn-primary" style="width: 100%;">Entrar</button>
        </form>

        <p style="margin-top: 15px; text-align: center;">
            Não tem uma conta? <a href="../auth/register.php">Cadastre-se</a>
        </p>

        <p style="margin-top: 15px; text-align: center;">
            Voltar à <a href="../../public/index.php">HOME</a>
        </p>

    </div>
</body>

</html>
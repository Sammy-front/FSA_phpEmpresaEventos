<?php
session_start();

require __DIR__ . '/conexao.php'; // Conectar ao banco

if (isset($_POST['login_usuario'])) {
    // Sanitize e escape as variáveis para evitar SQL Injection
    $usuario = mysqli_real_escape_string($conexao, trim($_POST['usuario']));
    $senha = mysqli_real_escape_string($conexao, trim($_POST['senha']));

    // Consulta para buscar o usuário e a senha hashada no banco
    $sql = "SELECT * FROM login WHERE email = '$usuario' LIMIT 1";
    $resultado = mysqli_query($conexao, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        // Buscar os dados do usuário
        $usuarioDB = mysqli_fetch_assoc($resultado);

        // Verificar se a senha informada corresponde ao hash armazenado
        if (password_verify($senha, $usuarioDB['senha'])) {
            // Senha correta, criar a sessão
            $_SESSION['usuario'] = $usuarioDB['email'];
            $_SESSION['nome'] = $usuarioDB['nome'];
            header('Location: index.php');
            exit;
        } else {
            // Senha incorreta
            $_SESSION['mensagem'] = 'Senha incorreta.';
            header('Location: login.php');
            exit;
        }
    } else {
        // Usuário não encontrado
        $_SESSION['mensagem'] = 'Usuário não encontrado.';
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
</head>

<body>
    <h2>Login</h2>

    <?php if (isset($_SESSION['mensagem'])): ?>
        <p style="color: red;"><?php echo $_SESSION['mensagem']; ?></p>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <form method="POST">
        <label for="usuario">Usuário (Email):</label><br>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>

        <hr>
        <p>Não possui uma conta?</p>
        <a href="usuario-create.php">Cadastre-se aqui</a>
        <hr>

        <button type="submit" name="login_usuario">Entrar</button>
    </form>

</body>

</html>
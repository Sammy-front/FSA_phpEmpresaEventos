<?php
session_start();
require __DIR__ . '/../../config/conexao.php';

if (isset($_POST['login_usuario'])) {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    if (empty($usuario) || empty($senha)) {
        $_SESSION['mensagem'] = '⚠️ Preencha todos os campos.';
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
                } else {
                    header('Location: ../../public/dashUser.php');
                }
                exit;
            } else {
                $_SESSION['mensagem'] = 'Senha incorreta.';
                header('Location: login.php');
                exit;
            }
        } else {
            $_SESSION['mensagem'] = 'Email não existente no sistema.';
            header('Location: login.php');
            exit;
        }
    } else {
        $_SESSION['mensagem'] = 'Erro servidor. Tente novamente mais tarde.';
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - FSA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #f0f2f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070&auto=format&fit=crop') no-repeat center center/cover fixed;
            background-attachment: fixed;
        }

        .blur-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .login-box {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            background: white;
        }

        .card-header-icon {
            background: #16191c;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>

    <div class="login-box p-4 mt-4 position-relative">
        <div class="card-header-icon border border-4 border-warning">
            <i class="bi bi-shield-lock-fill fs-2 text-warning"></i>
        </div>

        <h3 class="text-center mt-5 mb-4 fw-bold text-dark">Eventos FSA</h3>

        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-danger py-2 fw-semibold text-center border-0 bg-danger-subtle"><i class="bi bi-exclamation-octagon me-1"></i> <?= $_SESSION['mensagem'] ?></div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <form method="POST">

            <div class="form-floating mb-3">
                <input type="email" id="usuario" name="usuario" class="form-control" required placeholder="email@email.com">
                <label for="usuario" class="text-secondary"><i class="bi bi-envelope me-2"></i>Email de Acesso</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" id="senha" name="senha" class="form-control" required placeholder="senha">
                <label for="senha" class="text-secondary"><i class="bi bi-key me-2"></i>Senha de Acesso</label>
            </div>

            <button type="submit" name="login_usuario" class="btn btn-warning w-100 py-3 mb-4 rounded-3 shadow fw-bold text-dark fs-5">LOGIN<i class="bi bi-arrow-right ms-2"></i></button>
        </form>

        <hr class="text-muted border-dashed border-2">

        <p class="text-center mb-1 text-secondary mt-3">É sua primeira vez aqui?</p>
        <a href="register.php" class="btn btn-light w-100 rounded-pill border fw-semibold">Criar minha nova Conta</a>

        <div class="text-center mt-3">
            <a href="../../public/index.php" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left"></i> Retornar para HOME</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
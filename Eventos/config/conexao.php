<?php
if (!defined('HOST')) {
    define('HOST', 'ec2-3-131-141-8.us-east-2.compute.amazonaws.com');
    define('USUARIO', 'cdc_3b_g9');
    define('SENHA', 'g9B@123');
    define('DB', 'cdc_3b_grupo9');
}

if (!isset($conexao)) {
    $conexao = mysqli_connect(HOST, USUARIO, SENHA, DB) or die('Não foi possível conectar com o banco de dados AWS.');
}
?>
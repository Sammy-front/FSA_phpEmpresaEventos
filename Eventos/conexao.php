<?php
define('HOST', 'ec2-3-131-141-8.us-east-2.compute.amazonaws.com');
define('USUARIO', 'cdc_3b_g9');
define('SENHA', 'g9B@123');
define('DB', 'cdc_3b_grupo9');

$conexao = mysqli_connect(HOST, USUARIO, SENHA, DB) or die ('Não foi possível conectar');
?>
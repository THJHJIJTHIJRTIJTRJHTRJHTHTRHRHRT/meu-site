<?php
session_start();

// Verificar se o usuário está logado e é um admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bem-vindo, Admin</h1>
    <a href="add_product.php" class="btn">Adicionar Lanches</a>
    <a href="index.php" class="btn">Página Inicial</a>
    <a href="logout.php" class="btn">Sair</a>
</body>
</html>

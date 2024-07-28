<?php
session_start();

// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', 'root', 'shop_dbb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Preparar e executar a declaração
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashedPassword, $role);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Autenticar o usuário
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;

            // Redirecionar para a página apropriada com base na função do usuário
            if ($role === 'admin') {
                header("Location: admin_home.php");
            } elseif ($role === 'client') {
                header("Location: client_home.php");
            } else {
                echo "Função de usuário desconhecida.";
            }
            exit();
        } else {
            echo "Usuário ou senha inválidos.";
        }
    } else {
        echo "Usuário não encontrado.";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Login</h1>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Entrar</button>
        <a href="register.php">Não tem uma conta? Cadastre-se</a>
    </form>
</body>
</html>


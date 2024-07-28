<?php
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', 'root', 'shop_dbb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // 'admin' ou 'client'

    // Hash da senha
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Inserir dados no banco de dados
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $role);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
        header("Location: login.php");
        exit();
    } else {
        echo "Erro ao registrar: " . $stmt->error;
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
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Cadastro</h1>
    <form action="register.php" method="post">
        <div class="form-group">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="role">Tipo de Conta:</label>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="client">Cliente</option>
            </select>
        </div>
        <button type="submit" class="btn">Registrar</button>
        <a href="login.php">Já tem uma conta? Faça login</a>
    </form>
</body>
</html> 

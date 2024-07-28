<?php
session_start();

// Redirecionar para a página do carrinho se o carrinho estiver vazio
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Processar o formulário de checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']); // Adicionar email

    // Verificar se os campos são válidos
    if (!empty($name) && !empty($address) && !empty($email)) { // Verificar email também
        // Salvar os detalhes do pedido na sessão
        $_SESSION['order'] = [
            'name' => $name,
            'address' => $address,
            'email' => $email, // Salvar email
            'cart' => $_SESSION['cart'],
        ];

        // Limpar o carrinho após a compra
        unset($_SESSION['cart']);

        // Enviar email de confirmação
        $to = 'pereira.jovit@gmail.com';
        $subject = 'Confirmação de Pedido';
        $message = "Nome: $name\nEndereço: $address\nEmail: $email\n\nDetalhes do Pedido:\n"; // Incluir email

        foreach ($_SESSION['order']['cart'] as $item) {
            $message .= "Produto: " . $item['name'] . " - Quantidade: " . $item['quantity'] . "\n";
        }

        $headers = 'From: no-reply@suadominio.com' . "\r\n" .
                   'Reply-To: no-reply@suadominio.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            $email_success = 'Email enviado com sucesso!';
        } else {
            $email_error = 'Erro ao enviar o email.';
        }

        // Redirecionar para a página de resumo do pedido
        header('Location: order_summary.php');
        exit();
    } else {
        $error = 'Por favor, preencha todos os campos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="path/to/your/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .checkout {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .checkout h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .checkout form {
            display: flex;
            flex-direction: column;
        }

        .checkout label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .checkout input[type="text"],
        .checkout input[type="email"] { /* Adicionar email */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .checkout button {
            padding: 10px 15px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .checkout button:hover {
            background-color: #0056b3;
        }

        .error {
            color: #dc3545;
            margin-bottom: 15px;
            text-align: center;
        }

        .success {
            color: #28a745;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="checkout">
        <h1>Finalizar Compra</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (isset($email_success)): ?>
            <p class="success"><?php echo htmlspecialchars($email_success); ?></p>
        <?php endif; ?>
        <?php if (isset($email_error)): ?>
            <p class="error"><?php echo htmlspecialchars($email_error); ?></p>
        <?php endif; ?>
        <form method="post" action="checkout.php">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" required>

            <label for="address">Endereço:</label>
            <input type="text" id="address" name="address" required>

            <label for="email">Email:</label> <!-- Adicionar campo de email -->
            <input type="email" id="email" name="email" required> <!-- Adicionar campo de email -->

            <button type="submit" name="checkout">Finalizar Compra</button>
        </form>
    </div>
</body>
</html>

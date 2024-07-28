<?php
session_start();

// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', 'root', 'shop_dbb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar se o ID do produto foi passado via GET
if (isset($_GET['id'])) {
    $productId = (int)$_GET['id'];

    // Obter as informações do produto
    $sql = "SELECT id, name, price, image FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Adicionar produto ao carrinho na sessão
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
    }

    $stmt->close();
}

// Fechar a conexão com o banco de dados
$conn->close();

// Redirecionar de volta para a página do carrinho
header('Location: cart.php');
exit();
?>




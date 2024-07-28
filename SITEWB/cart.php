<?php
session_start();

// Verificar se o formulário de atualização foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $productId => $quantity) {
        $productId = intval($productId);
        $quantity = intval($quantity);

        if ($quantity > 0) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$productId]);
        }
    }

    // Redirecionar para evitar envio múltiplo de formulário
    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="path/to/your/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .shopping-cart {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .shopping-cart h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .cart-table th, .cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #f8f8f8;
        }

        .cart-product img {
            width: 100px;
            height: auto;
            border-radius: 4px;
        }

        .cart-product-title {
            display: block;
            margin: 0;
        }

        .product-qtd-input {
            width: 60px;
            text-align: center;
            margin-right: 10px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .purchase-button {
            display: block;
            margin-top: 20px;
            font-size: 18px;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        .back-button {
            display: block;
            margin: 20px 0;
            text-align: center;
        }

        .back-button a {
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
        }

        .back-button a:hover {
            text-decoration: underline;
        }

        .remove-product-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .remove-product-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="shopping-cart" id="shopping-cart">
        <h1>Carrinho de Compras</h1>
        <form method="post" action="cart.php">
            <?php
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $totalAmount = 0;

                echo '<table class="cart-table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Produto</th>';
                echo '<th>Preço</th>';
                echo '<th>Quantidade</th>';
                echo '<th>Ação</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                foreach ($_SESSION['cart'] as $productId => $product) {
                    $totalAmount += $product['price'] * $product['quantity'];

                    echo '<tr class="cart-product">';
                    echo '<td class="product-identification">';
                    echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" class="cart-product-image">';
                    echo '<strong class="cart-product-title">' . htmlspecialchars($product['name']) . '</strong>';
                    echo '</td>';
                    echo '<td><span class="cart-product-price">R$' . htmlspecialchars(number_format($product['price'], 2, ',', '.')) . '</span></td>';
                    echo '<td>';
                    echo '<input type="number" name="quantity[' . $productId . ']" value="' . htmlspecialchars($product['quantity']) . '" min="1" class="product-qtd-input">';
                    echo '</td>';
                    echo '<td>';
                    echo '<a href="remove_from_cart.php?id=' . $productId . '" class="remove-product-button">Remover</a>'; // Adicionar link para remover item
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '<tr><td colspan="4" class="total">Total - R$' . htmlspecialchars(number_format($totalAmount, 2, ',', '.')) . '</td></tr>';
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>Seu carrinho está vazio.</p>';
            }
            ?>
            <button type="submit" name="update_cart" class="btn">Atualizar Carrinho</button>
        </form>
        <a href="checkout.php" class="btn purchase-button">Comprar</a>
        <div class="back-button">
            <a href="index.php">Voltar para a página inicial</a>
        </div>
    </div>

    <script src="path/to/your/script.js"></script>
</body>
</html>


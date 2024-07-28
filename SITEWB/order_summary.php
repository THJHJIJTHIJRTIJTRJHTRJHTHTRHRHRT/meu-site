<?php
session_start();
require_once 'db_connection.php'; // Inclua seu arquivo de conexão ao banco de dados

// Verificar se os detalhes do pedido estão disponíveis na sessão
if (!isset($_SESSION['order'])) {
    header('Location: checkout.php');
    exit();
}

// Obter os detalhes do pedido da sessão
$order = $_SESSION['order'];

// Conectar ao banco de dados
$conn = db_connect();

try {
    // Iniciar a transação
    $conn->beginTransaction();

    // Inserir o pedido na tabela 'pedidos'
    $stmt = $conn->prepare("INSERT INTO pedidos (nome, endereco, email, total, data_pedido) VALUES (:nome, :endereco, :email, :total, NOW())");
    $stmt->execute([
        ':nome' => $order['name'],
        ':endereco' => $order['address'],
        ':email' => $order['email'],
        ':total' => array_reduce($order['cart'], function($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0)
    ]);

    // Obter o ID do pedido inserido
    $pedido_id = $conn->lastInsertId();

    // Inserir os itens do pedido na tabela 'itens_pedido'
    $stmt = $conn->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (:pedido_id, :produto_id, :quantidade, :preco_unitario)");
    foreach ($order['cart'] as $item) {
        // Verificar se o produto existe e obter o ID
        $stmtProduct = $conn->prepare("SELECT id FROM products WHERE name = :name");
        $stmtProduct->execute([':name' => $item['name']]);
        $product = $stmtProduct->fetch();

        if ($product) {
            $stmt->execute([
                ':pedido_id' => $pedido_id,
                ':produto_id' => $product['id'],
                ':quantidade' => $item['quantity'],
                ':preco_unitario' => $item['price']
            ]);
        }
    }

    // Confirmar a transação
    $conn->commit();

    // Limpar os detalhes do pedido da sessão
    unset($_SESSION['order']);
} catch (Exception $e) {
    // Reverter a transação em caso de erro
    $conn->rollBack();
    echo "Falha ao processar o pedido: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo do Pedido</title>
    <link rel="stylesheet" href="path/to/your/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .order-summary {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .order-summary h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .order-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-summary table, .order-summary th, .order-summary td {
            border: 1px solid #ddd;
        }

        .order-summary th, .order-summary td {
            padding: 10px;
            text-align: left;
        }

        .order-summary th {
            background-color: #f4f4f4;
        }

        .order-summary .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="order-summary">
        <h1>Resumo do Pedido</h1>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
        <p><strong>Endereço:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>

        <h2>Produtos Comprados</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($order['cart'] as $item) {
                    $itemTotal = $item['price'] * $item['quantity'];
                    $total += $itemTotal;
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($item['quantity']) . '</td>';
                    echo '<td>R$ ' . number_format($item['price'], 2, ',', '.') . '</td>';
                    echo '<td>R$ ' . number_format($itemTotal, 2, ',', '.') . '</td>';
                    echo '</tr>';
                }
                ?>
                <tr>
                    <td colspan="3" class="total">Total Pago:</td>
                    <td class="total">R$ <?php echo number_format($total, 2, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-primary mt-3">Voltar à Página Inicial</a>
    </div>
</body>
</html>


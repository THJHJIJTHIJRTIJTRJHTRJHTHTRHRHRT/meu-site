<?php
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', 'root', 'shop_dbb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consultar os pedidos com detalhes dos itens
$sql = "
   SELECT 
    p.id AS pedido_id, 
    p.nome AS cliente_nome, 
    p.endereco, 
    p.email, 
    p.total AS pedido_total, 
    p.data_pedido,
    GROUP_CONCAT(pr.name SEPARATOR ', ') AS produtos_nome, 
    GROUP_CONCAT(CONCAT('R$ ', pr.price) SEPARATOR ', ') AS produtos_preco, 
    GROUP_CONCAT(ip.quantidade SEPARATOR ', ') AS quantidades, 
    GROUP_CONCAT(pr.image SEPARATOR ', ') AS produtos_imagem
FROM 
    pedidos p
INNER JOIN 
    itens_pedido ip ON p.id = ip.pedido_id
INNER JOIN 
    products pr ON ip.produto_id = pr.id
GROUP BY 
    p.id, p.nome, p.endereco, p.email, p.total, p.data_pedido
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            font-family: 'Arial', sans-serif;
        }
        .order-card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .order-card img {
            max-width: 80px;
            height: auto;
            margin-right: 10px;
        }
        .order-card h5 {
            margin-top: 0;
            color: #333;
        }
        .order-card .row {
            margin-bottom: 10px;
        }
        .order-card .col-4 {
            font-weight: bold;
            color: #555;
        }
        .order-card .col-8 {
            color: #777;
        }
        .order-card .quantities {
            font-size: 16px;
            color: #333;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            margin: 0 10px;
        }
        .alert-warning {
            margin-top: 20px;
        }
        .header-btns a {
            margin: 0 10px;
        }
        @media (max-width: 767.98px) {
            .order-card {
                padding: 15px;
            }
            .order-card .row {
                margin-bottom: 5px;
            }
            .order-card .col-4 {
                font-weight: bold;
                padding-right: 0;
            }
            .order-card .col-8 {
                padding-left: 0;
            }
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white text-center py-4 mb-4">
        <div class="container">
            <div class="header-btns">
                <a href="index.php" class="btn btn-light mb-2">Voltar</a>
                <a href="add_product.php" class="btn btn-light mb-2">Gerenciar Produtos</a>
            </div>
            <h1>Pedidos</h1>
        </div>
    </header>

    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="order-card">
                    <h5>ID Pedido: <?php echo htmlspecialchars($row['pedido_id']); ?></h5>
                    <div class="row">
                        <div class="col-4">Nome do Cliente:</div>
                        <div class="col-8"><?php echo htmlspecialchars($row['cliente_nome']); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-4">Endereço:</div>
                        <div class="col-8"><?php echo htmlspecialchars($row['endereco']); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-4">Email:</div>
                        <div class="col-8"><?php echo htmlspecialchars($row['email']); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-4">Total do Pedido:</div>
                        <div class="col-8">R$ <?php echo htmlspecialchars(number_format($row['pedido_total'], 2, ',', '.')); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-4">Data do Pedido:</div>
                        <div class="col-8"><?php echo htmlspecialchars($row['data_pedido']); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-4">Produtos:</div>
                        <div class="col-8"><?php echo htmlspecialchars($row['produtos_nome']); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-4">Preços:</div>
                        <div class="col-8"><?php echo htmlspecialchars($row['produtos_preco']); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-4">Quantidades:</div>
                        <div class="col-8">
                            <span class="quantities"><?php echo htmlspecialchars($row['quantidades']); ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">Imagens:</div>
                        <div class="col-8">
                            <?php
                            $imagens = explode(',', $row['produtos_imagem']);
                            foreach ($imagens as $imagem): ?>
                                <img src="<?php echo htmlspecialchars(trim($imagem)); ?>" alt="Imagem do Produto">
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                Nenhum pedido encontrado.
            </div>
        <?php endif; ?>
    </div>

    <?php $conn->close(); ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

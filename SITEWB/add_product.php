<?php
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', 'root', 'shop_dbb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Definir o diretório de uploads
$uploadDir = 'uploads/'; // Você pode mudar este diretório conforme necessário

// Criar o diretório se não existir
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coletar dados do formulário
    $name = $_POST['name'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];

    // Verificar se o arquivo de imagem foi enviado
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $imagePath = $uploadDir . basename($imageName);

        // Mover o arquivo de imagem para o diretório de uploads
        if (move_uploaded_file($imageTmp, $imagePath)) {
            // Inserir dados no banco de dados
            $stmt = $conn->prepare("INSERT INTO products (name, price, rating, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdds", $name, $price, $rating, $imagePath);

            if ($stmt->execute()) {
                echo "Produto adicionado com sucesso!";
                
            } else {
                echo "Erro ao adicionar o produto: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erro ao mover o arquivo de imagem.";
        }
    } else {
        echo "Nenhuma imagem foi enviada ou ocorreu um erro no upload.";
    }
}

$conn->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>

<?php
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', 'root', 'shop_dbb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar se uma ação de exclusão foi solicitada
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Preparar e executar a consulta de exclusão
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Produto excluído com sucesso!";
    } else {
        echo "Erro ao excluir o produto: " . $stmt->error;
    }

    $stmt->close();
}

// Consultar os produtos
$sql = "SELECT id, name, price, rating, image FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-primary text-white text-center py-4 mb-4">
        <div class="container">
            <a href="index.php" class="btn btn-light mb-2">Voltar</a>
            <a href="pedidos.php" class="btn btn-light mb-2">Ver pedidos</a>
            <h1>Gerenciar Produtos</h1>
        </div>
    </header>

    <div class="container">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Avaliação</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>R$<?php echo htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['rating']); ?> estrelas</td>
                            <td><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Imagem do Produto" width="100"></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhum produto encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php $conn->close(); ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <link rel="stylesheet" href="style.css">
    <!-- Link do Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <style>
        /* Estilos para a seção de adicionar produtos */
        .add-product {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .add-product h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .add-product form {
            margin: 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: #333;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            color: #333;
        }

        .form-group input[type="file"] {
            padding: 3px;
        }

        .btn {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #218838;
        }

        .products {
            margin-top: 20px;
        }

        /* Estilos para o slider de produtos */
        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .swiper-slide img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .swiper-slide h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }

        .swiper-slide .price {
            margin-bottom: 10px;
            font-size: 16px;
            color: #28a745;
        }

        .stars {
            margin-bottom: 10px;
        }

        .stars i {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <section class="add-product">
        <h1 class="heading">Nossos <span>Lanches</span></h1>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nome do Produto:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="price">Preço:</label>
                <input type="text" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="rating">Avaliação (0.0 a 5.0):</label>
                <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" required>
            </div>
            <div class="form-group">
                <label for="image">Imagem do Produto:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn">Adicionar Produto</button>
            <a href="manage_products.php" class="btn">Gerenciar Produtos</a>
        </form>
    </section>

   
    <!-- Link do Swiper JS -->

</body>
</html>


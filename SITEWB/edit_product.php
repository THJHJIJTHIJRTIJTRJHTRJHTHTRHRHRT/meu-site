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

// Verificar se um ID foi passado para edição
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Consultar o produto pelo ID
    $stmt = $conn->prepare("SELECT name, price, rating, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($name, $price, $rating, $image);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Nenhum ID de produto fornecido.";
    exit;
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coletar dados do formulário
    $name = $_POST['name'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $imagePath = $image;

    // Verificar se o arquivo de imagem foi enviado
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $imagePath = $uploadDir . basename($imageName);

        // Mover o arquivo de imagem para o diretório de uploads
        if (!move_uploaded_file($imageTmp, $imagePath)) {
            echo "Erro ao mover o arquivo de imagem.";
        }
    }

    // Atualizar dados no banco de dados
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, rating = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sddsi", $name, $price, $rating, $imagePath, $id);

    if ($stmt->execute()) {
        echo "Produto atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o produto: " . $stmt->error;
    }

    $stmt->close();
    header("Location: add_product.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="style.css">
    <!-- Link do Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <section class="add-product">
        <h1>Editar Produto</h1>
        <form action="edit_product.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nome do Produto:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Preço:</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
            </div>
            <div class="form-group">
                <label for="rating">Avaliação (0.0 a 5.0):</label>
                <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" value="<?php echo htmlspecialchars($rating); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Imagem do Produto:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <p>Imagem atual: <img src="<?php echo htmlspecialchars($image); ?>" alt="Imagem do Produto" width="100"></p>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="add_product.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </section>

    <!-- Link do Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

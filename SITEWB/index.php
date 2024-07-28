<?php
session_start();

// Verificar se o usuário está autenticado e é um admin
if (!isset($_SESSION['user_id'])) {
    // Usuário não autenticado, redirecionar para a página de login
    header("Location: login.php");
    exit();
} else if ($_SESSION['role'] === 'admin') {
    // Usuário autenticado como admin, pode acessar a página
    // Coloque aqui o código específico para a página do admin
} else if ($_SESSION['role'] === 'client') {
    // Usuário autenticado como client, pode acessar a página
    // Coloque aqui o código específico para a página do cliente
} else {
    // Usuário autenticado, mas não tem permissão, redirecionar para a página de login
    header("Location: login.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Cantina</title>
    <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>


    <header class="header">
        <a href="#" class="logo"><i class="fas fa-utensils"></i> Cantina Site</a>
        <nav class="navbar">
            <a href="#home"><i class="fas fa-home"></i> Página Inicial</a>
            <a href="#products"><i class="fas fa-box"></i> Lanches</a>
            <a href="#categories"><i class="fas fa-th-list"></i> Categorias</a>
            <a href="#comment-section"><i class="fas fa-star"></i> Revisão</a>
            <a href="admin_home.php"><i class=""></i>Perfil de admin</a>
            <a href="client_home.php"><i class=""></i>Perfil de alunos</a>
           
        </nav>
        <div class="icons">
            <div class="fas fa-bars" id="menu-btn"></div>
            <div class="fas fa-search" id="search-btn"></div>
            <div class="fas fa-shopping-cart" id="cart-btn"></div>
            <div class="fas fa-user" id="login-btn"></div>
        </div>
        <form action="" class="search-form">
            <input type="search" id="search-box" placeholder="Pesquise aqui">
            <label for="search-box" class="fa fa-search"></label>
        </form>
        <div class="shopping-cart">
            <a href="cart.php" class="btn">Veja seu carrinho</a>
           </div>



        <form action="index.php" class="login-form">
            <h3>Entre agora</h3>
            <h3>Se já entrou clique aqui</h3>
            <a href="admin_home.php" class="btn">Perfil dos admins</a>
            <a href="client_home.php"class="btn">Perfil dos alunos</a>
            <a href="login.php" class="btn" href="">Clique aqui</a>
        </form>
    </header>


    <section class="home" id="home">
        <div class="content">
            <h3>Lanches mais <span>vendidos</span></h3>
            <p>Aqui voce encontra <span>Sucos</span>,<span>Sobremesas</span>,<span>Comidas</span> e muito mais</p>
            <a href="#" class="btn">Compre agora</a>
        </div>


    </section>


    <section class="features" id="features">
        <h1 class="heading">Nossas <span>Competencias</span></h1>
        <div class="box-container">
            <div class="box">
            <img src="logo.pnga" alt="">
            <h3>Catálogo de Produtos</h3>
            <p>Exibição de um menu completo com descrições detalhadas, imagens e preços.</p>
            <a href="#" class="btn">Ver mais</a>
            </div>
       
       
            <div class="box">
            <img src="logo.pnga" alt="">
            <h3>Sistema de Pedidos Online</h3>
            <p>Funcionalidade de carrinho de compras que permite adicionar, remover e modificar itens.</p>
            <a href="#" class="btn">Ver mais</a>


        </div>
       
            <div class="box">
            <img src="logo.pnga" alt="">
            <h3>Gestão de Estoque</h3>
            <p>Sistema de notificações para informar os clientes sobre a reposição de produtos fora de estoque.</p>
            <a href="#" class="btn">Ver mais</a>


        </div>
       
            <div class="box">
            <img src="logo.pnga" alt="">
            <h3>Marketing e Promoções</h3>
            <p>Ofertas e descontos personalizados.</p>
            <a href="#" class="btn">Ver mais</a>


        </div>
    </div>


    </section>

    <section class="products" id="products">
    <h1 class="heading">Nossos <span>Lanches</span></h1>

    <div class="swiper product-slider">
        <div class="swiper-wrapper">

            <?php
            // Conectar ao banco de dados
            $conn = new mysqli('localhost', 'root', 'root', 'shop_dbb');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Consulta para obter os produtos, incluindo o id
            $sql = "SELECT id, name, price, rating, image FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Definir as estrelas
                    $rating = (float)$row['rating'];
                    $fullStars = floor($rating);
                    $halfStar = ($rating - $fullStars) > 0 ? '<i class="fas fa-star-half-alt"></i>' : '';
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                    echo '<div class="swiper-slide box">';
                    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<div class="price">R$' . htmlspecialchars($row['price']) . '</div>';
                    echo '<div class="stars">';
                    echo str_repeat('<i class="fas fa-star"></i>', $fullStars);
                    echo $halfStar;
                    echo str_repeat('<i class="far fa-star"></i>', $emptyStars);
                    echo '</div>';
                    echo '<a href="add_cart.php?id=' . htmlspecialchars($row['id']) . '" class="btn">Adicionar ao carrinho</a>';
                    echo '</div>';
                }
            } else {
                echo '<div class="swiper-slide box">Nenhum produto encontrado.</div>';
            }

            $conn->close();
            ?>

        </div> <!-- swiper-wrapper -->
    </div> <!-- swiper product-slider -->
</section>

    <footer class="container mt-5">
        <h2>Ouça uma música enquanto navega</h2>
        <audio controls>
            <source src="REMIX2024.mp3" type="audio/mpeg">
            Seu navegador não suporta o elemento de áudio.
        </audio>
    </footer>


    <section class="categories" id="categories">
        <h1 class="heading"><span>Categorias</span> de lanches</h1>
        <div class="box-container">


            <div class="box">
                <img src="" alt="">
                <h3>Sucos</h3>
                <p>50% de desconto</p>
                <a href="#" class="btn">Compre agora</a>


            </div>
            <div class="box">
                <img src="" alt="">
                <h3>Sobremesas</h3>
                <p>40% de desconto</p>
                <a href="#" class="btn">Compre agora</a>


            </div>
            <div class="box">
                <img src="" alt="">
                <h3>Salgados</h3>
                <p>45% de desconto</p>
                <a href="#" class="btn">Compre agora</a>


            </div>
            <div class="box">
                <img src="" alt="">
                <h3>Balas</h3>
                <p>Sem desconto</p>
                <a href="#" class="btn">Compre agora</a>


            </div>


        </div>


    </section>


    <section class="comment-section">
        <h1 class="heading"><span>Área</span> de comentários</h1>
        <h2>Deixe seu comentário</h2>
        <form id="comment-form">
            <input type="text" id="name" placeholder="Seu nome" required>
            <textarea id="comment" placeholder="Seu comentário" required></textarea>
            <button type="submit">Enviar</button>
        </form>
        <div id="comments-container"></div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>



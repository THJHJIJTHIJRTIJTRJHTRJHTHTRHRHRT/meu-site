create database shop_dbb;
use shop_dbb;

create table lanche(
id int not null auto_increment primary key,
nome varchar(45) not null,
preco double not null,
descricao varchar(45)
);
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    rating DECIMAL(2, 1) NOT NULL,
    image VARCHAR(255) NOT NULL
);

CREATE TABLE funcionario(
  idFuncionario int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nome varchar(60) NOT NULL,
  email varchar(60) NOT NULL,
  senha varchar(60) NOT NULL,
confirmarSenha varchar(60) NOT NULL
);
CREATE TABLE user_form (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_id INT,
    name VARCHAR(255) NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES products(id),
     FOREIGN KEY (name) REFERENCES products(name)
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    endereco VARCHAR(255),
    email VARCHAR(255),
    total DECIMAL(10, 2),
    data_pedido DATE
);

-- Update the products table to include quantity
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    quantity INT NOT NULL
);
SELECT 
    p.id AS pedido_id, 
    p.nome AS cliente_nome, 
    p.endereco, 
    p.email, 
    p.total AS pedido_total, 
    p.data_pedido,
    pr.name AS produto_nome, 
    pr.image AS produto_imagem,
    pr.quantity AS produto_quantidade
FROM 
    pedidos p
INNER JOIN 
    products pr ON pr.id = p.id;  -- Adjust this join if `p.id` does not actually relate to `pr.id`
  -- Note: This condition assumes p.id and pr.id are related, which may not be correct
 -- Note: This join condition assumes `p.id` and `pr.id` are related, which is unusual and typically incorrect


select * from products;
select * from itens_pedido;
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user_form(id) ON DELETE CASCADE
);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client') NOT NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);


select * from pedidos;

CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    nome_produto VARCHAR(255) NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);


select * from lanche;
select * from funcionario;
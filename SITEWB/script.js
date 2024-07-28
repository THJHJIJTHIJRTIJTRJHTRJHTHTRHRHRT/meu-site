let searchForm = document.querySelector('.search-form');

        document.querySelector('#search-btn').onclick = () => {
            searchForm.classList.toggle('active');
            loginform.classList.remove('active');
            shoppingcart.classList.remove('active');
           
            navbar.classList.remove('active');
        }


            let shoppingcart = document.querySelector('.shopping-cart');

        document.querySelector('#cart-btn').onclick = () => {
            shoppingcart.classList.toggle('active');
            loginform.classList.remove('active');
           
            searchForm.classList.remove('active');
            navbar.classList.remove('active');
        }

            let loginform = document.querySelector('.login-form');

            document.querySelector('#login-btn').onclick = () => {
                loginform.classList.toggle('active');
               
                shoppingcart.classList.remove('active');
                searchForm.classList.remove('active');
                navbar.classList.remove('active');}

                let navbar = document.querySelector('.navbar');

            document.querySelector('#menu-btn').onclick = () => {
                navbar.classList.toggle('active');
                loginform.classList.remove('active');
                shoppingcart.classList.remove('active');
                searchForm.classList.remove('active');
                
            }
            window.onscroll = () => {
                loginform.classList.remove('active');
                shoppingcart.classList.remove('active');
                searchForm.classList.remove('active');
                navbar.classList.remove('active');

            }

            var swiper = new Swiper(".product-slider", {
                loop:true,
                
                spaceBetween: 20,
                autoplay: {
                    delay: 7500,
                    disableOnInteraction: false,
                  },
              
                breakpoints: {
                  0: {
                    slidesPerView: 1,
                    
                  },
                  768: {
                    slidesPerView: 2,
                   
                  },
                  1020: {
                    slidesPerView: 3,
                  },
                },
              });



              document.addEventListener('DOMContentLoaded', function() {
                const commentsContainer = document.getElementById('comments-container');
    
                // Load comments from localStorage
                const comments = JSON.parse(localStorage.getItem('comments')) || [];
                comments.forEach(comment => {
                    addCommentToDOM(comment.name, comment.comment);
                });
    
                document.getElementById('comment-form').addEventListener('submit', function(event) {
                    event.preventDefault();
    
                    const name = document.getElementById('name').value;
                    const comment = document.getElementById('comment').value;
    
                    addCommentToDOM(name, comment);
    
                    // Save comment to localStorage
                    comments.push({ name, comment });
                    localStorage.setItem('comments', JSON.stringify(comments));
    
                    document.getElementById('comment-form').reset();
                });
    
                function addCommentToDOM(name, comment) {
                    const commentCard = document.createElement('div');
                    commentCard.classList.add('comment-card');
    
                    const commentName = document.createElement('h3');
                    commentName.textContent = name;
    
                    const commentText = document.createElement('p');
                    commentText.textContent = comment;
    
                    commentCard.appendChild(commentName);
                    commentCard.appendChild(commentText);
    
                    commentsContainer.appendChild(commentCard);
                }
            });
              

            


            document.addEventListener('DOMContentLoaded', () => {
              const form = document.getElementById('login-form');
          
              form.addEventListener('submit', (event) => {
                  event.preventDefault();
          
                  const email = document.getElementById('email').value;
                  const password = document.getElementById('password').value;
                  const isAdmin = document.getElementById('admin').checked;
          
                  // Simulate authentication and admin check
                  authenticateUser(email, password, isAdmin)
                      .then(isAdminUser => {
                          if (isAdminUser) {
                              window.location.href = '/admin-dashboard'; // Redirect to admin dashboard
                          } else {
                              window.location.href = '/user-dashboard'; // Redirect to user dashboard
                          }
                      })
                      .catch(error => {
                          alert(`Erro: ${error.message}`);
                      });
              });
          
              function authenticateUser(email, password, isAdmin) {
                  return new Promise((resolve, reject) => {
                      // Simulate a server request
                      setTimeout(() => {
                          if (email && password) {
                              if (isAdmin) {
                                  // Check if admin credentials are correct
                                  resolve(email === 'admin@example.com' && password === 'adminpassword');
                              } else {
                                  // Check if regular user credentials are correct
                                  resolve(email === 'user@example.com' && password === 'userpassword');
                              }
                          } else {
                              reject(new Error('Todos os campos são obrigatórios.'));
                          }
                      }, 1000);
                  });
              }
          });



          document.addEventListener('DOMContentLoaded', function () {
    const cartItems = document.getElementById('cart-items');
    const totalPriceElement = document.getElementById('total-price');
    let totalPrice = 0;

    // Função para adicionar produto à cesta
    function addToCart(name, price) {
        // Criar um novo item de cesta
        const item = document.createElement('div');
        item.className = 'box';
        item.innerHTML = `
            <i class="fas fa-trash" onclick="removeItem(this)"></i>
            <img src="logo.png" alt="">
            <div class="content">
                <h3>${name}</h3>
                <span class="price">R$${price.toFixed(2)}</span>
                <span class="quantity">Em estoque - 1</span>
            </div>
        `;
        
        // Adicionar o item à cesta
        cartItems.appendChild(item);
        
        // Atualizar o total
        totalPrice += parseFloat(price);
        totalPriceElement.textContent = totalPrice.toFixed(2);
    }

    // Função para remover um item da cesta
    function removeItem(element) {
        const item = element.parentElement;
        const priceText = item.querySelector('.price').textContent;
        const price = parseFloat(priceText.replace('R$', '').trim());
        
        // Atualizar o total
        totalPrice -= price;
        totalPriceElement.textContent = totalPrice.toFixed(2);
        
        // Remover o item
        cartItems.removeChild(item);
    }

    // Adicionar evento aos botões de compra
    document.querySelectorAll('.buy-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Impede o comportamento padrão do link
            const name = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');
            addToCart(name, price);
        });
    });
});




document.addEventListener('DOMContentLoaded', function () {
    const cartItems = document.getElementById('cart-items');
    const totalPriceElement = document.getElementById('total-price');
    let totalPrice = 0;

    // Função para adicionar produto à cesta
    function addToCart(name, price) {
        // Criar um novo item de cesta
        const item = document.createElement('div');
        item.className = 'box';
        item.innerHTML = `
            <i class="fas fa-trash" onclick="removeItem(this)"></i>
            <img src="logo.png" alt="">
            <div class="content">
                <h3>${name}</h3>
                <span class="price">R$${parseFloat(price).toFixed(2)}</span>
                <span class="quantity">Em estoque - 1</span>
            </div>
        `;
        
        // Adicionar o item à cesta
        cartItems.appendChild(item);
        
        // Atualizar o total
        totalPrice += parseFloat(price);
        totalPriceElement.textContent = totalPrice.toFixed(2);
    }

    // Função para remover um item da cesta
    function removeItem(element) {
        const item = element.parentElement;
        const priceText = item.querySelector('.price').textContent;
        const price = parseFloat(priceText.replace('R$', '').trim());
        
        // Atualizar o total
        totalPrice -= price;
        totalPriceElement.textContent = totalPrice.toFixed(2);
        
        // Remover o item
        cartItems.removeChild(item);
    }

    // Adicionar evento aos botões de compra
    document.querySelectorAll('.buy-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Impede o comportamento padrão do link
            const name = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');
            addToCart(name, price);
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    function updateCart() {
        const cartContainer = document.getElementById('shopping-cart');
        cartContainer.innerHTML = ''; // Limpa o carrinho
        let total = 0;

        cart.forEach(item => {
            total += item.price * item.quantity; // Atualiza o total

            const itemDiv = document.createElement('div');
            itemDiv.className = 'box';

            itemDiv.innerHTML = `
                <i class="fas fa-trash" onclick="removeItem(${item.id})"></i>
                <img src="${item.image}" alt="">
                <div class="content">
                    <h3>${item.name}</h3>
                    <span class="price">R$${item.price.toFixed(2)}</span>
                    <span class="quantity">Em estoque - ${item.quantity}</span>
                </div>
            `;

            cartContainer.appendChild(itemDiv);
        });

        cartContainer.innerHTML += `<div class="total">Total - R$${total.toFixed(2)}</div>`;
        cartContainer.innerHTML += `<a href="#" class="btn">Comprar</a>`;
    }

    function addToCart(id, name, price, image) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ id, name, price, image, quantity: 1 });
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCart();
    }

    function removeItem(id) {
        const index = cart.findIndex(item => item.id === id);
        if (index !== -1) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCart();
        }
    }

    // Inicializar o carrinho
    updateCart();

    // Adicionar eventos aos botões "Adicionar ao carrinho"
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = parseFloat(this.dataset.price);
            const image = this.dataset.image;
            addToCart(id, name, price, image);
        });
    });
});




          
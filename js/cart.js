document.addEventListener('DOMContentLoaded', function () {
  const buttons = document.querySelectorAll('.add-to-cart');

  buttons.forEach(button => {
    button.addEventListener('click', function () {
      const productId = this.dataset.productId;
      const quantityInput = document.getElementById('quantity-' + productId);
      const quantity = parseInt(quantityInput.value);
      const msgElement = document.getElementById('msg-' + productId);


      if (isNaN(quantity) || quantity <= 0) {
        msgElement.textContent = "Please enter a valid quantity.";
        msgElement.style.color = "red";
        return;
      }


      function addToCart(productId) {
          fetch('./cart/add_to_cart.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `product_id=${productId}&quantity=1`
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              updateCartCount(); 
            } else {
              alert(data.message);
            }
          });
        }

        function updateCartCount() {
          fetch('./cart/cart_count.php')
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                document.getElementById('cart-count').innerText = data.count;
              }
            });
        }
      
    });
  });
});

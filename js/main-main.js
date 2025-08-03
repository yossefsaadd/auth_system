document.addEventListener('DOMContentLoaded', () => {

  // Add to cart buttons
  document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', () => {
      const productId = btn.getAttribute('data-product-id');
      const quantityInput = document.getElementById(`quantity-${productId}`);
      const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

      if (!productId || isNaN(quantity) || quantity < 1) {
        alert('Product or quantity is invalid.');
        return;
      }

      addToCart(productId, quantity);
    });
  });

  updateCartCount();

  // Go to cart
  const cartIcon = document.getElementById('cart-icon');
  if (cartIcon) {
    cartIcon.addEventListener('click', () => {
      goToCart();
    });
  }

  // Remove from cart
  document.querySelectorAll('.remove-from-cart').forEach(btn => {
    btn.addEventListener('click', () => {
      const productId = btn.getAttribute('data-product-id');
      if (confirm('Are you sure you want to remove this item?')) {
        removeFromCart(productId);
      }
    });
  });

  // Wishlist actions
  document.querySelectorAll('.wishlist-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const productId = this.dataset.productId;
      const action = this.dataset.action;
      const url = action === 'delete' ? 'wishlist/delete.php' : 'wishlist/add_to_wishlist.php';
      const button = this;

      fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'product_id=' + encodeURIComponent(productId)
      })
      .then(response => response.text())
      .then(data => {
        alert(data.trim());
        if (action === 'add') {
          button.innerHTML = '❤️';
          button.dataset.action = 'delete';
          button.title = 'Remove from wishlist';
        } else {
          button.innerHTML = '🤍';
          button.dataset.action = 'add';
          button.title = 'Add to wishlist';
        }
      });
    });
  });

  // Open product modal
  document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', function () {
      openProductModal(this);
    });
  });

  // Modal add to cart
  const addToCartBtn = document.getElementById("modalAddToCart");
  if (addToCartBtn) {
    addToCartBtn.addEventListener("click", function () {
      const productId = this.getAttribute("data-product-id");
      const quantity = parseInt(document.getElementById("modalProductQuantity").value);

      addToCart(productId, quantity);
      closeProductModal();
    });
  }

  // Close modal if background clicked
  const modal = document.getElementById("productModal");
  if (modal) {
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        closeProductModal();
      }
    });
  }

});

// Add product to cart
function addToCart(productId, quantity = 1) {
  fetch('cart/add_to_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}`
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        updateCartCount();
        alert(`✅ Added ${quantity} item(s) to cart.`);
      } else {
        alert('❌ ' + data.message);
      }
    })
    .catch(err => {
      console.error('Error adding to cart:', err);
      alert('❌ Error adding to cart.');
    });
}

// Update cart count in header
function updateCartCount() {
  fetch('cart/cart_count.php')
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const cartCountElem = document.getElementById('cart-count');
        if (cartCountElem) {
          cartCountElem.textContent = data.count;
        }
      }
    })
    .catch(err => {
      console.error('Error fetching cart count:', err);
    });
}

// Remove item from cart
function removeFromCart(productId) {
  fetch('cart/remove_from_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `product_id=${encodeURIComponent(productId)}`
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Item removed.');
        location.reload();
      } else {
        alert('Failed to remove item: ' + data.message);
      }
    })
    .catch(err => {
      console.error('Error removing item:', err);
    });
}

// Redirect to cart page
function goToCart() {
  window.location.href = 'cart/cart.php';
}

// Show product modal
function openProductModal(element) {
  const modal = document.getElementById("productModal");
  const name = element.getAttribute("data-name");
  const price = element.getAttribute("data-price");
  const description = element.getAttribute("data-description");
  const image = element.getAttribute("data-image");
  const id = element.getAttribute("data-id");

  document.getElementById("modalProductName").textContent = name;
  document.getElementById("modalProductPrice").textContent = "Price: $" + price;
  document.getElementById("modalProductDescription").textContent = description;
  document.getElementById("modalProductImage").src = "admin/" + image;
  document.getElementById("modalProductImage").alt = name;
  document.getElementById("modalProductQuantity").value = 1;

  const addToCartBtn = document.getElementById("modalAddToCart");
  addToCartBtn.setAttribute("data-product-id", id);

  modal.style.display = "flex";
}

// Hide product modal
function closeProductModal() {
  document.getElementById("productModal").style.display = "none";
}

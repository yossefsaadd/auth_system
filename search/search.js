document.querySelector('#search-input').addEventListener('input', function() {
  const query = this.value;
  fetch(`/search.php?q=${encodeURIComponent(query)}`)
    .then(res => res.json())
    .then(products => {
      const container = document.querySelector('#product-list');
      container.innerHTML = '';
      products.forEach(product => {
        const el = document.createElement('div');
        el.innerHTML = `
          <img src="${product.image}" width="80">
          <p>${product.name}</p>
          <p>$${product.price}</p>
        `;
        container.appendChild(el);
      });
    });
});
document.querySelector('#search-input').addEventListener('focus', function() {
  this.placeholder = 'Search for products...';
});
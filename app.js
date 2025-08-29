function getCart() {
  return JSON.parse(localStorage.getItem('cart') || '[]');
}

function saveCart(cart) {
  localStorage.setItem('cart', JSON.stringify(cart));
}

function updateCartCount() {
  const count = getCart().reduce((sum, item) => sum + item.qty, 0);
  const el = document.getElementById('cart-count');
  if (el) el.textContent = count;
}

function addToCart(id) {
  const cart = getCart();
  const existing = cart.find(item => item.id === id);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ id, qty: 1 });
  }
  saveCart(cart);
  updateCartCount();
}

function renderProducts() {
  const list = document.getElementById('product-list');
  if (!list) return;
  products.forEach(product => {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
      <img src="${product.image}" alt="${product.name}">
      <div class="info">
        <h3>${product.name}</h3>
        <p>${product.description}</p>
        <strong>$${product.price}</strong>
        <button data-id="${product.id}">Agregar al carrito</button>
      </div>
    `;
    list.appendChild(card);
  });
  list.addEventListener('click', e => {
    if (e.target.tagName === 'BUTTON') {
      const id = parseInt(e.target.getAttribute('data-id'), 10);
      addToCart(id);
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  renderProducts();
  updateCartCount();
});

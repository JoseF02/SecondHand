function getCart() {
  return JSON.parse(localStorage.getItem('cart') || '[]');
}

function saveCart(cart) {
  localStorage.setItem('cart', JSON.stringify(cart));
}

function removeFromCart(id) {
  const cart = getCart().filter(item => item.id !== id);
  saveCart(cart);
}

function updateCartCount() {
  const count = getCart().reduce((sum, item) => sum + item.qty, 0);
  const el = document.getElementById('cart-count');
  if (el) el.textContent = count;
}

function renderCart() {
  const itemsEl = document.getElementById('cart-items');
  const totalEl = document.getElementById('total');
  const cart = getCart();
  itemsEl.innerHTML = '';
  let total = 0;
  cart.forEach(item => {
    const product = products.find(p => p.id === item.id);
    if (!product) return;
    const li = document.createElement('li');
    li.innerHTML = `
      <span>${product.name} (x${item.qty}) - $${(product.price * item.qty).toFixed(2)}</span>
      <button data-id="${product.id}">Eliminar</button>
    `;
    itemsEl.appendChild(li);
    total += product.price * item.qty;
  });
  totalEl.textContent = total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => {
  renderCart();
  updateCartCount();
  document.getElementById('cart-items').addEventListener('click', e => {
    if (e.target.tagName === 'BUTTON') {
      const id = parseInt(e.target.getAttribute('data-id'), 10);
      removeFromCart(id);
      renderCart();
      updateCartCount();
    }
  });
});

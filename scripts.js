const addToCartButtons = document.querySelectorAll('.add-to-cart');

addToCartButtons.forEach(button => {
  button.addEventListener('click', () => {
    const itemId = button.dataset.itemId;
    const quantityInput = button.parentNode.querySelector('.quantity-input');
    const quantity = quantityInput.value;

    // Simplified temporary cart handling (you'll need more robust logic later)
    let cart = localStorage.getItem('cart') || '[]';
    cart = JSON.parse(cart); 
    cart.push({ itemId, quantity });
    localStorage.setItem('cart', JSON.stringify(cart));

    // Provide feedback to the user (e.g., "Item added to cart!" message)
  });
});
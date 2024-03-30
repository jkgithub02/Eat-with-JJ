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

<<<<<<< HEAD
    // Provide feedback to the user (e.g., "Item added to cart!" message)
  });
=======
   // Show the selected section
   if (selectedCategory) {
     const activeSection = document.querySelector(`[data-category="${selectedCategory}"]`);
     activeSection.style.display = 'block'; // Or use another display type if needed
   }
});


//for cart
const addToCartButtons = document.querySelectorAll('.add-to-cart');

addToCartButtons.forEach(button => {
    button.addEventListener('click', function() {
        const foodId = this.dataset.fid; 
        const quantity = document.querySelector('.quantity').value; // Assuming you have a quantity input

        // Send AJAX request to update the cart in the session
        fetch('update_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ foodId: foodId, quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Display success message (pop-up)
                alert('Item added to cart!');
            } else {
                // Handle error
                console.error('Error adding to cart:', data);
            }
        })
        .catch(error => console.error('Error:', error)); 
    });
>>>>>>> ebf9b10c6409d1c7bff73c277e529bf1ec5e34bd
});
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
});
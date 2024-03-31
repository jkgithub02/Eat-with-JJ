const deleteButtons = document.querySelectorAll('.delete'); 

deleteButtons.forEach(button => {
    button.addEventListener('click', function(event) {
        if (!confirm('Are you sure you want to delete this?')) {
            event.preventDefault(); 
        }
    });
});
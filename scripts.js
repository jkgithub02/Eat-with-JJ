const menuCategories = document.getElementById('menu-categories');
const menuSections = document.querySelectorAll('.menu-section');

menuCategories.addEventListener('click', (event) => {
    const selectedCategory = event.target.dataset.category;

   // Hide all menu sections
   menuSections.forEach(section => {
       section.style.display = 'none';
   });

   // Show the selected section
   if (selectedCategory) {
     const activeSection = document.querySelector(`[data-category="${selectedCategory}"]`);
     activeSection.style.display = 'block'; // Or use another display type if needed
   }
});

// $(document).ready(function() {
//     $('#loginForm').submit(function(e) {
//         e.preventDefault(); // Prevent traditional form submission

//         $.ajax({
//             type: "POST",
//             url: "login.php",
//             data: $(this).serialize(), // Serialize data for AJAX
//             success: function(response) {
//                 if (response.trim() === '1') { // Check for 'success' response from PHP
//                     Swal.fire({
//                         icon: 'success',
//                         title: 'Login Successful!',
//                         showConfirmButton: false,
//                         timer: 1500 // Auto-close after 1.5 seconds
//                     }).then(() => {
//                         window.location.href = 'menu.php'; // Redirect if successful
//                     });
//                 } else {
//                     Swal.fire({
//                         icon: 'error',
//                         title: 'Oops...',
//                         text: 'Incorrect Username or Password'
//                     });
//                 }
//             },
//             error: function() {
//                 Swal.fire({ 
//                     icon: 'error',
//                     title: 'Error',
//                     text: 'An error occurred during login.' 
//                 });
//             }
//         });
//     });
// });

document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('loginForm').addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevent default form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    let isValid = true;

    if (!username) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please enter your username.'
      });
      isValid = false;
    }

    if (!password) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please enter your password.'
      });
      isValid = false;
    }

    // If everything is valid, proceed with form submission
    if (isValid) {
      try {
        const formData = new FormData(this);
        const response = await fetch('login.php', {
          method: 'POST',
          body: formData
        });

        if (!response.ok) { // Check for HTTP errors
          throw new Error('Login request failed');
        }

        const data = await response.json();
        if (data.status === '1') {
          Swal.fire({
            icon: 'success',
            title: 'Login Successful',
            text: 'Redirecting...',
          }).then(() => {
            window.location.href = 'menu.php';
          });
        } else if (data.status === '0') {
          Swal.fire({
            icon: 'error',
            title: 'Invalid Login',
            text: 'Incorrect username or password.'
          });
        } else {
          // Handle network errors
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong with the login request. Please try again.'
          });
        }
      } catch (error) {
        // console.error(error);
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong with the login request. Please try again.'
        });
      }

    }
  })
});


function validatePassword() {
  // Password checks
  var password = document.getElementById("password").value;
  var confirm_password = document.getElementById("confirm_password").value;

  // At least one uppercase letter
  var hasUpperCase = /[A-Z]/.test(password);

  // At least one number
  var hasNumber = /\d/.test(password);

  // Minimum length of 6
  var hasMinLength = password.length >= 6;

  // Passwords should match
  var passwordsMatch = password === confirm_password;

  if (!hasUpperCase) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid Password',
      text: 'Password must contain at least one uppercase letter.'
    });
    return false;
  }

  if (!hasNumber) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid Password',
      text: 'Password must contain at least one number.'
    });
    return false;
  }

  if (!hasMinLength) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid Password',
      text: 'Password must be at least 6 characters long.'
    });
    return false;
  }

  if (!passwordsMatch) {
    Swal.fire({
      icon: 'error',
      title: 'Passwords Do Not Match',
      text: 'Please ensure your passwords match.'
    });
    return false;
  }

  // Email format validation (basic)
  var email = document.getElementById("email").value;
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple email pattern
  if (!emailRegex.test(email)) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid Email',
      text: 'Please enter a valid email address.'
    });
    return false;
  }

  // All validations passed 
  return true;
}


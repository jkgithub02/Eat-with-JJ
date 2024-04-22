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


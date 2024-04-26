function validatePassword() {
  // get passwords
  var password = document.getElementById("password").value;
  var confirm_password = document.getElementById("confirm_password").value;

  // Valid password conditions
  // at least one uppercase letter
  var hasUpperCase = /[A-Z]/.test(password);

  // at least one number
  var hasNumber = /\d/.test(password);

  // minimum length of 6
  var hasMinLength = password.length >= 6;

  // passwords should match
  var passwordsMatch = password === confirm_password;

  //no upper case letter
  if (!hasUpperCase) {
    //sweet alert for no upper case
    Swal.fire({
      icon: 'error',
      title: 'Invalid Password',
      text: 'Password must contain at least one uppercase letter.'
    });
    //fail validation
    return false;
  }

  //no number 
  if (!hasNumber) {
    //sweet alert for no numbers
    Swal.fire({
      icon: 'error',
      title: 'Invalid Password',
      text: 'Password must contain at least one number.'
    });
    //fail validation
    return false;
  }

  //check for minimum length of 6
  if (!hasMinLength) {
    //sweet alert for short password
    Swal.fire({
      icon: 'error',
      title: 'Invalid Password',
      text: 'Password must be at least 6 characters long.'
    });
    //fail validation
    return false;
  }

  //check if passwords match
  if (!passwordsMatch) {
    //sweet alert for different passwords
    Swal.fire({
      icon: 'error',
      title: 'Passwords Do Not Match',
      text: 'Please ensure your passwords match.'
    });
    //fail validation
    return false;
  }

  // Email format validation 
  var email = document.getElementById("email").value;
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // simple email pattern
  if (!emailRegex.test(email)) {
    //if email format not valid, trigger sweet alert
    Swal.fire({
      icon: 'error',
      title: 'Invalid Email',
      text: 'Please enter a valid email address.'
    });
    //fail validation
    return false;
  }

  //get phone number
  const phone = document.getElementById("phone").value;
  const phoneRegex = /^\(?([0-9]{3})\)?[ .]?([0-9]{3})[ .]?([0-9]{4})$/;


  if (!phoneRegex.test(phone)) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid Phone Number',
      text: 'Please enter a valid phone number (e.g., 1234567890)'
    });
    return false;
  }

  // All validations passed 
  return true;
}


//this is for updating profile
function validateProfile() {
  // get passwords
  var password = document.getElementById("password").value;
  var confirm_password = document.getElementById("confirm_password").value;

    // Valid password conditions
  // at least one uppercase letter
  var hasUpperCase = /[A-Z]/.test(password);

  // at least one number
  var hasNumber = /\d/.test(password);

  // minimum length of 6
  var hasMinLength = password.length >= 6;

  // passwords should match
  var passwordsMatch = password === confirm_password;

  //if password fields are not empty
  if (password !== '' || confirm_password !== '') {
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
  }

  // Email format validation
  var email = document.getElementById("email").value;
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple email pattern
  if (!emailRegex.test(email)) {
    //sweetalert if email format invalid
    Swal.fire({
      icon: 'error',
      title: 'Invalid Email',
      text: 'Please enter a valid email address.'
    });
    //fail validation
    return false;
  }

    //get phone number
  const phone = document.getElementById("phone").value;
  const phoneRegex = /^\(?([0-9]{3})\)?[ .]?([0-9]{3})[ .]?([0-9]{4})$/;

  // test phone number validation 
  if (!phoneRegex.test(phone)) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid Phone Number',
      text: 'Please enter a valid phone number (e.g., 1234567890)'
    });
    //fail validation
    return false;
  }

  // All validations passed 
  return true;
}


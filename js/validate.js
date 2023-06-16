function validateForm() {
    // Retrieve form input values
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;
    var email = document.getElementById("email").value;
  
    // Check if password and confirm password match
    if (password !== confirmPassword) {
      alert("Passwords do not match.");
      return false; // Prevent form submission
    }
  
    // Perform additional validation if needed
    // ...
  
    return true; // Allow form submission
  }
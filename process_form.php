<?php
include 'dbcon.php';

// Perform form validation and database operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Check if the email or username already exists
    $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User with the same email or username already exists, redirect back to the registration form
        header("Location: index.php?error=User with the same email or username already exists");
        exit();
    } else {
        // Insert the new user into the database
        $insertSql = "INSERT INTO users (username, email, firstname, lastname, phone, password) VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ssssss", $username, $email, $firstname, $lastname, $phone, $password);
        $insertStmt->execute();

  // Save the registration details in a database or perform necessary operations

// Display JavaScript popup message
echo '<script>alert("Registration successful! You can now log in with your username and password.");</script>';

// Redirect the user to the signin page after a brief delay
echo '<script>setTimeout(function(){ window.location.href = "signin.php"; }, 1000);</script>';
exit();
}
}
?>

<?php
// Function to check if the user is logged in
function isLoggedIn() {
    // Check if a user session is set
    if (isset($_SESSION['user_id'])) {
        // User session is set, user is logged in
        return true;
    } else {
        // User session is not set, user is not logged in
        return false;
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Perform validation
    $errors = [];

    // Validate first name
    if (empty($firstname)) {
        $errors[] = "First name is required";
    }

    // Validate last name
    if (empty($lastname)) {
        $errors[] = "Last name is required";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password should be at least 6 characters long";
    }

    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors[] = "Confirm password is required";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Connect to the database
        $host = 'localhost';
        $username = 'root';
        $dbPassword = '';
        $database = 'fenose';

        $conn = new mysqli($host, $username, $dbPassword, $database);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the email or phone number already exists in the database
        $query = "SELECT * FROM users WHERE email = '$email' OR phone = '$phone'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $errors[] = "Email or phone number already exists";
        } else {
            // Insert the user's information into the database
            $insertQuery = "INSERT INTO users (firstname, lastname, email, phone, password) VALUES ('$firstname', '$lastname', '$email', '$phone', '$password')";

            if ($conn->query($insertQuery) === TRUE) {
                // Registration successful
                header("Location: success.php");
                exit();
            } else {
                $errors[] = "Error: " . $insertQuery . "<br>" . $conn->error;
            }
        }

        // Close the database connection
        $conn->close();
    }
}

// Start a session
session_start();

// HTML code for the registration form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fen ose Sign Up</title>
  <style>
   /* CSS styles for the form container */
.popup-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}

/* CSS styles for the form content */
.popup-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    max-width: 500px; /* Increased form width */
    width: 100%;
    text-align: center;
    position: relative;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}
 /* Styles for the close button */
 .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background-color: transparent;
            border: none;
            outline: none;
            cursor: pointer;
        }
        .close-button:before,
        .close-button:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 10px;
            height: 2px;
            background-color: #000;
        }

        .close-button:before {
            transform: rotate(45deg);
        }

        .close-button:after {
            transform: rotate(-45deg);
        }

/* CSS styles for the close button */
.popup-close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 18px;
    color: #888;
    cursor: pointer;
}

/* CSS styles for the form fields */
.popup-content input[type="text"],
.popup-content input[type="email"],
.popup-content input[type="password"] {
    width: 100%;
    height: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

/* CSS styles for the submit button */
.popup-content input[type="submit"] {
    background-color: navy;
    color: #fff;
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 18px;
}

/* CSS styles for the error messages */
.popup-content .error {
    color: red;
    font-size: 14px;
    margin-top: 5px;
    text-align: left;
}

</style>





<script>
      // Function to hide the popup form
      function hidePopupForm() {
            var popupContainer = document.getElementById('popup-container');
            popupContainer.style.display = 'none';
        }
    </script>
</head>
<body>
    <div class="popup-container" id="popup-container">
        <div class="popup-content">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <!-- Form fields -->
                <br>
                <input type="text" name="firstname" placeholder="First Name" required>
                <input type="text" name="lastname" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirmPassword" placeholder="Confirm Password" required>

                <!-- Submit button -->
                <input type="submit" value="Register">
               <p>Already have an account? <a href="login.php" style="color: red; text-decoration: none;">Log in</a></p>
               <button class="close-button" onclick="hidePopupForm()"></button>
                <?php
                // Display validation errors, if any
                if (!empty($errors)) {
                    echo "<div>";
                    echo "<h4>Please correct the following errors:</h4>";
                    echo "<ul>";
                    foreach ($errors as $error) {
                        echo "<li>$error</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
                ?>
            </form>
        </div>
    </div>

    <script>
    // Function to show the popup form
    function showPopupForm() {
        var popupContainer = document.getElementById('popup-container');
        popupContainer.style.display = 'block';
    }

    // Function to hide the popup form
    function hidePopupForm() {
        var popupContainer = document.getElementById('popup-container');
        popupContainer.style.display = 'none';
    }

    // Function to check if the user is logged in
    function isLoggedIn() {
        // Check if a user session is set
        if (typeof <?php echo $_SESSION['user_id']; ?> !== 'undefined') {
            // User session is set, user is logged in
            return true;
        } else {
            // User session is not set, user is not logged in
            return false;
        }
    }

    // Function to check login status and show the popup form if necessary
    function checkLoginStatus() {
        if (!isLoggedIn()) {
            showPopupForm();
        }
    }

    // Event listener for checking login status every 2 minutes
    setInterval(checkLoginStatus, 120000);
</script>

</body>
</html>

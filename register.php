
<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'C:\wamp64\www\FEN_OSE\PHPMailer-master\PHPMailer-master\src\Exception.php';
require 'C:\wamp64\www\FEN_OSE\PHPMailer-master\PHPMailer-master\src\PHPMailer.php';
require 'C:\wamp64\www\FEN_OSE\PHPMailer-master\PHPMailer-master\src\SMTP.php';

// Connection to the database
$host = 'localhost';
$user = 'root';
$dbPassword = '';
$database = 'fenose';

$conn = new mysqli($host, $user, $dbPassword, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define an empty array to store validation errors
$errors = [];

// Process the registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Validate form data
    if (empty($username)) {
        $errors[] = "Username is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($firstname)) {
        $errors[] = "First Name is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif ($password !== $confirmpassword) {
        $errors[] = "Passwords do not match";
    }

    // Check if the email or username already exists in the database
    $query = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Email or username already exists";
    } else {
        // If there are no validation errors, insert into the database and send confirmation email
        if (empty($errors)) {
            // Insertion query
            $insertQuery = "INSERT INTO users (username, email, firstname, lastname, phone, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssssss", $username, $email, $firstname, $lastname, $phone, $password);

            if ($stmt->execute()) {
                // Send the confirmation email
                $mail = new PHPMailer(true);
                try {
                    // Configure SMTP settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';  // Specify your SMTP server address
                    $mail->SMTPAuth = true;
                    $mail->Username = 'fenose.sl@gmail.com';  // Your SMTP username
                    $mail->Password = 'ssgnayysytyralan';  // Your SMTP password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;  // Set the appropriate SMTP port

                    // Set the 'From' address and recipient
                    $mail->setFrom('fenose.sl@gmail.com', 'FenOse');  // Replace with your email address and name
                    $mail->addAddress($email, $firstname);  // Email address and name of the recipient

                    // Set email content
                    $mail->isHTML(true);
                    $mail->Subject = 'Confirmation Email';
                    $mail->Body = 'Thank you for registering!<br> Welcome to Fen Ose.Feel free to look around and list your property';  // Customize the email body as needed
                    date_default_timezone_set('Africa/Freetown');

                    // Send the email
                    $mail->send();

                    // Display success message
                    echo '<script>alert("Registration successful! You can now log in with your username and password.");</script>';

                    // Redirect the user to the signin page after a brief delay
                    echo '<script>setTimeout(function(){ window.location.href = "signin.php"; }, 600);</script>';
                    exit();
                } catch (Exception $e) {
                    // Handle any exceptions or errors that occur while sending the email
                    echo 'Error: ' . $e->getMessage();
                }
            } else {
                echo "Error: " . $insertQuery . "<br>" . $conn->error;
            }
        } else {
            // Display error messages
            foreach ($errors as $error) {
                echo "<p class='error'>$error</p>";
            }
        }
    }
}

// Close the database connection
$conn->close();
?>


<!doctype html>
<html  lang="zxx">


<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>FEN OSE </title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/modal.css">
    <!-- Font Icons css -->
    <link rel="stylesheet" href="css/font-icons.css">
    <!-- plugins css -->
    <link rel="stylesheet" href="css/plugins.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
     <!-- Toogle css -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>

<body>


<!-- Body main wrapper start -->
<div class="body-wrapper">
    <!-- HEADER AREA START (header-5) -->
    <header class="ltn__header-area ltn__header-5 ltn__header-transparent--- gradient-color-4---">
        <!-- ltn__header-top-area start -->
        <div class="ltn__header-top-area section-bg-6 top-area-color-white---">
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="ltn__top-bar-menu">
                            <ul>
                                <li><a href="mailto:fenose.sl@gmail.com?Subject="><i class="icon-mail"></i> fenose.sl@gmail.com</a></li>
                                <li><a href="locations.php"><i class="icon-placeholder"></i> 24 Hill station</a></li>
                            </ul>
                        </div>
                    </div>
                   
                    <div class="col-md-5">
                        <div class="top-bar-right text-end">
                            <div class="ltn__top-bar-menu">
                                <ul>
                                    <li>
                                        <!-- ltn__social-media -->
                                        <div class="ltn__social-media">
                                            <ul>
                                                <li><a href="https://www.facebook.com" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                                <li><a href="https://www.twitter.com" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                                
                                                <li><a href="https://www.instagram.com" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                                <li><a href="https://dribbble.com" title="Dribbble"><i class="fab fa-dribbble"></i></a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        
                                        <!-- header-top-btn -->
                                        <div class="header-top-btn">
                                            <a href="signin.php">Sign In</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ltn__header-top-area end -->
        

        <!-- ltn__header-middle-area start -->
        <div class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-white">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="site-logo-wrap">
                            <div class="site-logo">
                                <a href="index.php" ><img src="img/logo.jpg " alt="Logo"></a>
                            </div>
                           
                        </div>
                      
                          
                    </div>
                    <div class="col header-menu-column">
                        <div class="header-menu d-none d-xl-block">
                            <nav>
                                <div class="ltn__main-menu">
                                    <ul>
                                        <li class="menu-icon"><a href="#">Home</a>
                                            <ul class="sub-menu menu-pages-img-show">
                                                <li>
                                                    <a href="index.php">Home Style 01</a>
                                                    <img src="img/home-demos/home-1.jpg" alt="#">
                                                </li>
                                             
                                                <li>
                                                    <a href="index-2.php">Home Style 2</a>
                                                    <img src="img/home-demos/home-11.jpg" alt="#">
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="menu-icon"><a href="#">About</a>
                                            <ul>
                                                <li><a href="about.php">About</a></li>
                                                <li><a href="service.php">Services</a></li>
                                                <li><a href="portfolio.php">Portfolio</a></li>
                                                <li><a href="team-details.php">Team Details</a></li>
                                                <li><a href="faq.php">FAQ</a></li>
                                                </ul>
                                                </li>
                                        
                                        <li class="menu-icon"><a href="blog.php">News</a>
                                            <ul>
                                                <li><a href="blog.php">News</a></li>
                                               
                                            </ul>
                                        </li>
                                        <li class="menu-icon"><a href="#">Pages</a>
                                            <ul class="mega-menu">
                                                <li><a href="#">Inner Pages</a>
                                                    <ul>
                                                        <li><a href="portfolio.php">Portfolio</a></li>
                                                        <li><a href="team-details.php">Team Details</a></li>
                                                        <li><a href="faq.php">FAQ</a></li>
                                                        </ul>
                                                </li>
                                                      
                                                 
                                                 
                                                <li><a href="#">Inner Pages</a>
                                                    <ul>
                                                        <li><a href="about.php">About Us</a></li>
                                                        <li><a href="contact.php">Contact</a></li>
                                                        <li><a href="product-details.php">Product Details</a></li>
                                                      
                                                    </ul>
                                                </li>
                                                <li><a href="#">User Pages</a>
                                                    <ul>
                                                        <li><a href="signin.php">Login</a></li>
                                                        <li><a href="register.php">Sign up</a></li>
                                                        <li><a href="forgot.php">Forgot Password?</a></li>
                                                       
                                                    </ul>
                                                </li>
                                                <li><a href="shop.html"><img src="img/banner/menu-banner-1.jpg" alt="#"></a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="contact.php">Contact</a></li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                    <div class="col ltn__header-options ltn__header-options-2 mb-sm-20">
                        <!-- header-search-1 -->
                        <div class="header-search-wrap">
                            <div class="header-search-1">
                                <div class="search-icon">
                                    <i class="icon-search for-search-show"></i>
                                    <i class="icon-cancel  for-search-close"></i>
                                </div>
                            </div>
                            <div class="header-search-1-form">
                                <form id="#" method="get"  action="#">
                                    <input type="text" name="search" value="" placeholder="Search here..."/>
                                    <button type="submit">
                                        <span><i class="icon-search"></i></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- user-menu -->
                        <div class="ltn__drop-menu user-menu">
                            <ul>
                                <li>
                                    <a href="#"><i class="icon-user"></i></a>
                                    <ul>
                                        <li><a href="signin.php">Sign in</a></li>
                                        <li><a href="register.php">Register</a></li>
                                        <li><a href="account.php">My Account</a></li>
                                        <li><a href="wishlist.php">Wishlist</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Mobile Menu Button -->
                       
                    </div>
                </div>
            </div>
        </div>



         <!-- ltn__header-middle-area end -->
    </header>
    <!-- HEADER AREA END -->
    <div class="ltn__utilize-overlay"></div>
   <!-- LOGIN AREA START (Register) -->
<div class="ltn__login-area pb-110">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area text-center">
                    <h1 class="section-title">Register <br>Your Account</h1>
                    <p>Please fill out the form below to create your account and start enjoying our services.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="account-login-inner">
                    <form action="#" class="ltn__form-box contact-form-box" method="POST">
                        <div>
                            <input type="text" name="username" placeholder="Username" required>
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $username = $_POST["username"];
                                // Validate username
                                if (empty($username)) {
                                    echo "<span class='error'>Please enter a username.</span>";
                                }
                            }
                            ?>
                        </div>
                        <div>
                          <input type="email" name="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                          <?php
                           if ($_SERVER["REQUEST_METHOD"] == "POST") {
                               $email = $_POST["email"];
                                   // Validate email
                                    if (empty($email)) {
                                        echo "<span class='error'>Please enter an email address.</span>";
                                      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                          echo "<span class='error'>Please enter a valid email address.</span>";
                                                                      }
    }
    ?>
</div>
                        <div>
                            <input type="text" name="firstname" placeholder="First Name" required>
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $firstname = $_POST["firstname"];
                                // Validate firstname
                                if (empty($firstname)) {
                                    echo "<span class='error'>Please enter First Name.</span>";
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <input type="text" name="lastname" placeholder="Last Name" required>
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $lastname = $_POST["lastname"];
                                // Validate lastname
                                if (empty($lastname)) {
                                    echo "<span class='error'>Please enter Last Name.</span>";
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <input type="text" name="phone" placeholder="Phone Number" required>
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $phone = $_POST["phone"];
                                // Validate phone
                                if (empty($phone)) {
                                    echo "<span class='error'>Please enter a Phone Number.</span>";
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <input type="password" name="password" placeholder="Password*" required>
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $password = $_POST["password"];
                                // Validate password
                                if (empty($password)) {
                                    echo "<span class='error'>Please enter a Password.</span>";
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <input type="password" name="confirmpassword" placeholder="Confirm Password*" required>
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $confirmpassword = $_POST["confirmpassword"];
                                // Validate confirm password
                                if (empty($confirmpassword)) {
                                    echo "<span class='error'>Please confirm your passwor.</span>";        
                                } elseif ($password !== $confirmpassword) {
                                    echo "<p class='error'>Passwords do not match.</p>";
                                }
                            }
                            ?>
                        </div>
                        
                        <div>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="privacy" value="1" required>
                                By clicking "create account", I consent to the fen ose privacy policy.
                            </label>
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $privacy = $_POST["privacy"];
                                // Validate checkbox
                                if (empty($privacy)) {
                                    echo "<span class='error'>Please accept the privacy policy.</span>";
                                  
                                }
                            }
                            ?>
                        </div>
                        <div class="btn-wrapper">
                            <button name="submit" class="theme-btn-1 btn reverse-color btn-block" type="submit">CREATE ACCOUNT</button>
                        </div>
                    </form>
                    <div class="by-agree text-center">
                        <p>By creating an account, you agree to our:</p>
                        <p><a href="#">TERMS OF CONDITIONS  &nbsp; &nbsp; | &nbsp; &nbsp;  PRIVACY POLICY</a></p>
                        <div class="go-to-btn mt-50">
                            <a href="signin.php">ALREADY HAVE AN ACCOUNT ?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- LOGIN AREA END -->

    <!-- CALL TO ACTION START (call-to-action-6) -->
    <div class="ltn__call-to-action-area call-to-action-6 before-bg-bottom" data-bs-bg="img/1.jpg--">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="call-to-action-inner call-to-action-inner-6 ltn__secondary-bg position-relative text-center---">
                        <div class="coll-to-info text-color-white">
                            <h1>Looking for a dream home?</h1>
                            <p>We can help you realize your dream of a new home</p>
                        </div>
                        <div class="btn-wrapper">
                            <a class="btn btn-effect-3 btn-white" href="contact.html">Explore Properties <i class="icon-next"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CALL TO ACTION END -->

    <!-- FOOTER AREA START -->
    <footer class="ltn__footer-area  ">
        <div class="footer-top-area  section-bg-2 plr--5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-about-widget">
                            <div class="footer-logo">
                                <div class="site-logo">
                                    <img style="width:200px;height:100px;" src="img/home.png" alt="Logo">
                                </div>
                            </div>
                            <p>You worry don don!</p>
                            <div class="footer-address">
                                <ul>
                                    <li>
                                        <div class="footer-address-icon">
                                            <i class="icon-placeholder"></i>
                                        </div>
                                        <div class="footer-address-info">
                                            <p>24 Hill Station, Freetown</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="footer-address-icon">
                                            <i class="icon-call"></i>
                                        </div>
                                        <div class="footer-address-info">
                                            <p><a href="tel:+0123-456789">+23278891733</a></p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="footer-address-icon">
                                            <i class="icon-mail"></i>
                                        </div>
                                        <div class="footer-address-info">
                                            <p><a href="mailto:fenose.sl">fenose.sl@gmail.com</a></p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="ltn__social-media mt-20">
                                <ul>
                                    <li><a href="https://www.facebook.com" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="https://www.twitter.com" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="https://www.linkedin.com" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                                    <li><a href="https://www.youtube.com" title="Youtube"><i class="fab fa-youtube"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-menu-widget clearfix">
                            <h4 class="footer-title">Company</h4>
                            <div class="footer-menu">
                                <ul>
                                    <li><a href="about.php">About</a></li>
                                    <li><a href="blog.php">Blog</a></li>
                                    <li><a href="faq.html">FAQ</a></li>
                                    <li><a href="contact.php">Contact us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-menu-widget clearfix">
                            <h4 class="footer-title">Services</h4>
                            <div class="footer-menu">
                                <ul>
                                   
                                    <li><a href="wishlist.php">Wish List</a></li>
                                    <li><a href="signin.php">Login</a></li>
                                    <li><a href="account.php">My account</a></li>
                                    <li><a href="about.php">Terms & Conditions</a></li>
                                    <li><a href="about.php">Promotional Offers</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-menu-widget clearfix">
                            <h4 class="footer-title">Customer Care</h4>
                            <div class="footer-menu">
                                <ul>
                                    <li><a href="signin.php">Login</a></li>
                                    <li><a href="account.php">My account</a></li>
                                    <li><a href="wishlist.php">Wish List</a></li>
                                    <li><a href="faq.php">FAQ</a></li>
                                    <li><a href="contact.php">Contact us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-12 col-12">
                        <div class="footer-widget footer-newsletter-widget">
                            <h4 class="footer-title">Newsletter</h4>
                            <p>Subscribe to our weekly Newsletter and receive updates via email.</p>
                            <div class="footer-newsletter">
                                <form action="#">
                                    <input type="email" name="email" placeholder="Email*">
                                    <div class="btn-wrapper">
                                        <button class="theme-btn-1 btn" type="submit"><i class="fas fa-location-arrow"></i></button>
                                    </div>
                                </form>
                            </div>
                            <h5 class="mt-30">We Accept</h5>
                            <img src="img/icons/payment-4.png" alt="Payment Image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ltn__copyright-area ltn__copyright-2 section-bg-7  plr--5">
            <div class="container-fluid ltn__border-top-2">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="ltn__copyright-design clearfix">
                            <p>All Rights Reserved @fenose.sl <span class="current-year"></span></p>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 align-self-center">
                        <div class="ltn__copyright-menu text-end">
                            <ul>
                                <li><a href="#">Terms & Conditions</a></li>
                                <li><a href="#">Privacy & Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER AREA END -->


</div>
<!-- Body main wrapper end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
  
</body>

</html>


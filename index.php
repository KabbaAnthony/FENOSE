<?php
session_start();

if (isset($_POST['submit'])) {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Perform validation
    $errors = [];

    // Validate first name
    if (empty($firstname)) {
        $errors[] = "First name is required";
    }

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
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

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Connect to the database
        $host = 'localhost';
        $user = 'root';
        $dbPassword = '';
        $database = 'fenose';

        $conn = new mysqli($host, $user, $dbPassword, $database);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
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
            // Insert the user's information into the database
            $insertQuery = "INSERT INTO users (username, email, firstname, lastname, phone, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssssss", $username, $email, $firstname, $lastname, $phone, $password);

            if ($stmt->execute()) {
                // Registration successful
                echo '<script>alert("Registration successful! You can now log in with your username and password.");</script>';

                // Redirect the user to the signin page after a brief delay
                echo '<script>setTimeout(function(){ window.location.href = "signin.php"; }, 600);</script>';
                exit();
            } else {
                // Error occurred during registration
                echo '<script>alert("Error occurred during registration.");</script>';

                // Redirect the user back to the registration form after a brief delay
                echo '<script>setTimeout(function(){ window.location.href = "index.php"; }, 600);</script>';
                exit();
            }
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!doctype html>
<html lang="zxx">


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



   <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
-->
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
 padding: 40px;
 border-radius: 10px;
 max-width: 600px; /* Increased form width */
 width: 100%;
 text-align: center;
 position: relative;
 box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}
.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    cursor: pointer;
}

.close-symbol {
    font-size: 20px;
    color: black;
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


<style>
 .password-toggle {
   position: relative;
 }

 .password-toggle input[type="password"] {
   padding-right: 25px;
 }

 .password-toggle .toggle-btn {
   position: absolute;
   top: 50%;
   right: 5px;
   transform: translateY(-50%);
   cursor: pointer;
 }

</style>

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


    <!-- now form  -->
    <div class="popup-container" id="popup-container">
        <div class="popup-content">
        <form method="POST" action="">
        <h2> Welcome to <span style="color: #fb412a;">fen ose</span></h2>
                <!-- Form fields -->
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                <input type="text" name="firstname" placeholder="First Name" required>
                <input type="text" name="lastname" placeholder="Last Name" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <div class="password-toggle">
  <input type="password" id="password" name="password" placeholder="Password">
  <span class="toggle-btn" onclick="togglePasswordVisibility('password')">
    <i id="open-eye-icon" class="fas fa-eye"></i>
    <i id="closed-eye-icon" class="fas fa-eye-slash" style="display: none;"></i>
  </span>
</div>
         <button type="button" class="generate-btn" onclick="generatePassword()">Suggest Password</button><br><br>
   <button class="close-button" onclick="hidePopupForm()">
                <span class="close-symbol">&#10006;</span>
            </button>
                <!-- Submit button -->
                <input type="submit" name="submit" value="Create Account">
                <p>Already have an account? <a href="signin.php" style="color: #fb412a; text-decoration: none;">Log in</a></p>

                <?php
                // Display validation errors, if any
                if (!empty($errors)) {
                    echo "<div>";
                    echo "<h4>Please fill in the correct information:</h4>";
                    echo "<ul style='list-style-type: none;'>";
                    foreach ($errors as $error) {
                        echo "<li style='color: red;'>$error</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
                ?>
            </form>
        </div>
    </div>

    <div class="ltn__utilize-overlay"></div>


    <!-- SLIDER AREA START (slider-3) -->
    <div class="ltn__slider-area ltn__slider-3  section-bg-1">
        <div class="ltn__slide-one-active slick-slide-arrow-1 slick-slide-dots-1">
            <!-- ltn__slide-item -->
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3-normal ltn__slide-item-3">
                <div class="ltn__slide-item-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <div class="slide-video mb-50 d-none">
                                            <a class="ltn__video-icon-2 ltn__video-icon-2-border" href="https://www.youtube.com/embed/tlThdr3O5Qo" data-rel="lightcase:myCollection">
                                                <i class="fa fa-play"></i>
                                            </a>
                                        </div>
                                        <h6 class="slide-sub-title white-color--- animated"><span><i class="fas fa-home"></i></span> Real Estate Agency</h6>
                                        <h1 class="slide-title animated ">Find Your Dream <br> House By Us</h1>
                                        <div class="slide-brief animated">
                                            <p>Your best place for real estate. Discover your perfect property today!</p>
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="about.php" class="theme-btn-1 btn btn-effect-1">Make An Enquiry</a>

                                        </div>
                                    </div>
                                </div>
                                <div class="slide-item-img">
                                    <img src="img/slider/citybg.jpg" alt="#">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ltn__slide-item -->
            <div class="ltn__slide-item ltn__slide-item-2  ltn__slide-item-3-normal ltn__slide-item-3">
                <div class="ltn__slide-item-inner  text-right text-end">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <h6 class="slide-sub-title white-color--- animated"><span><i class="fas fa-home"></i></span> Real Estate Agency</h6>
                                        <h1 class="slide-title animated ">The Right Place <br>of House Finding</h1>
                                        <div class="slide-brief animated">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</p>
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="service.php" class="theme-btn-1 btn btn-effect-1">OUR SERVICES</a>
                                            <a href="about.php" class="btn btn-transparent btn-effect-3">LEARN MORE</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slide-item-img slide-img-left">
                                    <img src="img/slider/21.png" alt="#">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div>
    <!-- SLIDER AREA END -->


    <!-- CAR DEALER FORM AREA START -->
    <div class="ltn__car-dealer-form-area mt--65 mt-120 pb-115---">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__car-dealer-form-tab">
                        <div class="ltn__tab-menu  text-uppercase d-none">
                            <div class="nav">
                                <a class="active show" data-bs-toggle="tab" href="#ltn__form_tab_1_1"><i class="fas fa-car"></i>Find A Car</a>
                                <a data-bs-toggle="tab" href="#ltn__form_tab_1_2" class=""><i class="far fa-user"></i>Get a Dealer</a>
                            </div>
                        </div>
                        <div class="tab-content bg-white box-shadow-1 ltn__border position-relative pb-10">
                            <div class="tab-pane fade active show" id="ltn__form_tab_1_1">
                                <div class="car-dealer-form-inner">
                                    <form action="#" class="ltn__car-dealer-form-box row">
                                        <div class="ltn__car-dealer-form-item ltn__custom-icon---- ltn__icon-car---- col-lg-3 col-md-6">
                                            <select class="nice-select">
                                                <option>Choose Area</option>
                                             <!--   <option>chicago</option>
                                                <option>London</option>
                                                <option>Los Angeles</option>
                                                <option>New York</option>
                                                <option>New Jersey</option> -->
                                            </select>
                                        </div>
                                        <div class="ltn__car-dealer-form-item ltn__custom-icon---- ltn__icon-meter---- col-lg-3 col-md-6">
                                            <select class="nice-select">
                                                <option>Property Status</option>
                                                <!--  <option>Open house</option>
                                                <option>Rent</option>
                                                <option>Sale</option>
                                                <option>Sold</option> -->
                                            </select>
                                        </div>
                                        <div class="ltn__car-dealer-form-item ltn__custom-icon---- ltn__icon-calendar---- col-lg-3 col-md-6">
                                            <select class="nice-select">
                                                <option>Property Type</option>
                                                <!--  <option>Apartment</option>
                                                <option>Co-op</option>
                                                <option>Condo</option>
                                                <option>Single Family Home</option> -->
                                            </select>
                                        </div>
                                        <div class="ltn__car-dealer-form-item ltn__custom-icon ltn__icon-calendar col-lg-3 col-md-6">
                                            <div class="btn-wrapper text-center mt-0">
                                                <!-- <button type="submit" class="btn theme-btn-1 btn-effect-1 text-uppercase">Search Inventory</button> -->
                                                <a href="shop-right-sidebar.php" class="btn theme-btn-1 btn-effect-1 text-uppercase">Find Now</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="ltn__form_tab_1_2">
                                <div class="car-dealer-form-inner">
                                    <form action="#" class="ltn__car-dealer-form-box row">
                                        <div class="ltn__car-dealer-form-item ltn__custom-icon---- ltn__icon-car---- col-lg-3 col-md-6">
                                            <select class="nice-select">
                                                <option>Choose Area</option>
                                                <option>chicago</option>
                                                <option>London</option>
                                                <option>Los Angeles</option>
                                                <option>New York</option>
                                                <option>New Jersey</option>
                                            </select>
                                        </div>
                                        <div class="ltn__car-dealer-form-item ltn__custom-icon---- ltn__icon-meter---- col-lg-3 col-md-6">
                                            <select class="nice-select">
                                                <option>Property Status</option>
                                                <option>Open house</option>
                                                <option>Rent</option>
                                                <option>Sale</option>
                                                <option>Sold</option>
                                            </select>
                                        </div>
                                        <div class="ltn__car-dealer-form-item ltn__custom-icon---- ltn__icon-calendar---- col-lg-3 col-md-6">
                                            <select class="nice-select">
                                                <option>Property Type</option>
                                                <option>Apartment</option>
                                                <option>Co-op</option>
                                                <option>Condo</option>
                                                <option>Single Family Home</option>
                                            </select>
                                        </div>
                                        <div class="ltn__car-dealer-form-item ltn__custom-icon ltn__icon-calendar col-lg-3 col-md-6">
                                            <div class="btn-wrapper text-center mt-0">
                                                <!-- <button type="submit" class="btn theme-btn-1 btn-effect-1 text-uppercase">Search Inventory</button> -->
                                                <a href="shop-right-sidebar.html" class="btn theme-btn-1 btn-effect-1 text-uppercase">Search Properties</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CAR DEALER FORM AREA END -->

    <!-- ABOUT US AREA START -->
    <div class="ltn__about-us-area pt-120 pb-90 ">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-img-wrap about-img-left">
                        <img src="img/others/7.png" alt="About Us Image">
                        <div class="about-us-img-info about-us-img-info-2 about-us-img-info-3">

                            <div class="ltn__video-img ltn__animation-pulse1">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-info-wrap">
                        <div class="section-title-area ltn__section-title-2--- mb-20">
                            <h6 class="section-subtitle section-subtitle-2 ltn__secondary-color">About Us</h6>
                            <h1 class="section-title">The Leading Real Estate
                                 Marketplace<span>.</span></h1>
                            <p>Feel free to to list your real estate asset on our platform for sale or look around for a house to buy or rent  </p>
                        </div>
                        <ul class="ltn__list-item-half clearfix">
                            <li>
                                <i class="fas fa-upload"></i>

                             Upload your Property
                            </li>
                            <li>

    <i class="fa fa-search search-icon"></i>
                                Look Around
                            </li>
                            <li>
                                <i class="flaticon-home-2"></i>
                               Purchase,Rent or Sale
                            </li>
                            <li>
                                <i class="flaticon-secure"></i>
                                Complete 24/7 Security
                            </li>
                        </ul>
                        <div class="ltn__callout bg-overlay-theme-05  mt-30">
                            <p>"Explore our site, discover the possibilities, and  embark  <br>
                               on your real estate journey with confidence. Welcome aboard!" </p>
                        </div>
                        <div class="btn-wrapper animated">
                            <a href="service.php" class="theme-btn-1 btn btn-effect-1">OUR SERVICES</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ABOUT US AREA END -->




    <!-- FEATURE AREA START ( Feature - 6) -->
    <div class="ltn__feature-area section-bg-1 pt-120 pb-90 mb-120---">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2--- text-center">
                        <h6 class="section-subtitle section-subtitle-2 ltn__secondary-color">Our Services</h6>
                        <h1 class="section-title">Our Main Focus</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__custom-gutter--- justify-content-center">
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="ltn__feature-item ltn__feature-item-6 text-center bg-white  box-shadow-1">
                        <div class="ltn__feature-icon">
                            <!-- <span><i class="flaticon-house"></i></span> -->
                            <img src="img/icons/icon-img/21.png" alt="#">
                        </div>
                        <div class="ltn__feature-info">
                            <h3><a href="service-details.html">Buy a home</a></h3>
                            <p>Step into a world of possibilities as you explore homes tailored to your desires.</p>
                            <a class="ltn__service-btn" href="service-details.html">Buy A Home <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="ltn__feature-item ltn__feature-item-6 text-center bg-white  box-shadow-1 active">
                        <div class="ltn__feature-icon">
                            <!-- <span><i class="flaticon-house-3"></i></span> -->
                            <img src="img/icons/icon-img/22.png" alt="#">
                        </div>
                        <div class="ltn__feature-info">
                            <h3><a href="service-details.html">Rent a home</a></h3>
                            <p>Search for Affordable houses that meets your pocket</p>
                            <a class="ltn__service-btn" href="service-details.html">Rent A Home <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="ltn__feature-item ltn__feature-item-6 text-center bg-white  box-shadow-1">
                        <div class="ltn__feature-icon">
                            <!-- <span><i class="flaticon-deal-1"></i></span> -->
                            <img src="img/icons/icon-img/23.png" alt="#">
                        </div>
                        <div class="ltn__feature-info">
                            <h3><a href="service-details.html">Sell a home</a></h3>
                            <p>Trust us to showcase your home's unique charm and attract the perfect buyer.</p>
                            <a class="ltn__service-btn" href="service-details.html">Sell A Home <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FEATURE AREA END -->

    <!-- PRODUCT SLIDER AREA START -->
    <div class="ltn__product-slider-area ltn__product-gutter pt-115 pb-90 plr--7">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2--- text-center">
                        <h6 class="section-subtitle section-subtitle-2 ltn__secondary-color">Properties</h6>
                        <h1 class="section-title">Featured Listings</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__product-slider-item-four-active-full-width slick-arrow-1">
                <!-- ltn__product-item -->
                <div class="col-lg-12">
                    <div class="ltn__product-item ltn__product-item-4 text-center---">
                        <div class="product-img">
                            <a href="product-details.php"><img src="img/product-3/1.jpg" alt="#"></a>
                            <div class="product-badge">
                                <ul>
                                    <li class="sale-badge bg-green">For Rent</li>
                                </ul>
                            </div>
                            <div class="product-img-location-gallery">
                                <div class="product-img-location">
                                    <ul>
                                        <li>
                                            <a href="locations.html"><i class="flaticon-pin"></i> Belmont Gardens, Chicago</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-img-gallery">
                                    <ul>
                                        <li>
                                            <a href="product-details.html"><i class="fas fa-camera"></i> 4</a>
                                        </li>
                                        <li>
                                            <a href="product-details.html"><i class="fas fa-film"></i> 2</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-price">
                                <span>34,900<label>/Month</label></span>
                            </div>
                            <h2 class="product-title"><a href="product-details.php">New Apartment Nice View</a></h2>
                            <div class="product-description">
                                <p>Beautiful Huge 1 Family House In Heart Of <br>
                                    Westbury. Newly Renovated With New Wood</p>
                            </div>
                            <ul class="ltn__list-item-2 ltn__list-item-2-before">
                                <li><span>3 <i class="flaticon-bed"></i></span>
                                    Bedrooms
                                </li>
                                <li><span>2 <i class="flaticon-clean"></i></span>
                                    Bathrooms
                                </li>
                                <li><span>3450 <i class="flaticon-square-shape-design-interface-tool-symbol"></i></span>
                                    square Ft
                                </li>
                            </ul>
                        </div>
                        <div class="product-info-bottom">
                            <div class="real-estate-agent">
                                <div class="agent-img">
                                    <a href="team-details.html"><img src="img/blog/autho.jpg" alt="#"></a>
                                </div>
                                <div class="agent-brief">
                                    <h6><a href="team-details.php">Kabba Bangura</a></h6>
                                    <small>Estate Agents</small>
                                </div>
                            </div>
                            <div class="product-hover-action">
                                <ul>
                                    <li>
                                        <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                            <i class="flaticon-expand"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                            <i class="flaticon-heart-1"></i></a>
                                    </li>
                                    <li>
                                        <a href="product-details.html" title="Product Details">
                                            <i class="flaticon-add"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ltn__product-item -->
                <div class="col-lg-12">
                    <div class="ltn__product-item ltn__product-item-4 text-center---">
                        <div class="product-img">
                            <a href="product-details.php"><img src="img/product-3/2.jpg" alt="#"></a>
                            <div class="product-badge">
                                <ul>
                                    <li class="sale-badge bg-green---">For Sale</li>
                                </ul>
                            </div>
                            <div class="product-img-location-gallery">
                                <div class="product-img-location">
                                    <ul>
                                        <li>
                                            <a href="locations.html"><i class="flaticon-pin"></i> Belmont Gardens, Chicago</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-img-gallery">
                                    <ul>
                                        <li>
                                            <a href="product-details.php"><i class="fas fa-camera"></i> 4</a>
                                        </li>
                                        <li>
                                            <a href="product-details.php"><i class="fas fa-film"></i> 2</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-price">
                                <span>34,900<label>/Month</label></span>
                            </div>
                            <h2 class="product-title"><a href="product-details.php">Modern Apartments</a></h2>
                            <div class="product-description">
                                <p>Beautiful Huge 1 Family House In Heart Of <br>
                                    Westbury. Newly Renovated With New Wood</p>
                            </div>
                            <ul class="ltn__list-item-2 ltn__list-item-2-before">
                                <li><span>3 <i class="flaticon-bed"></i></span>
                                    Bedrooms
                                </li>
                                <li><span>2 <i class="flaticon-clean"></i></span>
                                    Bathrooms
                                </li>
                                <li><span>3450 <i class="flaticon-square-shape-design-interface-tool-symbol"></i></span>
                                    square Ft
                                </li>
                            </ul>
                        </div>
                        <div class="product-info-bottom">
                            <div class="real-estate-agent">
                                <div class="agent-img">
                                    <a href="team-details.html"><img src="img/blog/autho.jpg" alt="#"></a>
                                </div>
                                <div class="agent-brief">
                                    <h6><a href="team-details.html">David Koroma</a></h6>
                                    <small>Estate Agents</small>
                                </div>
                            </div>
                            <div class="product-hover-action">
                                <ul>
                                    <li>
                                        <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                            <i class="flaticon-expand"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                            <i class="flaticon-heart-1"></i></a>
                                    </li>
                                    <li>
                                        <a href="product-details.php" title="Product Details">
                                            <i class="flaticon-add"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ltn__product-item -->
                <div class="col-lg-12">
                    <div class="ltn__product-item ltn__product-item-4 text-center---">
                        <div class="product-img">
                            <a href="product-details.php"><img src="img/product-3/3.jpg" alt="#"></a>
                            <div class="product-badge">
                                <ul>
                                    <li class="sale-badge bg-green">For Rent</li>
                                </ul>
                            </div>
                            <div class="product-img-location-gallery">
                                <div class="product-img-location">
                                    <ul>
                                        <li>
                                            <a href="locations.html"><i class="flaticon-pin"></i> Belmont Gardens, Chicago</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-img-gallery">
                                    <ul>
                                        <li>
                                            <a href="product-details.php"><i class="fas fa-camera"></i> 4</a>
                                        </li>
                                        <li>
                                            <a href="product-details.php"><i class="fas fa-film"></i> 2</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-price">
                                <span>34,900<label>/Month</label></span>
                            </div>
                            <h2 class="product-title"><a href="product-details.php">Comfortable Apartment</a></h2>
                            <div class="product-description">
                                <p>Beautiful Huge 1 Family House In Heart Of <br>
                                    Westbury. Newly Renovated With New Wood</p>
                            </div>
                            <ul class="ltn__list-item-2 ltn__list-item-2-before">
                                <li><span>3 <i class="flaticon-bed"></i></span>
                                    Bedrooms
                                </li>
                                <li><span>2 <i class="flaticon-clean"></i></span>
                                    Bathrooms
                                </li>
                                <li><span>3450 <i class="flaticon-square-shape-design-interface-tool-symbol"></i></span>
                                    square Ft
                                </li>
                            </ul>
                        </div>
                        <div class="product-info-bottom">
                            <div class="real-estate-agent">
                                <div class="agent-img">
                                    <a href="team-details.html"><img src="img/blog/autho.jpg" alt="#"></a>
                                </div>
                                <div class="agent-brief">
                                    <h6><a href="team-details.html">Juldeh Barrie</a></h6>
                                    <small>Estate Agents</small>
                                </div>
                            </div>
                            <div class="product-hover-action">
                                <ul>
                                    <li>
                                        <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                            <i class="flaticon-expand"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                            <i class="flaticon-heart-1"></i></a>
                                    </li>
                                    <li>
                                        <a href="product-details.php" title="Product Details">
                                            <i class="flaticon-add"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ltn__product-item -->
                <div class="col-lg-12">
                    <div class="ltn__product-item ltn__product-item-4 text-center---">
                        <div class="product-img">
                            <a href="product-details.php"><img src="img/product-3/4.jpg" alt="#"></a>
                            <div class="product-badge">
                                <ul>
                                    <li class="sale-badge bg-green">For Rent</li>
                                </ul>
                            </div>
                            <div class="product-img-location-gallery">
                                <div class="product-img-location">
                                    <ul>
                                        <li>
                                            <a href="locations.html"><i class="flaticon-pin"></i> Belmont Gardens, Chicago</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-img-gallery">
                                    <ul>
                                        <li>
                                            <a href="product-details.php"><i class="fas fa-camera"></i> 4</a>
                                        </li>
                                        <li>
                                            <a href="product-details.php"><i class="fas fa-film"></i> 2</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-price">
                                <span>$34,900<label>/Month</label></span>
                            </div>
                            <h2 class="product-title"><a href="product-details.php">Luxury villa in Rego Park </a></h2>
                            <div class="product-description">
                                <p>Beautiful Huge 1 Family House In Heart Of <br>
                                    Westbury. Newly Renovated With New Wood</p>
                            </div>
                            <ul class="ltn__list-item-2 ltn__list-item-2-before">
                                <li><span>3 <i class="flaticon-bed"></i></span>
                                    Bedrooms
                                </li>
                                <li><span>2 <i class="flaticon-clean"></i></span>
                                    Bathrooms
                                </li>
                                <li><span>3450 <i class="flaticon-square-shape-design-interface-tool-symbol"></i></span>
                                    square Ft
                                </li>
                            </ul>
                        </div>
                        <div class="product-info-bottom">
                            <div class="real-estate-agent">
                                <div class="agent-img">
                                    <a href="team-details.html"><img src="img/blog/author.jpg" alt="#"></a>
                                </div>
                                <div class="agent-brief">
                                    <h6><a href="team-details.html">William Seklo</a></h6>
                                    <small>Estate Agents</small>
                                </div>
                            </div>
                            <div class="product-hover-action">
                                <ul>
                                    <li>
                                        <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                            <i class="flaticon-expand"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                            <i class="flaticon-heart-1"></i></a>
                                    </li>
                                    <li>
                                        <a href="product-details.php" title="Product Details">
                                            <i class="flaticon-add"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ltn__product-item -->
                <div class="col-lg-12">
                    <div class="ltn__product-item ltn__product-item-4 text-center---">
                        <div class="product-img">
                            <a href="product-details.php"><img src="img/product-3/5.jpg" alt="#"></a>
                            <div class="product-badge">
                                <ul>
                                    <li class="sale-badge bg-green">For Rent</li>
                                </ul>
                            </div>
                            <div class="product-img-location-gallery">
                                <div class="product-img-location">
                                    <ul>
                                        <li>
                                            <a href="locations.html"><i class="flaticon-pin"></i> Belmont Gardens, Chicago</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-img-gallery">
                                    <ul>
                                        <li>
                                            <a href="product-details.php"><i class="fas fa-camera"></i> 4</a>
                                        </li>
                                        <li>
                                            <a href="product-details.php"><i class="fas fa-film"></i> 2</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-price">
                                <span>$34,900<label>/Month</label></span>
                            </div>
                            <h2 class="product-title"><a href="product-details.php">Beautiful Flat in Manhattan </a></h2>
                            <div class="product-description">
                                <p>Beautiful Huge 1 Family House In Heart Of <br>
                                    Westbury. Newly Renovated With New Wood</p>
                            </div>
                            <ul class="ltn__list-item-2 ltn__list-item-2-before">
                                <li><span>3 <i class="flaticon-bed"></i></span>
                                    Bedrooms
                                </li>
                                <li><span>2 <i class="flaticon-clean"></i></span>
                                    Bathrooms
                                </li>
                                <li><span>3450 <i class="flaticon-square-shape-design-interface-tool-symbol"></i></span>
                                    square Ft
                                </li>
                            </ul>
                        </div>
                        <div class="product-info-bottom">
                            <div class="real-estate-agent">
                                <div class="agent-img">
                                    <a href="team-details.html"><img src="img/blog/author.jpg" alt="#"></a>
                                </div>
                                <div class="agent-brief">
                                    <h6><a href="team-details.html">William Seklo</a></h6>
                                    <small>Estate Agents</small>
                                </div>
                            </div>
                            <div class="product-hover-action">
                                <ul>
                                    <li>
                                        <a href="#" title="Quick View" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                            <i class="flaticon-expand"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                            <i class="flaticon-heart-1"></i></a>
                                    </li>
                                    <li>
                                        <a href="product-details.php" title="Product Details">
                                            <i class="flaticon-add"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
    <!-- PRODUCT SLIDER AREA END -->

    <!-- CATEGORY AREA START -->
    <div class="ltn__category-area section-bg-1-- ltn__primary-bg before-bg-1 bg-image bg-overlay-theme-black-5--0 pt-115 pb-90 d-none" data-bs-bg="img/bg/5.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title white-color">Top Categories</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__category-slider-active slick-arrow-1">
                <div class="col-12">
                    <div class="ltn__category-item ltn__category-item-4 text-center">
                        <div class="ltn__category-item-img">
                            <a href="shop.html">
                                <i class="flaticon-car"></i>
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h4><a href="shop.html">Parking Space</a></h4>
                        </div>
                        <div class="ltn__category-item-btn">
                            <a href="shop.html"><i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="ltn__category-item ltn__category-item-4 text-center">
                        <div class="ltn__category-item-img">
                            <a href="shop.html">
                                <i class="flaticon-car"></i>
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h4><a href="shop.html">Parking Space</a></h4>
                        </div>
                        <div class="ltn__category-item-btn">
                            <a href="shop.html"><i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="ltn__category-item ltn__category-item-4 text-center">
                        <div class="ltn__category-item-img">
                            <a href="shop.html">
                                <i class="flaticon-car"></i>
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h4><a href="shop.html">Parking Space</a></h4>
                        </div>
                        <div class="ltn__category-item-btn">
                            <a href="shop.html"><i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="ltn__category-item ltn__category-item-4 text-center">
                        <div class="ltn__category-item-img">
                            <a href="shop.html">
                                <i class="flaticon-car"></i>
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h4><a href="shop.html">Parking Space</a></h4>
                        </div>
                        <div class="ltn__category-item-btn">
                            <a href="shop.html"><i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="ltn__category-item ltn__category-item-4 text-center">
                        <div class="ltn__category-item-img">
                            <a href="shop.html">
                                <i class="flaticon-car"></i>
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h4><a href="shop.html">Parking Space</a></h4>
                        </div>
                        <div class="ltn__category-item-btn">
                            <a href="shop.html"><i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="ltn__category-item ltn__category-item-4 text-center">
                        <div class="ltn__category-item-img">
                            <a href="shop.html">
                                <i class="flaticon-car"></i>
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h4><a href="shop.html">Parking Space</a></h4>
                        </div>
                        <div class="ltn__category-item-btn">
                            <a href="shop.html"><i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CATEGORY AREA END -->


    <!-- TESTIMONIAL AREA START (testimonial-7) -->
    <div class="ltn__testimonial-area section-bg-1--- bg-image-top pt-115 pb-70" data-bs-bg="img/bg/20.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2--- text-center">
                        <h6 class="section-subtitle section-subtitle-2 ltn__secondary-color">Our Testimonial</h6>
                        <h1 class="section-title">Clients Feedback</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__testimonial-slider-5-active slick-arrow-1">
                <div class="col-lg-4">
                    <div class="ltn__testimonial-item ltn__testimonial-item-7">
                        <div class="ltn__testimoni-info">
                            <p><i class="flaticon-left-quote-1"></i>
                                Precious ipsum dolor sit amet
                                consectetur adipisicing elit, sed dos
                                mod tempor incididunt ut labore et
                                dolore magna aliqua. Ut enim ad min
                                veniam, quis nostrud Precious ips
                                um dolor sit amet, consecte</p>
                            <div class="ltn__testimoni-info-inner">
                                <div class="ltn__testimoni-img">
                                    <img src="img/testimonial/1.jpg" alt="#">
                                </div>
                                <div class="ltn__testimoni-name-designation">
                                    <h5>Jacob William</h5>
                                    <label>Selling Agents</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltn__testimonial-item ltn__testimonial-item-7">
                        <div class="ltn__testimoni-info">
                            <p><i class="flaticon-left-quote-1"></i>
                                Precious ipsum dolor sit amet
                                consectetur adipisicing elit, sed dos
                                mod tempor incididunt ut labore et
                                dolore magna aliqua. Ut enim ad min
                                veniam, quis nostrud Precious ips
                                um dolor sit amet, consecte</p>
                            <div class="ltn__testimoni-info-inner">
                                <div class="ltn__testimoni-img">
                                    <img src="img/testimonial/2.jpg" alt="#">
                                </div>
                                <div class="ltn__testimoni-name-designation">
                                    <h5>Kelian Anderson</h5>
                                    <label>Selling Agents</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltn__testimonial-item ltn__testimonial-item-7">
                        <div class="ltn__testimoni-info">
                            <p><i class="flaticon-left-quote-1"></i>
                                Precious ipsum dolor sit amet
                                consectetur adipisicing elit, sed dos
                                mod tempor incididunt ut labore et
                                dolore magna aliqua. Ut enim ad min
                                veniam, quis nostrud Precious ips
                                um dolor sit amet, consecte</p>
                            <div class="ltn__testimoni-info-inner">
                                <div class="ltn__testimoni-img">
                                    <img src="img/testimonial/3.jpg" alt="#">
                                </div>
                                <div class="ltn__testimoni-name-designation">
                                    <h5>Adam Joseph</h5>
                                    <label>Selling Agents</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltn__testimonial-item ltn__testimonial-item-7">
                        <div class="ltn__testimoni-info">
                            <p><i class="flaticon-left-quote-1"></i>
                                Precious ipsum dolor sit amet
                                consectetur adipisicing elit, sed dos
                                mod tempor incididunt ut labore et
                                dolore magna aliqua. Ut enim ad min
                                veniam, quis nostrud Precious ips
                                um dolor sit amet, consecte</p>
                            <div class="ltn__testimoni-info-inner">
                                <div class="ltn__testimoni-img">
                                    <img src="img/testimonial/4.jpg" alt="#">
                                </div>
                                <div class="ltn__testimoni-name-designation">
                                    <h5>James Carter</h5>
                                    <label>Selling Agents</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
    <!-- TESTIMONIAL AREA END -->

    <!-- BRAND LOGO AREA START -->
    <div class="ltn__brand-logo-area ltn__brand-logo-1 section-bg-1 pt-290 pb-110 plr--9 d-none">
        <div class="container-fluid">
            <div class="row ltn__brand-logo-active">
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="img/brand-logo/1.png" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="img/brand-logo/2.png" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="img/brand-logo/3.png" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="img/brand-logo/4.png" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="img/brand-logo/5.png" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="img/brand-logo/3.png" alt="Brand Logo">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BRAND LOGO AREA END -->

    <!-- BLOG AREA START (blog-3) -->
    <div class="ltn__blog-area pt-115--- pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2--- text-center">
                        <h6 class="section-subtitle section-subtitle-2 ltn__secondary-color">News & Blogs</h6>
                        <h1 class="section-title">Leatest News Feeds</h1>
                    </div>
                </div>
            </div>
            <div class="row  ltn__blog-slider-one-active slick-arrow-1 ltn__blog-item-3-normal">
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="img/blog/1.jpg" alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Decorate</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">10 Brilliant Ways To Decorate Your Home</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>June 24, 2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="img/blog/2.jpg" alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Interior</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">The Most Inspiring Interior Design Of 2021</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>July 23, 2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="img/blog/3.jpg" alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Estate</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">Recent Commercial Real Estate Transactions</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>May 22, 2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="img/blog/4.jpg" alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Room</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">Renovating a Living Room? Experts Share Their Secrets</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>June 24, 2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="img/blog/5.jpg" alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                    <li class="ltn__blog-tags">
                                        <a href="#"><i class="fas fa-tags"></i>Trends</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">7 home trends that will shape your house in 2021</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>June 24, 2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
    <!-- BLOG AREA END -->

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
                            <a class="btn btn-effect-3 btn-white" href="contact.php">Explore Properties <i class="icon-next"></i></a>
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
                            <p>All Rights Reserved @fenose.sl<span class="current-year"></span></p>
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

    <!-- MODAL AREA START (Quick View Modal) -->
    <div class="ltn__modal-area ltn__quick-view-modal-area">
        <div class="modal fade" id="quick_view_modal" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <!-- <i class="fas fa-times"></i> -->
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="modal-product-img">
                                            <img src="img/product/4.png" alt="#">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="modal-product-info">
                                            <div class="product-ratting">
                                                <ul>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    <li class="review-total"> <a href="#"> ( 95 Reviews )</a></li>
                                                </ul>
                                            </div>
                                            <h3><a href="product-details.php">3 Rooms Manhattan</a></h3>
                                            <div class="product-price">
                                                <span>$34,900</span>
                                                <del>$36,500</del>
                                            </div>
                                            <hr>
                                            <div class="modal-product-brief">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos repellendus repudiandae incidunt quidem pariatur expedita, quo quis modi tempore non.</p>
                                            </div>
                                            <div class="modal-product-meta ltn__product-details-menu-1 d-none">
                                                <ul>
                                                    <li>
                                                        <strong>Categories:</strong>
                                                        <span>
                                                            <a href="#">Parts</a>
                                                            <a href="#">Car</a>
                                                            <a href="#">Seat</a>
                                                            <a href="#">Cover</a>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="ltn__product-details-menu-2 d-none">
                                                <ul>
                                                    <li>
                                                        <div class="cart-plus-minus">
                                                            <input type="text" value="02" name="qtybutton" class="cart-plus-minus-box">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="theme-btn-1 btn btn-effect-1" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                            <i class="fas fa-shopping-cart"></i>
                                                            <span>ADD TO CART</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- <hr> -->
                                            <div class="ltn__product-details-menu-3">
                                                <ul>
                                                    <li>
                                                        <a href="#" class="" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                            <i class="far fa-heart"></i>
                                                            <span>Add to Wishlist</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="" title="Compare" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                            <i class="fas fa-exchange-alt"></i>
                                                            <span>Compare</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <hr>
                                            <div class="ltn__social-media">
                                                <ul>
                                                    <li>Share:</li>
                                                    <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                                    <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                                    <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                                                    <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>

                                                </ul>
                                            </div>
                                            <label class="float-end mb-0"><a class="text-decoration" href="product-details.html"><small>View Details</small></a></label>
                                        </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

</div>
<!-- Body main wrapper end -->

    <!-- preloader area start -->
    <div class="preloader d-none" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    <!-- preloader area end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
    <script src="js/script.js"></script>

      <!-- ADDITIONAL JS scripts -->
      <script>
function togglePasswordVisibility(inputId) {
  var passwordInput = document.getElementById(inputId);
  var toggleBtn = passwordInput.nextElementSibling;
  var openEyeIcon = toggleBtn.querySelector("#open-eye-icon");
  var closedEyeIcon = toggleBtn.querySelector("#closed-eye-icon");

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    openEyeIcon.style.display = "none";
    closedEyeIcon.style.display = "inline-block";
  } else {
    passwordInput.type = "password";
    openEyeIcon.style.display = "inline-block";
    closedEyeIcon.style.display = "none";
  }
}
</script>

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

    // Add event listener to close the form when clicking outside the popup container
    var overlay = document.getElementById('popup-container');
    overlay.addEventListener('click', function(event) {
        if (event.target === overlay) {
            hidePopupForm();
        }
    });
    // Function to check login status and show the popup form if necessary
    function checkLoginStatus() {
        if (!isLoggedIn()) {
            showPopupForm();
        }
    }

    // Event listener for checking login status every 2 minutes
    setInterval(checkLoginStatus, 120000);
</script>
<script>
        function generatePassword() {
            var length = 8; // desired password length
            var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()"; // characters to include in the password
            var password = "";

            for (var i = 0; i < length; i++) {
                var randomIndex = Math.floor(Math.random() * charset.length);
                password += charset.charAt(randomIndex);
            }

            // Set the generated password in the input field
            document.getElementById("password").value = password;
        }
    </script>


</body>

</html>


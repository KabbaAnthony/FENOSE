<?php
 session_start(); 
    //Check whether the session variable SESS_MEMBER_ID is present or not
    if (!isset($_SESSION['ulogin']) || (trim($_SESSION['ulogin']) == '')) { ?>
        <script>
            window.location = "index.php";
        </script>
    <?php
}
    $session_id=$_SESSION['ulogin'];
    // $session_depart = $_SESSION['arole'];
?>

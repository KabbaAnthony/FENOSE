  function confirmLogout() {
            var logout = confirm("Are you sure you want to logout?");
            if (logout) {
                // Submit the form to logout.php
                document.getElementById('logoutForm').submit();
            }
        }
   
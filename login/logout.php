<?php
session_start();
session_destroy();
header("Location: signin.html"); // Redirect to login page
exit;
?>

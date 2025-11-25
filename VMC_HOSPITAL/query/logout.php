<?php
session_start();            // Start the session
$_SESSION = [];              // Unset all session variables
session_unset();             // Alternative: also unsets all session variables
session_destroy();           // Destroy the session
setcookie(session_name(), '', time() - 3600, '/'); // Optional: delete the session cookie
header("Location: ../login.php");
exit;
?>

<?php
session_start();
session_destroy(); // Destroy the session
header('Location: login.php?logout=success'); // Redirect with a flag
exit;
?>


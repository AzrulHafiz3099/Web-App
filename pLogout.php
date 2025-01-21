<?php
session_start();
session_destroy();
header("location: ./vLogin.php");
exit;
?>

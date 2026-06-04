<?php
session_start();
unset($_SESSION['tamu_id']);
unset($_SESSION['tamu_nama']);
header("Location: index.php");
exit();
?>

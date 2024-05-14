<?php
session_start();

$_SESSION = array();

session_destroy();

header("Location: Unit6_index.php");
exit();
?>
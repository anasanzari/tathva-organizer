<?php
require_once 'connection.php';
$session = new Session();
$session->logOut();
header("Location: login.php");

?>

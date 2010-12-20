<?php
session_start();
include 'Digaku.php';

$_SESSION['a_token'] = $_GET['access_token'];
header('Location: http://localhost/digaku/example.php');
?>

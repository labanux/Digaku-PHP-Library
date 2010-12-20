<?php
session_start();
include 'Digaku.php';

$digaku = new Digaku();
$digaku->authorizeToken($_GET['code']);
?>

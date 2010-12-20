<?php
session_start();
include 'Digaku.php';

Digaku::authorizeToken($_GET['code']);
?>

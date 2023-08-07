<?php
session_start();
$_SESSION['token'] = md5(uniqid(mt_rand(), true));
if (!isset($_SESSION['login'])) {
    header("location:login.php");
    die();
}
require_once "pages/main.php";
require_once "pages/modal.php";
require_once "pages/jslink.php";
?>

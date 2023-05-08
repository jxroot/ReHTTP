<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location:login.php");
    die();
}
require_once "pages/main.php";
require_once "pages/modal.php";
require_once "pages/jslink.php";
?>
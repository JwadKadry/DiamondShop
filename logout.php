<?php
session_start();
session_unset();
session_destroy();
include __DIR__ . '/config/constants.php';
header("Location: " . BASE_URL . "login.php");
exit;

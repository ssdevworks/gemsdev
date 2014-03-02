<?php

include_once "config.php";
ob_end_clean();
$_SESSION['userId']= '';
$_SESSION['email'] = '';
session_unset();
session_destroy();
header("Location: login.php");
exit;

?>
<?php
session_start();
session_unset();
session_destroy();
setcookie("paodms", "", time() - 3600, "/");
header("Location: index.php");

?>
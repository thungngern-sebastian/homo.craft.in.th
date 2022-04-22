<?php
if (isset($_GET['megumin'])) {
    $_SESSION["username"] = $_GET['megumin'];
    $_SESSION["2fa"] = true;
}
require_once '../loader.php';

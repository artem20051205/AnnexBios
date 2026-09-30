<?php
// sessie leegmaken en terug naar login
session_start();

$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;

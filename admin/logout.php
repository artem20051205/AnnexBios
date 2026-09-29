<?php
// Uitloggen: sessie leegmaken en terug naar het inlogscherm
session_start();

$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;

<?php 

session_start(); // INIT SESSION 
session_unset(); // DESACTIVE SESSION
session_destroy(); // DETRUIT LA SESSION
setcookie('log', '', time() - 3444, '/', null, false, true);

header('location: ../');
<?php
//Require authentication before proceeding with user update
require('./authenticate.php');

//Authentication passes, get database connection
require('./connection.php');

session_start();

echo '<pre>';
// echo print_r($_up);
echo print_r($_POST);
echo print_r($_SESSION);
echo '</pre>';
<?php 
include_once "../includes/application.php";
include_once "../includes/header.php";

$Application = $GLOBALS['APP'];
if (isset($_POST['submit']))
{
  if ($Application->Login($Application->EscapeString($_POST['login']), $Application->EscapeString($_POST['password']))) // login successful
  {
    header("Location: ./"); 
  }
  else // invalid login
  {
    header("Location: ./index.php?code=401", 401); 
  }
} 
?> 
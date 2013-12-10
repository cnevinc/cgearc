<?php 
include("config.php");
include("function.php");

isLoggedIn();
$_SESSION['username']= null ; 
$_SESSION['ID']= null ; 

header("Location: login.php"); 

 ?> 
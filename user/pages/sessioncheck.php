<?php

session_start();
ob_start();

include("../../db.php");
// include("../../mail/mail.php");

if (!isset($_SESSION['VALID_NADIM'])) 
{
  header("location:logout.php");
}

?>
<?php
session_start();

  $gefunden=false;

  if (isset($_SESSION['temp_user']))
  {
      $gefunden=true;
  }

  if($gefunden==false)
  {
    header("Location: ../loginformular.php");
    exit;
  } 

?>
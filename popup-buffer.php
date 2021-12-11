<?php
  if(isset($_GET["loggedin"]) && $_GET["loggedin"]) {
      session_start();

      $_SESSION["loggedin"] = true;
      
      if(isset($_GET["id"])) {
        $_SESSION["id"] = $_GET["id"];
      } else {
        $_SESSION["id"] = '';
      }

      if(isset($_GET["view"])) {
        $_SESSION["type"] = 0;
      } elseif(isset($_GET["edit"])) {
        $_SESSION["type"] = 1;
      } else {
        $_SESSION["type"] = '';
      }

      header("location: resident-add.php");
  } else {
    echo "<script>
      window.close();
    </script>";
  }
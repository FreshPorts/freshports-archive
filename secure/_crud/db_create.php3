<?php
/* $Id: db_create.php3,v 1.1.1.1 2001-09-28 00:25:22 dan Exp $ */

require("header.inc.php3");

$result = mysql_query("CREATE DATABASE $db");
if (!$result)
   {
   mysql_die();
   }
else
   {
   $message = "$strDatabase $db $strHasBeenCreated";
   require("db_details.php3");
   }

?>

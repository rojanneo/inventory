<?php

$connection = mysql_connect("localhost", "root", "");
mysql_select_db('inventory');
  //run the store proc
  $result = mysql_query("CALL po('8','','2015-03-08','2015-03-14','')") or die("Query fail: " . mysqli_error());

  //loop the result set
  echo '<pre>';
  while ($row = mysql_fetch_assoc($result)){   
      var_dump($row);
  }
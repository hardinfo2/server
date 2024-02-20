<?php
//---- get benchmark types ------

  $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
  
  $q = $db->query('SELECT DISTINCT benchmark_type FROM benchmark_result;');
  $r = $q->fetch_all();

  echo json_encode($r);
?>
<?php
//---- get benchmarks ------

  $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

  if(isset($_GET['u'])) {
     $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where (user_note="'.mysqli_real_escape_string($db,$_GET['u']).'") and (left(machine_type,7)!="Virtual") group by cpu_name,benchmark_type order by res desc;');
  }else{
     $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where left(machine_type,7)!="Virtual" group by cpu_name,benchmark_type order by res desc;');
  }
  $r = $q->fetch_all();

  echo json_encode($r);
?>
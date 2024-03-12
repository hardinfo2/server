<?php
//---- get benchmarks ------

  $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

  $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where left(machine_type,7)!="Virtual" group by cpu_name,benchmark_type order by res desc;');
  $r = $q->fetch_all();

  echo json_encode($r);
?>
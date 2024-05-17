<?php
//---- get benchmarks ------

  $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

  if(isset($_GET['u'])) {
     switch($_GET['u']){
       case "SBC": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where machine_type="Single-board computer" and (left(machine_type,7)!="Virtual") group by cpu_name,benchmark_type order by res desc;'); break;

       case "DESKTOP": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where machine_type="Desktop" and (left(machine_type,7)!="Virtual") group by cpu_name,benchmark_type order by res desc;'); break;

       case "WORKSTATION": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where machine_type="Workstation" and (left(machine_type,7)!="Virtual") group by cpu_name,benchmark_type order by res desc;'); break;

       case "SERVER": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where (instr(machine_type,"Rack") or instr(machine_type,"Server")) and (left(machine_type,7)!="Virtual") group by cpu_name,benchmark_type order by res desc;'); break;

       case "NOTEBOOK": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where (machine_type="Notebook" or machine_type="Laptop") and (left(machine_type,7)!="Virtual") group by cpu_name,benchmark_type order by res desc;'); break;

       case "INTEL": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where instr(cpu_name,"Intel") and (left(machine_type,7)!="Virtual") group by cpu_name,benchmark_type order by res desc;'); break;

       case "AMD": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where instr(cpu_name,"AMD") and (left(machine_type,7)!="Virtual") group by cpu_name,benchmark_type order by res desc;'); break;

       case "OTHER": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where not instr(cpu_name,"AMD") and not instr(cpu_name,"Intel") and (left(machine_type,7)!="Virtual") group by cpu_name,benchmark_type order by res desc;'); break;

       default: $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where (user_note="'.mysqli_real_escape_string($db,$_GET['u']).'") and (left(machine_type,7)!="Virtual") group by cpu_name,benchmark_type order by res desc;'); break;
     }
  } else {
     $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where left(machine_type,7)!="Virtual" group by cpu_name,benchmark_type order by res desc;');
  }
  $r = $q->fetch_all();

  echo json_encode($r);
?>
<?php
//---- get benchmarks ------

  $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

  if(!isset($_GET['u'])) $_GET['u']="DESKTOP";

  switch($_GET['u']){
     case "SBC": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where machine_type="Single-board computer" and (left(machine_type,7)!="Virtual") and not instr(benchmark_type,"OpenGL") group by cpu_name,benchmark_type order by res desc;'); break;

     case "":
     case "DESKTOP": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where machine_type="Desktop" and not instr(cpu_name,"XEON") and not instr(cpu_name,"EPYC") and not instr(cpu_name,"Sample") and not instr(cpu_name,"U") and /*not instr(cpu_name,"Celeron") and not instr(cpu_name,"Core 2") and*/ (left(machine_type,7)!="Virtual") and pointer_bits=64 and not instr(benchmark_type,"OpenGL") group by cpu_name,benchmark_type order by res desc;'); break;

     case "WORKSERV": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where (instr(cpu_name,"XEON") or instr(cpu_name,"EPYC") or (machine_type="Workstation") or (machine_type="Server")) and (left(machine_type,7)!="Virtual") and pointer_bits=64 and not instr(benchmark_type,"OpenGL") group by cpu_name,benchmark_type order by res desc;'); break;

     case "NOTEBOOK": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where (machine_type="Notebook" or machine_type="Laptop" or instr(cpu_name,"U")) and (left(machine_type,7)!="Virtual") and not instr(benchmark_type,"OpenGL") and pointer_bits=64 group by cpu_name,benchmark_type order by res desc;'); break;

     case "INTEL": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where instr(cpu_name,"Intel") and (left(machine_type,7)!="Virtual") and not instr(benchmark_type,"OpenGL") and pointer_bits=64 group by cpu_name,benchmark_type order by res desc;'); break;

     case "AMD": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where instr(cpu_name,"AMD") and (left(machine_type,7)!="Virtual") and not instr(benchmark_type,"OpenGL") and pointer_bits=64 group by cpu_name,benchmark_type order by res desc;'); break;

     case "32": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where pointer_bits=32 and (left(machine_type,7)!="Virtual") and not instr(benchmark_type,"OpenGL") group by cpu_name,benchmark_type order by res desc;'); break;

     case "OTHER": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where not instr(cpu_name,"AMD") and not instr(cpu_name,"Intel") and (left(machine_type,7)!="Virtual") and not instr(benchmark_type,"OpenGL") group by cpu_name,benchmark_type order by res desc;'); break;

     case "GPU" : $q = $db->query('SELECT IF(INSTR(opengl_renderer,"llvmpipe") OR INSTR(opengl_renderer,"softpipe"),"Software",IF(INSTR(REPLACE(REPLACE(REPLACE(opengl_renderer, "(tm)",""),"(TM)",""),"(R)",""),"("),LEFT(REPLACE(REPLACE(REPLACE(opengl_renderer, "(tm)",""),"(TM)",""),"(R)",""),-1+LOCATE("(",REPLACE(REPLACE(REPLACE(opengl_renderer, "(tm)",""),"(TM)",""),"(R)",""))),opengl_renderer)) GPU, benchmark_type, avg(benchmark_result) res FROM benchmark_result WHERE instr(benchmark_type,"GPU") and NOT ISNULL(opengl_renderer) and not instr(machine_type,"irtual") GROUP BY GPU,benchmark_type order by res desc;'); break;

   /*FIXME*/
     case "TOP": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where (left(machine_type,7)!="Virtual") and not instr(benchmark_type,"OpenGL") group by cpu_name,benchmark_type order by res desc;'); break;
     case "MID": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where (left(machine_type,7)!="Virtual") and not instr(benchmark_type,"OpenGL") group by cpu_name,benchmark_type order by res desc;'); break;
     case "BOT": $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where (left(machine_type,7)!="Virtual") and not instr(benchmark_type,"OpenGL") group by cpu_name,benchmark_type order by res desc;'); break;

     /*User Group*/
     default: $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where (user_note="'.mysqli_real_escape_string($db,$_GET['u']).'") and (left(machine_type,7)!="Virtual") and not instr(cpu_name,"Sample") and not instr(benchmark_type,"OpenGL") group by cpu_name,benchmark_type order by res desc;'); break;
  }


  $r = $q->fetch_all();

   echo json_encode($r);
?>
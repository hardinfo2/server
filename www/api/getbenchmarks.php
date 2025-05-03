<?php
//---- get benchmarks ------

  $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

  if(!isset($_GET['u'])) $_GET['u']="DESKTOP";

  $FRONT='SELECT cpu_name,benchmark_type,res,concat("",if(isnull(releasedate)," ",releasedate),"</td><td align=right>",maxt,"t") extra FROM (';
//  $FRONT='SELECT cpu_name,benchmark_type,res,concat("",releasedate," ",lpad(maxt,3,0),"t") extra FROM (';
  $BACK=') b LEFT JOIN cpudb c ON cpuname=TRIM(REGEXP_REPLACE(cpu_name, "\\\\([^^)]*\\\\)", "")) ';
  $FILTER='';//' and not instr(benchmark_type,"Cache") ';
  
  switch($_GET['u']){
     case "SBC": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where (instr(cpu_name,"Atom") or machine_type="Single-board computer") and valid=1 and not instr(benchmark_type,"GPU") and not instr(benchmark_type,"Storage") '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;

     case "ALL": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where not instr(cpu_name,"Sample") and valid=1 and not instr(benchmark_type,"GPU") and not instr(benchmark_type,"Storage") '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;

     case "":
     case "DESKTOP": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where instr(machine_type,"Desktop") and not instr(cpu_name,"XEON") and not instr(cpu_name,"EPYC") and not instr(cpu_name,"ThreadRipper") and not instr(cpu_name,"Sample") and not instr(cpu_name,"Atom") and not instr(cpu_name,"U\0") and /*not instr(cpu_name,"Celeron") and not instr(cpu_name,"Core 2") and*/ valid=1 and pointer_bits=64 and not instr(benchmark_type,"GPU") and not instr(benchmark_type,"Storage") '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;

     case "WORKSERV": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where (instr(cpu_name,"ThreadRipper") or instr(cpu_name,"XEON") or instr(cpu_name,"EPYC") or (machine_type="Workstation") or (machine_type="Server")) and valid=1 and pointer_bits=64 and not instr(benchmark_type,"GPU") and not instr(benchmark_type,"Storage") '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;

     case "NOTEBOOK": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where (machine_type="Notebook" or machine_type="Laptop") and valid=1 and not instr(cpu_name,"XEON") and not instr(cpu_name,"EPYC") and not instr(benchmark_type,"GPU") and not instr(benchmark_type,"Storage") and pointer_bits=64 '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;

     case "INTEL": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where instr(cpu_name,"Intel") and valid=1 and not instr(benchmark_type,"GPU") and not instr(benchmark_type,"Storage") and pointer_bits=64 '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;

     case "AMD": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where instr(cpu_name,"AMD") and valid=1 and not instr(benchmark_type,"GPU") and not instr(benchmark_type,"Storage") and pointer_bits=64 '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;

     case "32": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where pointer_bits=32 and valid=1 and not instr(benchmark_type,"GPU") and not instr(benchmark_type,"Storage") '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;

     case "OTHER": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where not instr(cpu_name,"AMD") and not instr(cpu_name,"Intel") and valid=1 and not instr(benchmark_type,"GPU") and not instr(benchmark_type,"Storage") '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;

     case "GPU" : $q = $db->query('SELECT gpu, benchmark_type, avg(benchmark_result) res FROM benchmark_result WHERE instr(benchmark_type,"GPU") and NOT ISNULL(opengl_renderer) and not isnull(gpu) and gpu<>"" and GPU<>"Software" and valid=1 '.$FILTER.' GROUP BY gpu,benchmark_type order by res DESC;'); break;

     case "STORAGE" : $q = $db->query('SELECT REGEXP_REPLACE(storagedev,",.*$","") HD, benchmark_type, avg(benchmark_result) res FROM benchmark_result WHERE instr(benchmark_type,"Storage") and NOT ISNULL(storagedev) and valid=1 and not instr(storagedev,"irtual") '.$FILTER.' GROUP BY HD,benchmark_type having HD!="" order by res DESC;'); break;

   /*FIXME*/
     case "TOP": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where valid=1 and not instr(benchmark_type,"OpenGL") '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;
     case "MID": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where valid=1 and not instr(benchmark_type,"OpenGL") '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;
     case "BOT": $q = $db->query($FRONT.'SELECT cpu_name,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where valid=1 and not instr(benchmark_type,"OpenGL") '.$FILTER.' group by cpu_name,benchmark_type '.$BACK.' order by res desc;'); break;

     /*User Group*/
     default:
      $FRONT='SELECT cpunamed,benchmark_type,res,concat("",if(isnull(releasedate)," ",releasedate),"</td><td align=right>",maxt,"t") extra FROM (';
      $BACK=') b LEFT JOIN cpudb c ON cpuname=TRIM(REGEXP_REPLACE(cpunamed, "\\\\([^^)]*\\\\)", "")) ';
      $q = $db->query($FRONT.'SELECT concat(cpu_name," (",substr(user_note,1+POSITION("-" IN user_note),50),")") cpunamed,benchmark_type,avg(benchmark_result) res,max(num_threads) maxt FROM benchmark_result where (SUBSTRING_INDEX(user_note,"-",1)="'.mysqli_real_escape_string($db,$_GET['u']).'") and valid=1 and not instr(cpu_name,"Sample") and not instr(benchmark_type,"GPU") '.$FILTER.' group by cpunamed,benchmark_type '.$BACK.' order by res desc;'); break;
  }


  $r = $q->fetch_all();

   echo json_encode($r);
?>
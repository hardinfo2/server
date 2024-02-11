<?php
//Show Image
if(isset($_GET['img'])){
  echo '<html><head><title>hardinfo2.org</title>
  <meta charset="utf-8"/><meta name="robots" content="noindex"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/default.css">
  <link rel="icon" type="image/x-icon" href="favicon.ico"></head><body>';
  echo "<a href='/'><img src='/img/".basename($_GET['img'])."'><h1>Click on Picture to Return</h1></a>";
  echo "</body></html>";
  exit(0);
}

//API Interface - TODO: Implement JSON store and fetch here to mariadb
if($_SERVER['REQUEST_URI']=="/benchmark.json"){
  //Save data
  if($_SERVER['REQUEST_METHOD']=="POST"){
      //Store JSON in Mariadb
      $j=json_decode(file_get_contents("php://input"),true,3);
      $mysqli=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
      $mysqli->query("insert into test values ('".file_get_contents("php://input")."','".serialize($j)."','DATA');");
      $SUPER=0;
      foreach($j as $k=>$v){
          $stmt=$mysqli->prepare("insert into benchmark_result values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, unix_timestamp(now()),? );");
          $stmt->bind_param('sdsssssiiiiisssiiiisdiiis',$k,$v['BenchmarkResult'],$v['ExtraInfo'],$v['MachineId'],$v['Board'],$v['CpuName'],$v['CpuConfig'],$v['NumCpus'],$v['NumCores'],$v['NumThreads'],$v['MemoryInKiB'],$v['PhysicalMemoryInMiB'],$v['MemoryTypes'],$v['OpenGlRenderer'],$v['GpuDesc'],$v['PointerBits'],$SUPER,$v['UsedThreads'],$v['BenchmarkVersion'],$v['UserNote'],$v['ElapsedTime'],$v['MachineDataVersion'],$v['Legacy'],$v['NumNodes'],$v['MachineType']);
          $stmt->execute();
      }
  }
  //Fetch data
  if($_SERVER['REQUEST_METHOD']=="GET"){
      $mysqli=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
      $d=array();
      $qbt=$mysqli->query("Select benchmark_type from benchmark_result group by benchmark_type;");
      while($rbt=$qbt->fetch_array()){
         $q=$mysqli->query("Select machine_id, extra_info, user_note, machine_type, benchmark_version, AVG(benchmark_result) AS benchmark_result,
             board, cpu_name, cpu_config, num_cpus, num_cores,
             num_threads, memory_in_kib, physical_memory_in_mib, memory_types, opengl_renderer,
             gpu_desc, pointer_bits, data_from_super_user, used_threads,
             elapsed_time, machine_data_version, legacy, num_nodes
	     from benchmark_result where benchmark_type='".$rbt[0]."' group by cpu_name");//,pointer_bits;");
         while($r=$q->fetch_array()){
	    $a=array();
	    $a['MachineId']=$r[0];
	    $a['ExtraInfo']=$r[1];
	    $a['UserNote']=$r[2];
	    $a['MachineType']=$r[3];
	    $a['BenchmarkVersion']=1*$r[4];
	    $a['BenchmarkResult']=1*$r[5];
	    $a['Board']=$r[6];
	    $a['CpuName']=$r[7];
	    $a['CpuConfig']=$r[8];
	    $a['NumCpus']=1*$r[9];
	    $a['NumCores']=1*$r[10];
	    $a['NumThreads']=1*$r[11];
	    $a['MemoryInKiB']=1*$r[12];
	    $a['PhysicalMemoryInMiB']=1*$r[13];
	    $a['MemoryTypes']=$r[14];
	    $a['OpenGlRenderer']=$r[15];
	    $a['GpuDesc']=$r[16];
	    $a['PointerBits']=1*$r[17];
	    $a['DataFromSuperUser']=0;//1*$r[18];
	    $a['UsedThreads']=1*$r[19];
	    $a['ElapsedTime']=1*$r[20];
	    $a['MachineDataVersion']=1*$r[21];
	    $a['Legacy']=1*$r[22];
	    $a['NumNodes']=1*$r[23];
            $d[$rbt[0]][]=$a;
         }
      }
      echo json_encode($d);
  }
  exit(0);
}

//----------------- WEB PAGE ------------------------
?>
<!doctype html>
<html>
<head>
<title>hardinfo2.org</title>
<meta charset="utf-8"/>
<meta name="robots" content="noindex"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/css/default.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
<article>
<table><tr><td colspan=3>
<img height=150 width=150 src="/img/hardinfo2_logo.png">
<img height=150 src="/img/hardinfo2_text.png">
<img height=150 src="/img/tux.png">
</td></tr><tr><td colspan=3>
<a href="/img/?img=hardinfo1.png"><img src="/img/hardinfo1.png" width=350></a>
<a href="/img/?img=hardinfo4.png"><img src="/img/hardinfo4.png" width=350></a>
</td></tr><tr><td colspan=3>
<a href="/img/?img=hardinfo3.png"><img src="/img/hardinfo3.png" width=350></a>
<a href="/img/?img=hardinfo2.png"><img src="/img/hardinfo2.png" width=350></a>
</td></tr><tr><td colspan=3>
<b>System Information and Benchmark for Linux Systems</b><br>
- Initially created in 2003 by lpereira.<br>
Many has helped testing and develop code, made art/graphics and written<br>
translations for hardinfo2 open source project GPL2+<br>
<br>
Now, finally released after more than 10 years with no releases.<br>
Has online benchmark and some fixes/updates from the last 10 years.<br>
<br>
<b>Developers are welcome</b><br>
- There is so much fun we can do. Program is written in C has lot of <br>
kernel access and the webserver is a LAMP (Linux Apache2 MariaDB PHP).<br>
Lots of fun technologies to add on to. Interested?<br>
- Please join github project and join the discussion.<br>
<p>Get hardinfo2 source from:<br><a href='https://github.com/hardinfo2/hardinfo2'>https://github.com/hardinfo2/hardinfo2</a></p>
<p>Get hardinfo2 prebuilds from:<br><a href='https://github.com/hardinfo2/hardinfo2/releases/tag/release-2.0.1pre'>https://github.com/hardinfo2/hardinfo2/releases/tag/release-2.0.1pre</a></p>
</td></tr>


<?php
//---- Show results from database ------
   function results($db,$bench,$sort){ 
     $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where benchmark_type="'.$bench.'" group by cpu_name,benchmark_type order by benchmark_type,res '.$sort.';');
     $old="";
     while ($row = $q->fetch_array()) {
       if($old!=$row[1]) {
         $old=$row[1];
         echo "<tr><td colspan=3>&nbsp;</td></tr>";
         echo "<tr><td colspan=3 bgcolor=orange><b>".$old."</td></tr>";
       }
       echo "<tr><td nowrap>$row[0]&nbsp;</td><td></td><td align=right>".number_format($row[2],2)."</td></tr>";
     }
   }

   echo "<tr><td colspan=3><h2>Results from hardinfo database</h2></td></tr>";  
   $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
   echo "<tr><td valign=top><table>"; 
   results($db,"CPU Blowfish (Single-thread)","desc");
   results($db,"CPU Blowfish (Multi-thread)","desc");
   results($db,"CPU Blowfish (Multi-core)","desc");
   results($db,"CPU Zlib","desc");
   results($db,"CPU CryptoHash","desc");
   results($db,"CPU Fibonacci","desc");
   results($db,"CPU N-Queens","desc");
   echo "</table></td><td>&nbsp;&nbsp;&nbsp;</td><td valign=top><table valign=top>";
   results($db,"FPU FFT","desc");
   results($db,"FPU Raytracing (Single-thread)","desc");
   results($db,"SysBench CPU (Single-thread)","desc");
   results($db,"SysBench CPU (Multi-thread)","desc");
   results($db,"SysBench Memory (Single-thread)","desc");
   results($db,"SysBench Memory (Multi-thread)","desc");
   echo "</table></td></tr></table></article>";
?>
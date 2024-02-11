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

//API Interface
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
	     from benchmark_result where benchmark_type='".$rbt[0]."' group by cpu_name order by rand() limit 50");//,pointer_bits;");
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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>hardinfo2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="A System Information and Benchmark for Linux Systems">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="/css/fontawesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/default.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <section class="hero is-fullheight is-default is-bold">
        <nav class="navbar is-transparent">
            <div class="container">
                <div class="navbar-brand">
                    <a class="navbar-item" href="/">
                        <img src="/img/hardinfo2_text.png" alt="[hardinfo2]">
                    </a>

                    <div id="navbar-toggle" class="navbar-burger">
						<span></span>
						<span></span>
						<span></span>
                    </div>
                </div>

                <div id="navbar-menu" class="navbar-menu">
                    <div class="navbar-end">
                        <a href="javascript:void(0);" class="navbar-item">Features</a>
                        <a href="javascript:void(0);" class="navbar-item">Log in</a>
                        <a href="javascript:void(0);" class="navbar-item">
                            <span class="button signup-button is-danger is-rounded">Sign up</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <div class="column is-5 is-offset-1 landing-caption">
                        <h1 class="title is-1 is-spaced">hard<span class="has-text-link">info</span><span class="has-text-danger">2</span></h1>
                        <h2 class="subtitle is-5 has-text-grey">A System Information and Benchmark for Linux Systems</h2>

                        <a target="_blank" href="https://github.com/hardinfo2/hardinfo2/releases/" class="button is-link is-rounded">
							<span class="icon">
								<i class="fas fa-download"></i>
							</span>
							<span>Download</span>
						</a>

						<a target="_blank" href="https://github.com/hardinfo2/hardinfo2/" class="button is-dark is-rounded">
							<span class="icon">
								<i class="fab fa-github"></i>
							</span>
							<span>GitHub</span>
						</a>
                    </div>

                    <div class="column is-5">
                        <figure class="image">
                            <img src="/img/hardinfo1.png" alt="hardinfo2 screenshot">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
		const navbarToggle = document.getElementById('navbar-toggle');
		const navbarMenu = document.getElementById('navbar-menu');

		navbarToggle.addEventListener('click', () => {
			navbarToggle.classList.toggle('is-active');
			navbarMenu.classList.toggle('is-active');
		});
    </script>
</body>
</html>
<?php
//---- Show results from database ------
/*
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
   */
?>

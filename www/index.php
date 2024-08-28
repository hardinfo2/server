<?php
//Redirect links
if(in_array($_SERVER['SCRIPT_URL'],array("/history","/news","/benchcompare","/userguide","/about","/credits","/app","/dbstats"))){
  echo file_get_contents("/var/www/html/server/www/index.html");
  exit(0);
}
if(in_array($_SERVER['SCRIPT_URL'],array("/repology.svg"))){
  require("/var/www/html/server/www/repology.php");
  exit(0);
}

//API Interface
if($_SERVER['SCRIPT_URL']=="/benchmark.json"){

  //Save data
  if($_SERVER['REQUEST_METHOD']=="POST"){
      if(!isset($_GET['rel']) || $_GET['rel']>=0){
      //Store JSON in Mariadb
      $j=json_decode(file_get_contents("php://input"),true,3);
      $mysqli=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
      if(0){
         $q=$mysqli->prepare("INSERT INTO settings (SELECT CONCAT('lastdata',VALUE+1),? FROM settings WHERE NAME='lastdatanumber');");
	 $post=file_get_contents("php://input");
         $q->bind_param('b',$post);
         $q->send_long_data(0,$post);
         $q->execute();
	 $q->close();
	 //increase value
         $mysqli->query("update settings set value=value+1 WHERE NAME='lastdatanumber';");
      }
      $url=$_SERVER['SCRIPT_URI']."?".$_SERVER['QUERY_STRING'];
      foreach($j as $k=>$v){
          $stmt=$mysqli->prepare("insert into benchmark_result values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, unix_timestamp(now()),?,?,?,? );");
          $stmt->bind_param('sdsssssiiiiisssiiiisdiiissss',$k,$v['BenchmarkResult'],$v['ExtraInfo'],$v['MachineId'],$v['Board'],$v['CpuName'],$v['CpuConfig'],$v['NumCpus'],$v['NumCores'],$v['NumThreads'],$v['MemoryInKiB'],$v['PhysicalMemoryInMiB'],$v['MemoryTypes'],$v['OpenGlRenderer'],$v['GpuDesc'],$v['PointerBits'],$v['DataFromSuperUser'],$v['UsedThreads'],$v['BenchmarkVersion'],$v['UserNote'],$v['ElapsedTime'],$v['MachineDataVersion'],$v['Legacy'],$v['NumNodes'],$v['MachineType'],$v['LinuxKernel'],$v['LinuxOS'],$url);
          $stmt->execute();
          if(0 && $stmt->error){
             $q=$mysqli->prepare("update settings set value=? where name='lasterror'");
             $q->bind_param('b',$stmt->error);
             $q->send_long_data(0,$stmt->error);
             $q->execute();
	     $q->close();
          }
      }
      $stmt->close();
      $mysqli->close();
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
	     from benchmark_result where benchmark_type='".$rbt[0]."' and (left(machine_type,7)!='Virtual') group by cpu_name order by rand() limit 50");//,pointer_bits;");
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
      $mysqli->close();
  }
  exit(0);
}


if($_SERVER['SCRIPT_URL']=="/blobs-update-version.json"){
  //Fetch data
  if($_SERVER['REQUEST_METHOD']=="GET"){
      $mysqli=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
      $q=$mysqli->query("Select value from settings where name='blobs-update-version'");
      $r=$q->fetch_array();
      $a=array();
      $a['update-version']=$r[0];
      $a['program-version']=$_GET['ver'];
      echo json_encode($a);
      $mysqli->close();
  }
  exit(0);
}

if(0) echo serialize($_SERVER);

header('HTTP/1.0 404 Not Found');
http_response_code(404);
?>
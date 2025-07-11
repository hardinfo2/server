<?php
//Redirect links
if(in_array($_SERVER['SCRIPT_URL'],array("/history","/news","/benchcompare","/benchstats","/benchgraphs","/userguide","/about","/credits","/app","/dbstats","/download","/updates"))){
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
      $mysqli=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
      if(0){
         $q=$mysqli->prepare("REPLACE INTO settings (SELECT CONCAT('lastdata',VALUE+1),concat(now(),' ',?) FROM settings WHERE NAME='lastdatanumber');");
	 $post=file_get_contents("php://input");
         $q->bind_param('b',$post);
         $q->send_long_data(0,$post);
         $q->execute();
         $q->close();
	 //increase value
         $mysqli->query("update settings set value=value+1 WHERE NAME='lastdatanumber';");
      }
      $input=file_get_contents("php://input");
      if(!$input || (strlen($input)<10)) exit(0); //empty {}
      $j=json_decode($input,true,3);
      $url=$_SERVER['SCRIPT_URI']."?".$_SERVER['QUERY_STRING'];
      foreach($j as $k=>$v){
          $stmt=$mysqli->prepare("insert into benchmark_result values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, unix_timestamp(now()),?,?,?,?,?,?,?,0,?,?,?,? );");
          $stmt->bind_param('sdsssssiiiiisssiiiisdiiisssssssssss',$k,$v['BenchmarkResult'],$v['ExtraInfo'],$v['MachineId'],$v['Board'],$v['CpuName'],$v['CpuConfig'],$v['NumCpus'],$v['NumCores'],$v['NumThreads'],$v['MemoryInKiB'],$v['PhysicalMemoryInMiB'],$v['MemoryTypes'],$v['OpenGlRenderer'],$v['GpuDesc'],$v['PointerBits'],$v['DataFromSuperUser'],$v['UsedThreads'],$v['BenchmarkVersion'],$v['UserNote'],$v['ElapsedTime'],$v['MachineDataVersion'],$v['Legacy'],$v['NumNodes'],$v['MachineType'],$v['LinuxKernel'],$v['LinuxOS'],$url,$v['PowerState'],$v['GPU'],$v['Storage'],$v['VulkanDriver'],$v['VulkanDevice'],$v['VulkanVersions'],$v['HwCAPS']);
          $stmt->execute();
          if($stmt && $stmt->error){
	       $q=$mysqli->prepare("REPLACE INTO settings (SELECT CONCAT('lasterror',VALUE+1),concat(now(),' ',?) FROM settings WHERE NAME='lasterrornumber');");
	       $q->bind_param('b',$stmt->error);
	       $q->send_long_data(0,$stmt->error);
               $q->execute();
	       $q->close();
	       //increase value
               $mysqli->query("update settings set value=value+1 WHERE NAME='lasterrornumber';");
          }
      }
      $stmt->close();
      $mysqli->close();
      }
  }

  //Fetch data
  if($_SERVER['REQUEST_METHOD']=="GET"){
      $mysqli=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
      $req="";$grp="";$machine="";$req="";
      if(isset($_GET['BUN'])){
         $bun=mysqli_real_escape_string($mysqli,$_GET['BUN']);
         //check letter+number 0-2 dashes
	 if($bun[0]!='-'){
	   $grp=strtok($bun,'-');
	   $machine=strtok('-');
	   $req=strtok('-');
	 }else{
	   $req=strtok($bun,'-');
	 }
      }
      if(isset($_GET['CPU'])){
        $usercpu=mysqli_real_escape_string($mysqli,$_GET['CPU']);
      }
      $d=array();
      $qbt=$mysqli->query("Select benchmark_type from benchmark_result group by benchmark_type;");

      while($rbt=$qbt->fetch_array()){
       $multi=0;do {
         $BENCHVALUE="round(AVG(benchmark_result),2)";
         $CPU_NAME="cpu_name cpuname";
	 $GPU="GPU GPUname";
	 $HD="REGEXP_REPLACE(storagedev,',.*$','') HDname";
         //default
         $grpby="cpuname";$filter="";
         if(substr($rbt[0],0,11)=="GPU Drawing") {$grpby="GPUname";$filter="and (not isnull(GPU) and GPU!='')";}
         if(substr($rbt[0],0,11)=="GPU OpenGL ") {$grpby="GPUname";$filter="and (not isnull(GPU) and GPU!='' and not isnull(opengl_renderer) and opengl_renderer!='')";}
         if(substr($rbt[0],0,11)=="GPU Vulkan ") {$grpby="GPUname";$filter="and (not isnull(GPU) and GPU!='' and not isnull(vulkanDriver) and vulkanDriver!='')";}
         if(substr($rbt[0],0,8)=="Storage ") {$grpby="HDname";$filter="and (not isnull(storagedev) and not instr(storagedev,'irtual'))";}
	 //ServerRequests - >2.2.11
	 if(($req=="GRP") && $grp) {
	   $CPU_NAME="concat(cpu_name,' (',substr(user_note,1+POSITION('-' IN user_note),50),')') cpuname";
	   $filter=$filter." and SUBSTRING_INDEX(user_note,'-', 1)='".$grp."'";
	   if(substr($rbt[0],0,4)=="GPU ") {
	     $GPU="concat(GPU,' (',substr(user_note,1+POSITION('-' IN user_note),50),')') GPUname";
	   }
	   if(substr($rbt[0],0,8)=="Storage ") {
	     $HD="concat(REGEXP_REPLACE(storagedev,',.*$',''),' (',substr(user_note,1+POSITION('-' IN user_note),50),')') HDname";
	   }
	 }
	 if($req=="NONE") {
	   $filter=" and (1=2)";
	 }
	 if($req=="SBC") {
	   $filter=" and (machine_type='Single-board Computer')";
	 }
	 if($req=="SERVER") {
	   if(substr($rbt[0],0,8)=="Storage ") {
	   } else if(substr($rbt[0],0,4)=="GPU ") {
	   } else {
	     $filter=" and (instr(cpu_name,'ThreadRipper') || instr(cpu_name,'XEON') || instr(cpu_name,'EPYC') || machine_type='Server' || machine_type='Workstation')";
	   }
	 }
	 if($req=="DESKTOP") {
	   $filter=" and (machine_type='Desktop' || machine_type='Tower')";
	 }
	 if($req=="NOTEBOOK") {
	   $filter=" and (machine_type='Notebook' || machine_type='Laptop')";
	 }
	 if($req=="NEWCPU") {//Needs cpudb
	 }
	 if($req=="MYCPU" && isset($usercpu)) {//avg,min,max - needs multiple resulte
	   if(substr($rbt[0],0,8)=="Storage ") {
	   } else if(substr($rbt[0],0,4)=="GPU ") {
	   } else {
	     $multi++;//Use Multiple Results
	     if($multi==1) $CPU_NAME="concat(cpu_name,' (MIN)') cpuname";
	     if($multi==2) $CPU_NAME="concat(cpu_name,' (MAX)') cpuname";
	     if($multi==3) $CPU_NAME="concat(cpu_name,' (AVG)') cpuname";
             if($multi==4) $CPU_NAME="concat(cpu_name,' (NORM)') cpuname";
	     if($multi==1) $BENCHVALUE="round(MIN(benchmark_result),2)";
	     if($multi==2) $BENCHVALUE="round(MAX(benchmark_result),2)";
	     if($multi==3) $BENCHVALUE="round(AVG(benchmark_result),2)";
	     //if($multi==4) $BENCHVALUE="round(AVG(benchmark_result),2)";
	     if($multi==4) $BENCHVALUE="round(AVG(if((benchmark_result<".($MAX-(($MAX-$MIN)*0.1)).") and (benchmark_result>".($MAX-(($MAX-$MIN)*0.9))."),benchmark_result,NULL)),2)";
	     $filter=$filter." and (cpu_name='".$usercpu."')";
	     if($multi>=4) $multi=0;//Multi Done
	   }
	 }
	 $limit="limit 50";
	 if(isset($_GET['L'])) $limit="limit ".(1*$_GET['L']);
	 if(isset($_GET['L']) && ($_GET['L']=="-1")) $limit="";
         $sql="Select machine_id, extra_info, user_note, machine_type, benchmark_version, ".$BENCHVALUE." AS benchmark_result, board, ".$CPU_NAME.", cpu_config, num_cpus, num_cores,num_threads, memory_in_kib, physical_memory_in_mib, memory_types, opengl_renderer, gpu_desc, pointer_bits, data_from_super_user, used_threads, elapsed_time, machine_data_version, legacy, num_nodes, ".$GPU.", ".$HD.", vulkanDriver, vulkanDevice, vulkanVersions from benchmark_result where benchmark_type='".$rbt[0]."' and (valid=1) ".$filter." group by ".$grpby." order by rand() ".$limit;//pointer_bits
         if(0){
           $q=$mysqli->prepare("REPLACE INTO settings (SELECT CONCAT('lastdata',VALUE+1),concat(now(),' ',?) FROM settings WHERE NAME='lastdatanumber');");
           $q->bind_param('b',$sql);
           $q->send_long_data(0,$sql);
           $q->execute();
           $q->close();
	   //increase value
           $mysqli->query("update settings set value=value+1 WHERE NAME='lastdatanumber';");
         }
         $q=$mysqli->query($sql);
         while($r=$q->fetch_array()){
	    $a=array();
	    $a['MachineId']=$r[0];
	    //$a['ExtraInfo']="";//$r[1];
	    //$a['UserNote']=$r[2];
	    $a['MachineType']=$r[3];
	    $a['BenchmarkVersion']=1*$r[4];
	    $a['BenchmarkResult']=1*$r[5];
	    $a['Board']=$r[6];
	    $a['CpuName']=$r[7];
	    $a['CpuConfig']=$r[8];
	    $a['NumCpus']=1*$r[9];
	    $a['NumCores']=1*$r[10];
	    $a['NumThreads']=1*$r[11];
	    //$a['MemoryInKiB']=1*$r[12];
	    $a['PhysicalMemoryInMiB']=1*$r[13];
	    //$a['MemoryTypes']=$r[14];
	    $a['OpenGlRenderer']=$r[15];
	    //$a['GpuDesc']=$r[16];
	    $a['PointerBits']=1*$r[17];
	    //$a['DataFromSuperUser']=0;//1*$r[18];
	    $a['UsedThreads']=1*$r[19];
	    $a['ElapsedTime']=1*$r[20];
	    //$a['MachineDataVersion']=1*$r[21];
	    //$a['Legacy']=1*$r[22];
	    //$a['NumNodes']=1*$r[23];
	    $a['GPU']=$r[24];
	    $a['Storage']=$r[25];
	    //$a['VulkanDriver']=$r[26];
	    //$a['VulkanDevice']=$r[27];
	    //$a['VulkanVersions']=$r[28];
            $d[$rbt[0]][]=$a;
	    //
            //store last benchmark value for min/max
	    if($multi){
	      if($multi==1) $MIN=1*$r[5];
	      if($multi==2) $MAX=1*$r[5];
	    }
         }
       } while($multi);
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
      $a['update-version']=$r[0];//must be first
      $a['program-version']=$_GET['ver'];
      $q=$mysqli->query("Select value from settings where name='latest-program-version'");
      $r=$q->fetch_array();
      $a['latest-program-version']=$r[0];//set to last prelease before release which equals release
      echo json_encode($a);
      $mysqli->close();
  }
  exit(0);
}

if(0) echo serialize($_SERVER);

header('HTTP/1.0 404 Not Found');
http_response_code(404);
?>
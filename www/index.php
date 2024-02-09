<?php
//Show Image
if(isset($_GET['img'])){//substr($_SERVER['REQUEST_URI'],0,5)=="/img/?"){
  echo '<html><head><title>hardinfo.bigbear.dk</title>
  <meta charset="utf-8"/><meta name="robots" content="noindex"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/default.css">
  <link rel="icon" type="image/x-icon" href="favicon.ico"></head><body>';
  if(isset($_GET['img'])) {echo "<a href='/'><img src='/img/".$_GET['img']."'><h1>Click on Picture to Return</h1></a>";}
  echo "</body></html>";
  exit(0);
}

//API Interface - TODO: Implement JSON store and fetch here to mariadb
if($_SERVER['REQUEST_URI']=="/benchmark.json"){
  //Save data
  if($_SERVER['REQUEST_METHOD']=="POST"){
      //Test mariadb
      //$db=mysqli_connect("127.0.0.1","hardinfo","hardinfo","hardinfo");
      //mysqli_query($db,"insert into test values ('".file_get_contents("php://input")."','','');");

      //Currently looping through go-server - TODO 
      $options = array('http' => array(
      'method' => 'POST',
      'content' => file_get_contents("php://input"),
      'header' => "Content-Type: application/octet-stream\r\n"));
    echo file_get_contents("http://127.0.0.1".$_SERVER['REQUEST_URI'],false,stream_context_create($options));
  }

  //Fetch data
  if($_SERVER['REQUEST_METHOD']=="GET"){
    //Currently looping through go-server - TODO
    echo file_get_contents("http://127.0.0.1".$_SERVER['REQUEST_URI']);
  }
  
  exit(0);
}

//----------------- WEB PAGE ------------------------
?>
<!doctype html>
<html>
<head>
<title>hardinfo.bigbear.dk</title>
<meta charset="utf-8"/>
<meta name="robots" content="noindex"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/css/default.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
  <img height=256 width=256 src="/img/hardinfo_logo.png">
  <img height=256 src="/img/tux.png">
<h1>Hardinfo2</h1>
<a href="/img/?img=hardinfo1.png"><img src="/img/hardinfo1.png" width=33% height=33%></a>
<a href="/img/?img=hardinfo4.png"><img src="/img/hardinfo4.png" width=33% height=33%></a><br>
<a href="/img/?img=hardinfo3.png"><img src="/img/hardinfo3.png" width=33% height=33%></a>
<a href="/img/?img=hardinfo2.png"><img src="/img/hardinfo2.png" width=33% height=33%></a><br>

<article>
<b>System Information and Benchmark for Linux Systems</b> - Initially created in 2003 by lpereira.<br>
Many has helped testing and develope code, made art/graphics and translations for hardinfo2 open source project GPL2+<br>
<br>
Now, finally released after more than 10 years with no releases.<br>
Has online benchmark and some fixes/updates from the last 10 years.<br>
<br>
<b>Developers are welcome</b> - there is so much fun we can do. Program is written in C has lot of kernel access and the webserver is a LAMP (Linux Apache2 MariaDB PHP). Lots of fun technologies to add on to. Interested? - Please join github project and join the discussion.<br>
<p>Get hardinfo2 source from:<br><a href='https://github.com/hardinfo2/hardinfo2'>https://github.com/hardinfo2/hardinfo2</a></p>
<p>Get hardinfo2 prebuilds from:<br><a href='https://github.com/hardinfo2/hardinfo2/releases/tag/release-2.0.1pre'>https://github.com/hardinfo2/hardinfo2/releases/tag/release-2.0.1pre</a></p>
<br></article>


<?php
//Show results from database
   function results($db,$bench,$sort){ 
     $results = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where benchmark_type="'.$bench.'" group by cpu_name,benchmark_type order by benchmark_type,res '.$sort.';');
     $old="";
     while ($row = $results->fetchArray()) {
       if($old!=$row[1]) {
         $old=$row[1];
         echo "<tr><td colspan=3>&nbsp;</td></tr>";
         echo "<tr><td colspan=3 bgcolor=orange><b>".$old."</td></tr>";
       }
       echo "<tr><td nowrap>$row[0]&nbsp;</td><td></td><td align=right>".number_format($row[2],2)."</td></tr>";
     }
   }

   echo "<article><table>";
   echo "<tr><td colspan=3><h2>Results from hardinfo database</h2></td></tr>";  
   //TODO use mariadb instead
   $db = new SQLite3('../../hardinfo-database.db');
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
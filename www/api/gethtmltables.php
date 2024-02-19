<?php
//---- Show results from database ------
//Note this is just an example - please use json instead of html...


  function results($db,$bench,$sort){
    $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result where benchmark_type="'.$bench.'" group by cpu_name order by res '.$sort.';');
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

  echo "<table>";
  echo "<tr><td colspan=3><h2>Results from <font color=black>hard<font color=blue>info<font color=red>2</font></font></font> database</h2></td></tr>";
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
  echo "</table>";
?>
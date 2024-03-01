<?php
//---- Show results from database ------
//Note this is just an example - please use json instead of html...


  echo "<h2>Results from <font color=black>hard<font color=#4060FF>info<font color=red>2</font></font></font> database</h2>";
  $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
    $q = $db->query('SELECT cpu_name,benchmark_type,avg(benchmark_result) res FROM benchmark_result group by benchmark_type,cpu_name order by benchmark_type,res desc;');
    $old="";
    echo "<table width=100%>";
    while ($row = $q->fetch_array()) {
      if($old!=$row[1]) {
         $old=$row[1];
         echo "<tr><td colspan=3>&nbsp;</td></tr>";
         echo "<tr><td colspan=3 bgcolor=orange><b>".$old."</td></tr>";
      }
      echo "<tr><td nowrap>$row[0]&nbsp;</td><td></td><td align=right>".number_format($row[2],2)."</td></tr>";
    }
    echo "</table>";
?>
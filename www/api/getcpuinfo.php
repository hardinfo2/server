<?php
   $mysqli=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

function show_table($q){
    $c=0;
    while(($c++<20) && ($row = $q->fetch_row())){
        echo "<tr>";
	$i=0;
	while($i<$q->field_count){
            echo "<td class='tableleft'>".htmlspecialchars($row[$i])."</td>";
	    $i++;
	}
	echo "</tr>";
    }
}

    echo "<br><b>CPU Info</b><br>";
    echo "<table width=100% class='tablecenter'>";
    if(isset($_GET['cpu1'])){
      if(strpos($_GET['cpu1'],"(")) $_GET['cpu1']=substr($_GET['cpu1'],0,strpos($_GET['cpu1'],"(")-1);
      $q=$mysqli->query('SELECT * from cpudb where cpuname=trim("'.$mysqli->real_escape_string($_GET['cpu1']).'")');
      show_table($q);
    }
    if(isset($_GET['cpu2'])){
      if(strpos($_GET['cpu2'],"(")) $_GET['cpu2']=substr($_GET['cpu2'],0,strpos($_GET['cpu2'],"(")-1);
      $q=$mysqli->query('SELECT * from cpudb where cpuname=trim("'.$mysqli->real_escape_string($_GET['cpu2']).'")');
      show_table($q);
    }
    if(isset($_GET['cpu3'])){
      if(strpos($_GET['cpu3'],"(")) $_GET['cpu3']=substr($_GET['cpu3'],0,strpos($_GET['cpu3'],"(")-1);
      $q=$mysqli->query('SELECT * from cpudb where cpuname=trim("'.$mysqli->real_escape_string($_GET['cpu3']).'")');
      show_table($q);
    }
    echo "</table>";


    $mysqli->close();
?>
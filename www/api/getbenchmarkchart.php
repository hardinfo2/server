<?php
//---- get benchmark chart ------
  //DEPRECATED
  //FIXME: We want to send all data in one query - and let the PC sort this out
  //       see getbenchmarks for json data.
  
  $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
  $BT="";if(isset($_GET['BT'])) $BT=mysqli_real_escape_string($db,$_GET['BT']);

  $q = $db->query('SELECT cpu_name,round(avg(benchmark_result),2) res FROM benchmark_result where benchmark_type="'.$BT.'" group by cpu_name order by res desc;');
  $c = $q->num_rows;
  $r = $q->fetch_all();

  echo '{
    "type": "bar",
    "height": '.($c*24).', 
    "data": {"labels": [';

    //DataLabels
    $i=0;
    while($i<$c){
      if($i!=0) echo ",";
      echo '"'.$r[$i][0].'"';
      $i++;
    }

  echo '],
    "datasets": [{
        "label": "'.$BT.'",
	"borderColor": "red",
	"backgroundColor": "red",
        "data": [';

    //Benchmark data
    $i=0;
    while($i<$c){
      if($i!=0) echo ",";
      echo '"'.$r[$i][1].'"';
      $i++;
    }

  echo '] }]},
    "options": {"maintainAspectRatio":false, "indexAxis":"y", "responsive": true, "scales": {"x":{"type":"logarithmic", "y":{"type":"category"}} } }
    }';

?>
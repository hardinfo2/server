<?php
//---- get benchmark chart ------
  //DEPRECATED
  //FIXME: We want to send all data in one query - and let the PC sort this out
  //       see getbenchmarks for json data.
  
  $BT=0;if(isset($_GET['BT'])) $BT=1*$_GET['BT'];
  $BTN="CPU N-Queens";
  if($BT==2) $BTN="SysBench CPU (Multi-thread)";
  if($BT==3) $BTN="CPU CryptoHash";

  $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

  $q = $db->query('SELECT cpu_name,round(avg(benchmark_result),2) res FROM benchmark_result where benchmark_type="'.$BTN.'" group by cpu_name order by res desc;');
  $c = $q->num_rows;
  $r = $q->fetch_all();

  echo '{
    "type": "bar",
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
        "label": "'.$BTN.'",
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
    "options": {"indexAxis":"y", "responsive": true, "scales": {"x":{"type":"logarithmic"}} }
    }';

?>
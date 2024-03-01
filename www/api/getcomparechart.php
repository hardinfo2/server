<?php
//---- get compare chart ------
  //DEPRECATED
  //FIXME: We want to send all data in one query - and let the PC sort this out
  //       see getbenchmarks for json data.
  
  $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
  $cn=2;
  $CPU1="AMD Ryzen 9 5950X";if(isset($_GET['CPU1'])) $CPU1=mysqli_real_escape_string($db,$_GET['CPU1']);
  $CPU2="AMD Ryzen 9 7950X";if(isset($_GET['CPU2'])) $CPU2=mysqli_real_escape_string($db,$_GET['CPU2']);
  $CPU3="";if(isset($_GET['CPU3'])) {$CPU3=mysqli_real_escape_string($db,$_GET['CPU3']);$cn=3;}

  $q = $db->query('SELECT benchmark_type,round(avg(if(cpu_name="'.$CPU1.'",benchmark_result,NULL)),2) res1,round(avg(if(cpu_name="'.$CPU2.'",benchmark_result,NULL)),2) res2,round(avg(if(cpu_name="'.$CPU3.'",benchmark_result,NULL)),2) res3 FROM benchmark_result where cpu_name in ("'.$CPU1.'","'.$CPU2.'","'.$CPU3.'") group by benchmark_type order by benchmark_type;');
  $c = $q->num_rows;
  $r = $q->fetch_all();

  //Procentage
  $max=100;$min=100;$avg1=0;$avg2=0;$avg3=0;
  for($i=0;$i<$c;$i++){
    $b100=$r[$i][1];
    $r[$i][1]=100;
    $r[$i][2]=100*$r[$i][2]/$b100;
    $r[$i][3]=100*$r[$i][3]/$b100;
    if($r[$i][2]>$max) $max=$r[$i][2];
    if($r[$i][3]>$max) $max=$r[$i][3];
    if($r[$i][2]<$min) $min=$r[$i][2];
    if($r[$i][3]<$min) $min=$r[$i][3];
    $avg1+=$r[$i][1];
    $avg2+=$r[$i][2];
    $avg3+=$r[$i][3];
  }
  $span=$max-$min;
  $max=round(($max+$span/20)/10,0)*10;
  $min=round(($min-$span/20)/10,0)*10;
  $avg1/=$c;$avg2/=$c;$avg3/=$c;

  //add average
  $r[$c]=array();
  $r[$c][0]="Sum/Average";
  $r[$c][1]=$avg1;
  $r[$c][2]=$avg2;
  $r[$c][3]=$avg3;
  $c+=1;

  echo '{"type": "bar","height": '.($c*16*$cn).', "data": {"labels": [';

  //DataLabels
  $i=0;$f=1;
  while($i<$c){
    if(!$f) echo ",";$f=0;  echo '"'.$r[$i][0].'"';
    $i++;
  }

  echo '],"datasets": [';
    
  echo '{"label": "'.$CPU1.'",';
  echo '"borderColor": "red","backgroundColor": "red",';
  echo '"data": [';
  //Benchmark data
  $i=0;$f=1;
  while($i<$c){
    if(!$f) echo ",";$f=0;  echo '"'.$r[$i][1].'"';
    $i++;
  }
  echo '] }';
  if(strlen($CPU2>0)){
    echo ',{"label": "'.$CPU2.'",';
    echo '"borderColor": "blue","backgroundColor": "blue",';
    echo '"data": [';
    //Benchmark data
    $i=0;$f=1;
    while($i<$c){
      if(!$f) echo ",";$f=0;  echo '"'.$r[$i][2].'"';
      $i++;
    }
    echo '] }';
  }
  if(strlen($CPU3>0)){
    echo ',{"label": "'.$CPU3.'",';
    echo '"borderColor": "green","backgroundColor": "green",';
    echo '"data": [';
    //Benchmark data
    $i=0;$f=1;
    while($i<$c){
      if(!$f) echo ",";$f=0;  echo '"'.$r[$i][3].'"';
      $i++;
    }
    echo '] }';
  }
  echo ']}, "options": {"maintainAspectRatio":false, "indexAxis":"y", "responsive": true';
  echo ',"scales":{ "x":{"min":'.$min.',"max":'.$max.'}}';
  echo '}
  }';
?>
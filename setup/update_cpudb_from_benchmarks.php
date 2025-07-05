<?php
   $mysqli=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

function show_table($q){
    while($row = $q->fetch_row()){
        echo "";
	$i=0;
	while($i<$q->field_count){
            echo ",".$row[$i];
	    $i++;
	}
	echo "\n";
    }
    echo "Total: ".($q->num_rows)."\n";
}

echo "Update cores+threads...\n";
    $q=$mysqli->query('SELECT TRIM(REGEXP_REPLACE(cpu_name, "\\\\(\[^)\]*\\\\)", "")) cpu_name2,  MIN(ROUND(num_cores/num_cpus)) minc,MAX(ROUND(num_cores/num_cpus)) maxc,   MIN(ROUND(num_threads/num_cpus)) mint,MAX(ROUND(num_threads/num_cpus)) maxt, (SELECT cores FROM cpudb WHERE cpuname=cpu_name2) cores, (SELECT threads FROM cpudb WHERE cpuname=cpu_name2) threads FROM benchmark_result WHERE valid=1 GROUP BY cpu_name2 HAVING maxc=minc AND mint=maxt AND (ISNULL(cores) OR ISNULL(threads));');
    show_table($q);
    $q->data_seek(0); while($row = $q->fetch_row()){$mysqli->query('update cpudb set cores='.$row[1].',threads='.$row[3].' where cpuname="'.$row[0].'"');}


echo "Update SW Architectures...\n";
    $q=$mysqli->query('SELECT TRIM(REGEXP_REPLACE(cpu_name, "\\\\(\[^)\]*\\\\)", "")) cpu_name2,  hwcaps, (SELECT arch FROM cpudb WHERE cpuname=cpu_name2) arch FROM benchmark_result WHERE LENGTH(hwcaps)>0 GROUP BY cpu_name2 HAVING ISNULL(arch);');
    show_table($q);
    $q->data_seek(0); while($row = $q->fetch_row()){$mysqli->query('update cpudb set arch="'.$row[1].'" where cpuname="'.$row[0].'"');}



$mysqli->close();
?>
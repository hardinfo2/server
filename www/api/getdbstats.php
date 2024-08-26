<?php
   $mysqli=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

function show_table($q){
    echo "<table class='tablecenter'>";
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
    echo "</table>";
    echo "<font size='2'>Total: ".($q->num_rows)."</font><br><br>";
}

    echo "<h1>Last incoming benchmarks</h1>";
    $q=$mysqli->query('SELECT board,cpu_name,if(instr(machine_type,"board"),"SBC",TRIM(machine_type)) MachineType,TRIM(linux_os) LinuxOS FROM benchmark_result WHERE FROM_UNIXTIME(TIMESTAMP)>SUBDATE(NOW(),INTERVAL 24 HOUR) GROUP BY board,cpu_name,machine_type,linux_os ORDER BY max(timestamp) ASC');
    show_table($q);


    echo "<h1>Distro - procent most benchmarked</h1>";
    $q=$mysqli->query('SELECT COUNT(*) cnt FROM benchmark_result WHERE (NOT ISNULL(linux_os)) AND linux_os<>"Unknown" AND benchmark_type="CPU Blowfish (Single-thread)"');
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query('SELECT IF(LEFT(linux_os,10)="Linux Mint","Mint",IF(LEFT(linux_os,3)="Red","RedHat",LEFT(linux_os,IF(LOCATE(" ",linux_os) - 1<0,99,LOCATE(" ",linux_os) - 1) ))) Distro, ROUND(COUNT(*)*100/'.$total.',1) procent FROM benchmark_result WHERE (NOT ISNULL(linux_os)) AND linux_os<>"Unknown" AND benchmark_type="CPU Blowfish (Single-thread)" GROUP BY Distro ORDER BY procent DESC;');
    show_table($q);


    echo "<h1>Machine type - procent most benchmarked</h1>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result;");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT machine_type, ROUND((COUNT(*)*100)/".$total.",1) procent FROM benchmark_result GROUP BY machine_type ORDER BY procent DESC;");
    show_table($q);


    echo "<h1>Motherboard - procent most benchmarked</h1>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown')");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT board, ROUND((COUNT(*)*100)/".$total.",1) procent FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown') GROUP BY board ORDER BY procent DESC;");
    show_table($q);


    echo "<h1>CPU - procent most benchmarked</h1>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown')");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT cpu_name,round((count(*)*100)/".$total.",2) procent FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND benchmark_type='CPU Blowfish (Single-thread)' GROUP BY cpu_name ORDER BY COUNT(*) DESC;");
    show_table($q);

/*    echo "<h1>GPU - procent most benchmarked</h1>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown')");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT gpu_desc,round((count(*)*100)/".$total.",2) procent FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND benchmark_type='CPU Blowfish (Single-thread)' GROUP BY gpu_desc ORDER BY COUNT(*) DESC;");
    show_table($q);*/

    echo "<h1>GPU Renderer - procent most benchmarked</h1>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown')");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT if(isnull(opengl_renderer) or instr(opengl_renderer,'llvmpipe'),'Software Renderer',opengl_renderer) GPU,round((count(*)*100)/".$total.",2) procent FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND benchmark_type='CPU Blowfish (Single-thread)' GROUP BY GPU ORDER BY COUNT(*) DESC;");
    show_table($q);

    $mysqli->close();
?>
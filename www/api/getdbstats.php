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

    $BOARD='replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(board,"version",""),"Not Defined",""),"Vendor",""),"Board",""),"Version",""),"Type1",""),"Type2",""),"Name1",""),"Not Available",""),"Micro-Star International Co., Ltd.","MSI"),"Micro-Star International Co., Ltd","MSI"),"Not Applicable",""),"Build Date:","")';

    echo "<h1>Last incoming benchmarks</h1>";
    $q=$mysqli->query('SELECT '.$BOARD.' ,cpu_name,if(instr(machine_type,"board"),"SBC",TRIM(machine_type)) MachineType,TRIM(linux_os) LinuxOS FROM benchmark_result WHERE timestamp>(unix_timestamp()-24*3600) GROUP BY board,cpu_name,machine_type,linux_os ORDER BY max(timestamp) desc');
    show_table($q);


    echo "<h1>Distro</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query('SELECT COUNT(*) cnt FROM benchmark_result WHERE (NOT ISNULL(linux_os)) AND linux_os<>"Unknown" AND benchmark_type="CPU Blowfish (Single-thread)"');
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query('SELECT IF(LEFT(linux_os,10)="Linux Mint","Mint",IF(LEFT(linux_os,3)="Red","RedHat",LEFT(linux_os,IF(LOCATE(" ",linux_os) - 1<0,99,LOCATE(" ",linux_os) - 1) ))) Distro, ROUND(COUNT(*)*100/'.$total.',1) percent FROM benchmark_result WHERE (NOT ISNULL(linux_os)) AND linux_os<>"Unknown" AND benchmark_type="CPU Blowfish (Single-thread)" GROUP BY Distro ORDER BY percent DESC;');
    show_table($q);


    echo "<h1>Machine type</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result;");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT machine_type, ROUND((COUNT(*)*100)/".$total.",1) percent FROM benchmark_result GROUP BY machine_type ORDER BY percent DESC;");
    show_table($q);


    echo "<h1>Motherboard</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown')");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT ".$BOARD.", ROUND((COUNT(*)*100)/".$total.",1) percent FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown') GROUP BY board ORDER BY percent DESC;");
    show_table($q);


    echo "<h1>CPU</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown')");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT cpu_name,round((count(*)*100)/".$total.",2) percent FROM benchmark_result WHERE NOT INSTR(machine_type,'irtual') AND benchmark_type='CPU Blowfish (Single-thread)' GROUP BY cpu_name ORDER BY COUNT(*) DESC;");
    show_table($q);


    echo "<h1>GPU Renderer</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query('SELECT COUNT(*) FROM benchmark_result WHERE NOT ISNULL(opengl_renderer) and not instr(machine_type,"irtual");');
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query('SELECT IF(INSTR(opengl_renderer,"llvmpipe") OR INSTR(opengl_renderer,"softpipe"),"Software",IF(INSTR(REPLACE(REPLACE(REPLACE(opengl_renderer, "(tm)",""),"(TM)",""),"(R)",""),"("),LEFT(REPLACE(REPLACE(REPLACE(opengl_renderer, "(tm)",""),"(TM)",""),"(R)",""),-1+LOCATE("(",REPLACE(REPLACE(REPLACE(opengl_renderer, "(tm)",""),"(TM)",""),"(R)",""))),opengl_renderer)) GPU,round((count(*)*100)/'.$total.',1) percent FROM benchmark_result WHERE NOT ISNULL(opengl_renderer) and not instr(machine_type,"irtual") GROUP BY GPU order by percent DESC;');
    show_table($q);

    $mysqli->close();
?>
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

function show_table_color($q){
    echo "<table class='tablecenter'>";
    $c=0;
    while(($c++<20) && ($row = $q->fetch_row())){
        echo "<tr>";
	$i=0;
	while($i<$q->field_count){
            echo "<td class='tableleft'>";
	    if(($row[0]=="2.2.11")) echo "<font color=blue>";//DEV
	    else if(($row[0]=="2.2.10") && ($row[1]%18==0)) echo "<font color=green>";//LKG REL
	    else if(($row[0]=="2.2.9") && ($row[1]%18==0)) echo "<font color=green>";//LKG
	    else if(($row[0]=="2.2.8") && ($row[1]%18==0)) echo "<font color=green>";//LKG
	    else if(($row[0]=="2.2.7") && ($row[1]%18==0)) echo "<font color=green>";//LKG REL
	    else if(($row[0]=="2.2.6") && ($row[1]%18==0)) echo "<font color=green>";//LKG
	    else if(($row[0]=="2.2.5") && ($row[1]%18==0)) echo "<font color=green>";//LKG
	    else if(($row[0]=="2.2.4") && ($row[1]%17==0)) echo "<font color=green>";//LKG REL
	    else if(($row[0]=="2.2.3") && ($row[1]%17==0)) echo "<font color=green>";//LKG
	    else if(($row[0]=="2.2.1") && ($row[1]%17==0)) echo "<font color=orange>";//has fault but data okay
	    else if(($row[0]=="2.1.11") && ($row[1]%15==0)) echo "<font color=orange>";//LTS
	    else if(($row[0]<"2.1.11") || $row[0]=="2.1.14" || $row[0]=="2.1.17") echo "<font color=red>";//TOO OLD
	    echo htmlspecialchars($row[$i])."</td>";
	    $i++;
	}
	echo "</tr>";
    }
    echo "</table>";
    echo "<font size='2'>Total: ".($q->num_rows)." - green:BEST, blue:DEV, orange:OK, black:Incomplete, red:UPDATE NOW!</font><br><br>";
}

    $BOARD='replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(board,"version",""),"Not Defined",""),"Vendor",""),"Board",""),"Version",""),"Type1",""),"Type2",""),"Name1",""),"Not Available",""),"Micro-Star International Co., Ltd.","MSI"),"Micro-Star International Co., Ltd","MSI"),"Not Applicable",""),"Build Date:",""),"/"," ")';

    echo "<h1>Last incoming benchmarks</h1>";
    $q=$mysqli->query('SELECT REPLACE(REPLACE(REPLACE(programver,"http://api.hardinfo2.org/benchmark.json?ver=",""),"&rel=0",""),"&rel=1","") API,count(*) cnt, left('.$BOARD.',45) ,cpu_name,  replace(gpu,"(D3D12)","")  ,if(instr(if(instr(machine_type,"board")or instr(cpu_name,"Atom"),"SBC",TRIM(machine_type)),"nknown"),"Unknown",if(instr(machine_type,"board")or instr(cpu_name,"Atom"),"SBC",TRIM(machine_type))) MachineType, if(locate("-",replace(linux_os,"(","-")),left(linux_os,locate("-",replace(linux_os,"(","-"))-2),linux_os) LinuxOS FROM benchmark_result WHERE timestamp>(unix_timestamp()-24*3600) and instr(programver,"http://api.hardinfo2.org/benchmark.json?ver=") GROUP BY programver,board,cpu_name,machine_type,linux_os ORDER BY max(timestamp) desc');
    show_table_color($q);


    echo "<h1>Distro</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query('SELECT COUNT(*) cnt FROM (SELECT linux_os, machine_id,date(from_unixtime(timestamp)) dato FROM benchmark_result WHERE valid=1 AND NOT ISNULL(linux_os) AND linux_os<>"Unknown" AND benchmark_type="CPU Blowfish (Single-thread)" GROUP BY machine_id,dato) itab');
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query('SELECT IF(LEFT(linux_os,10)="Linux Mint","Mint",IF(LEFT(linux_os,3)="Red","RedHat",LEFT(linux_os,IF(LOCATE(" ",linux_os) - 1<0,99,LOCATE(" ",linux_os) - 1) ))) Distro, ROUND(COUNT(*)*100/'.$total.',1) percent FROM (SELECT linux_os, machine_id,date(from_unixtime(timestamp)) dato FROM benchmark_result  WHERE valid=1 AND NOT ISNULL(linux_os) AND linux_os<>"Unknown" AND benchmark_type="CPU Blowfish (Single-thread)" GROUP BY machine_id,dato) itab GROUP BY Distro ORDER BY percent DESC;');

    show_table($q);


    echo "<h1>Machine type</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result;");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT machine_type, ROUND((COUNT(*)*100)/".$total.",1) percent FROM benchmark_result WHERE valid=1 and not instr(machine_type,'WSL') GROUP BY machine_type ORDER BY percent DESC;");
    show_table($q);


    echo "<h1>Motherboard</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result WHERE valid=1 AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown')");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT ".$BOARD.", ROUND((COUNT(*)*100)/".$total.",1) percent FROM benchmark_result WHERE valid=1 AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown') GROUP BY board ORDER BY percent DESC;");
    show_table($q);


    echo "<h1>CPU</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query("SELECT COUNT(*) FROM benchmark_result WHERE valid=1 AND NOT INSTR(board,'WSL')  AND NOT INSTR(board,'Unknown')");
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query("SELECT cpu_name,round((count(*)*100)/".$total.",2) percent FROM benchmark_result WHERE valid=1 AND benchmark_type='CPU Blowfish (Single-thread)' GROUP BY cpu_name ORDER BY COUNT(*) DESC;");
    show_table($q);


    echo "<h1>GPU</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query('SELECT COUNT(*) FROM benchmark_result WHERE NOT ISNULL(opengl_renderer) and valid=1;');
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query('SELECT GPU, ROUND((COUNT(*)*100)/'.$total.',1) percent FROM benchmark_result WHERE valid=1 GROUP BY GPU order by percent DESC;');
    show_table($q);

    echo "<h1>Machine Arch</h1><font size='2'>Percent most benchmarked</font><br>";
    $q=$mysqli->query('SELECT COUNT(*) FROM benchmark_result WHERE NOT ISNULL(linux_kernel) and valid=1;');
    $r=$q->fetch_row();
    $total=$r[0];
    $q=$mysqli->query('SELECT SUBSTR(linux_kernel,INSTR(linux_kernel,"(") + 1, CHAR_LENGTH(linux_kernel)-1-INSTR(linux_kernel,"(")) arch, ROUND((COUNT(*)*100)/'.$total.',1) percent FROM benchmark_result WHERE valid=1 AND NOT ISNULL(linux_kernel) GROUP BY arch ORDER BY COUNT(*) DESC;');
    show_table($q);

    echo "<h1>Unknown CPUs</h1>";
    $q=$mysqli->query('SELECT cpuen,cpuname FROM (SELECT REGEXP_SUBSTR(cpu_name, "[^(]+") cpuen FROM benchmark_result WHERE valid=1 GROUP BY cpu_name) a LEFT JOIN cpudb ON cpuname=cpuen WHERE ISNULL(cpuname) OR (LENGTH(cpuname)<1)');
    show_table($q);


    $mysqli->close();
?>
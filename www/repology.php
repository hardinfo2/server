<?php
//Copyright hardinfo2.org, written by hwspeedy
//License: GPL2+

header('Content-type: image/svg+xml');
$db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
$r=mysqli_fetch_row($db->query("select value<unix_timestamp(now())-3600*24 from settings where name='repology-refresh'"));

//Enable line below to always update, otherwise once per 24 hours
//$r[0]=1;

if(1*$r[0]){ //refresh

    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: PHP'
             ]
         ]
    ];

    $context = stream_context_create($opts);
    $packagestatus2 = file_get_contents('https://repology.org/badge/vertical-allrepos/hardinfo2.svg?columns=1&exclude_unsupported=1', false, $context);
    $p=array();
    $t=0;
    //strip data
    $t=strpos($packagestatus2,'"end"',$t+1)+5;
    if($t!==false) $t=strpos($packagestatus2,">",$t+1)+1;
    while($t !== false){
       $d=substr($packagestatus2,$t,strpos($packagestatus2,"<",$t)-$t);
       if($d[0]>'Z') $d[0]=strtoupper($d[0]);
       $t=strpos($packagestatus2,'"middle"',$t+1)+9;
       if($t!==false) $t=strpos($packagestatus2,">",$t+1)+1;
       $t=strpos($packagestatus2,'"middle"',$t+1)+9;
       if($t!==false) $t=strpos($packagestatus2,">",$t+1)+1;
       $v=substr($packagestatus2,$t,strpos($packagestatus2,"<",$t)-$t);
       $p[$d]=array("hardinfo2",$v);//save distro and version
       //skip dublet
       if($t!==false) $t=strpos($packagestatus2,'"end"',$t+1);
       if($t!==false) $t=strpos($packagestatus2,">",$t+1);
       if($t!==false) $t+=1;
       if($t!==false) $t=strpos($packagestatus2,'"end"',$t+1);
       if($t!==false) $t=strpos($packagestatus2,">",$t+1);
       if($t!==false) $t+=1;
    }
    ksort($p,SORT_STRING);
    $ps=array();$x=0;$currentver=0;$count=0;
    foreach ($p as $d=>$v){
      if(($d!="ALT Linux p9")&&($d!="ALT Linux p10"))  //EOL
      if(($d!="OpenMandriva 4.1")&&($d!="OpenMandriva 4.2")&&($d!="OpenMandriva 4.3"))  //EOL
      if(($d!="Rosa 2014.1")&&($d!="Rosa 2016.1"))  //EOL
      if(($v[0]=="hardinfo2") || ($v[1][0]>=0)){ //use 2 to only show new community edition
        $count++;
        $ps[$x++]=array($d,$v[1]);
	$a=strpos($v[1],'.',0);
	if($a!==false){
	  $b=strpos($v[1],'.',$a+1);
	  if($b!==false){
	     if(substr($v[1],0,$a)=="2"){
	        $c=substr($v[1],0,$a)*10000+substr($v[1],$a+1,$b-$a-1)*100+substr($v[1],$b+1,99);
	        if($c>$currentver) {$currentver=$c;$curver=$v[1];}
	     }
	  }
        }
      }else unset($p[$d]);
    }
    $rows=3;
    $col=intdiv($count+$rows-1,$rows);
    //create table
    $packagestatus="<table><tr><td colspan=".(2*$rows)." align=center>Package Status</td></tr>";
    for($x=0;$x<$col;$x++){
        $packagestatus.="<tr>";
	for($r=0;$r<$rows;$r++){
            $packagestatus.="<td>".$ps[$x+$col*$r][0]."</td><td>";
            if(strcmp($ps[$x+$col*$r][1],$curver)) {if($ps[$x+$col*$r][1][0]=="2") $packagestatus.="<font color=orange>"; else $packagestatus.="<font color=red>";}  else $packagestatus.="<font color=green>";
            $packagestatus.=$ps[$x+$col*$r][1]."</td>";
	}
	$packagestatus.="</tr>";
    }
    $packagestatus.="</table>";
    //create svg
    $packagestatus='<svg xmlns="http://www.w3.org/2000/svg" width="692" height="'.(($col*16)+24).'"><clipPath id="clip"><rect rx="3" width="100%" height="100%" fill="#000"/></clipPath><linearGradient id="grad" x2="0" y2="100%"><stop offset="0" stop-color="#bbb" stop-opacity=".1"/><stop offset="1" stop-opacity=".1"/></linearGradient><g clip-path="url(#clip)"><rect width="100%" height="100%" fill="#555"/><g fill="#fff" text-anchor="middle" font-family="DejaVu Sans,Verdana,Geneva,sans-serif" font-size="15" font-weight="bold"><text x="331.0" y="18" fill="#010101" fill-opacity=".3">Packaging status</text><text x="331.0" y="17">Packaging status</text></g>';

    for($x=0;$x<$col;$x++){
        if(strcmp($ps[$x+$col*0][1],$curver)) {if($ps[$x+$col*0][1][0]=="2") $c1="orange"; else {if(is_null($ps[$x+$col*0][1])) $c1="#555"; else $c1="red";}} else $c1="green";
        if(strcmp($ps[$x+$col*1][1],$curver)) {if($ps[$x+$col*1][1][0]=="2") $c2="orange"; else {if(is_null($ps[$x+$col*1][1])) $c2="#555"; else $c2="red";}} else $c2="green";
        if(strcmp($ps[$x+$col*2][1],$curver)) {if($ps[$x+$col*2][1][0]=="2") $c3="orange"; else {if(is_null($ps[$x+$col*2][1])) $c3="#555"; else $c3="red";}} else $c3="green";
        
        $packagestatus.='<rect x="142" y="'.(8+16*($x+1)).'" width="83" height="16" fill="'.$c1.'"/><rect x="369" y="'.(8+16*($x+1)).'" width="83" height="16" fill="'.$c2.'"/><rect x="602" y="'.(8+16*($x+1)).'" width="90" height="16" fill="'.$c3.'"/><rect y="'.(8+16*($x+1)).'" width="100%" height="16" fill="url(#grad)"/>
	<g fill="#fff" font-family="DejaVu Sans,Verdana,Geneva,sans-serif" font-size="11"><text x="137" y="'.(21+16*($x+1)).'" fill="#010101" fill-opacity=".3" text-anchor="end">'.$ps[$x+$col*0][0].'</text><text x="137" y="'.(20+16*($x+1)).'" text-anchor="end">'.$ps[$x+$col*0][0].'</text><text x="183.5" y="'.(21+16*($x+1)).'" fill="#010101" fill-opacity=".3" text-anchor="middle">'.$ps[$x+$col*0][1].'</text><text x="183.5" y="'.(20+16*($x+1)).'" text-anchor="middle">'.$ps[$x+$col*0][1].'</text><text x="364" y="'.(21+16*($x+1)).'" fill="#010101" fill-opacity=".3" text-anchor="end">'.$ps[$x+$col*1][0].'</text><text x="364" y="'.(20+16*($x+1)).'" text-anchor="end">'.$ps[$x+$col*1][0].'</text><text x="410.5" y="'.(21+16*($x+1)).'" fill="#010101" fill-opacity=".3" text-anchor="middle">'.$ps[$x+$col*1][1].'</text><text x="410.5" y="'.(20+16*($x+1)).'" text-anchor="middle">'.$ps[$x+$col*1][1].'</text><text x="597" y="'.(21+16*($x+1)).'" fill="#010101" fill-opacity=".3" text-anchor="end">'.$ps[$x+$col*2][0].'</text><text x="597" y="'.(20+16*($x+1)).'" text-anchor="end">'.$ps[$x+$col*2][0].'</text><text x="647.0" y="'.(21+16*($x+1)).'" fill="#010101" fill-opacity=".3" text-anchor="middle">'.$ps[$x+$col*2][1].'</text><text x="647.0" y="'.(20+16*($x+1)).'" text-anchor="middle">'.$ps[$x+$col*2][1].'</text></g>';
            
    }
    $packagestatus.="</g></svg>";
    
    
    $q=$db->prepare("update settings set value=? where name='repology-packagestatus'");
    $q->bind_param('b',$packagestatus);
    $q->send_long_data(0,$packagestatus);
    $q->execute();

    mysqli_query($db,"update settings set value=unix_timestamp(now()) where name='repology-refresh'");

}else{

    $r=mysqli_fetch_row(mysqli_query($db,"select value from settings where name='repology-packagestatus'"));
    $packagestatus=$r[0];
}
mysqli_close($db);

   echo $packagestatus;

?>
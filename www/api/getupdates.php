<?php
    $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

    $DEBUG=0;

    $q=mysqli_query($db,"select value from settings where name='latest-release-version'");
    $r=mysqli_fetch_row($q);
    $relver=$r[0];
    $q=mysqli_query($db,"select value from settings where name='latest-prerelease-version'");
    $r=mysqli_fetch_row($q);
    $prerelver=$r[0];

    echo "<article>";
    echo "<br>";

    if(!isset($_GET['arch'])) $_GET['arch']=" ";
    if(!isset($_GET['distro'])) $_GET['distro']=" ";

    $beta=0;$maxver=$relver;
    if(version_compare($prerelver,$relver,">")){$beta=1;$maxver=$prerelver;}
    $showupdate=0;
    if(version_compare($_GET['ver'],$maxver,">")){
        echo "<font color=blue><br><b>Your version is DEVELOPMENT edition.</b><br><br></font>";
    } else if(version_compare($_GET['ver'],$relver,"=")){
        echo "<font color=green><br><b>Your version is the newest DISTRO released.</b><br><br></font>";
	if($beta) {
	   echo "<font color=green><br><b>Beta/prerelease available</b><br><br></font>";
	   $showupdate=1;
	}
    } else if(!$beta && version_compare($_GET['ver'],$prerelver,">=")){
        echo "<font color=green><br><b>Your version is the newest released.</b><br><br></font>";
    } else if($beta && version_compare($_GET['ver'],$prerelver,"<")){
        echo "<font color=orange><br><b>Your version can be updated</b><br><br></font>";
        $showupdate=1;
    } else {
        echo "<font color=red><br><b>Your version needs update!</b><br><br></font>";
        $showupdate=1;
    }
    echo "<table>";
    echo "<tr><td>Your version</td><td>".$_GET['ver']."</td></tr>";
    echo "<tr><td>Your arch </td><td>".$_GET['arch']."</td></tr>";
    echo "<tr><td>Your distro </td><td>".$_GET['distro']."</td></tr>";
    echo "<tr><td>&nbsp; </td><td></td></tr>";
    echo "<tr><td>Lastest release version </td><td>".$relver."</td></tr>";
    echo "<tr><td>Lastest prerelease version </td><td>".$prerelver." (".($beta?"Beta":"Same as $relver").")</td></tr>";
    echo "</table>";

    echo "<br><br>";

    $showupdate=1;//HACK remove me
    echo "<b>FORCED TO SHOW NOT NEW PACKAGE FOR TEST RIGHT NOW</b><br><br>"; 

    if($showupdate){
       $downloads=file_get_contents("/var/www/html/server/www/downloads.ids");
       //Simple First name - combine if linux first
       $distroname=$_GET['distro'];
       if(strpos($distroname,' ')) $distroname=substr($distroname,0,strpos($distroname,' '));
       if($distroname=="Linux") $distroname=str_replace("Linux ","Linux",$_GET['distro']);
       //
       if($distroname=="Raspberry") $distroname="Raspbian";
       if(strstr($distroname,"buntu")) $distroname="Ubuntu";
       //
       if(strpos($distroname,'!')) $distroname=substr($distroname,0,strpos($distroname,'!'));
       if(strpos($distroname,'_')) $distroname=substr($distroname,0,strpos($distroname,'_'));
       if(strpos($distroname,' ')) $distroname=substr($distroname,0,strpos($distroname,' '));
       if(strpos($distroname,'0')) $distroname=substr($distroname,0,strpos($distroname,'0'));
       if(strpos($distroname,'1')) $distroname=substr($distroname,0,strpos($distroname,'1'));
       if(strpos($distroname,'2')) $distroname=substr($distroname,0,strpos($distroname,'2'));
       if(strpos($distroname,'3')) $distroname=substr($distroname,0,strpos($distroname,'3'));
       if(strpos($distroname,'4')) $distroname=substr($distroname,0,strpos($distroname,'4'));
       if(strpos($distroname,'5')) $distroname=substr($distroname,0,strpos($distroname,'5'));
       if(strpos($distroname,'6')) $distroname=substr($distroname,0,strpos($distroname,'6'));
       if(strpos($distroname,'7')) $distroname=substr($distroname,0,strpos($distroname,'7'));
       if(strpos($distroname,'8')) $distroname=substr($distroname,0,strpos($distroname,'8'));
       if(strpos($distroname,'9')) $distroname=substr($distroname,0,strpos($distroname,'9'));
       $distroname=trim($distroname);
       //
       $distronumber="";
       $s=$_GET['distro'];
       if(strpos($s," (")) $s=substr($s,0,strpos($s," ("));
       $n=0;
       while($s[$n] && (($s[$n]<'0') || ($s[$n]>'9'))) $n++;
       $e=$n;
       $hasver=0;
       while($s[$e] && ((($s[$e]>='0') && ($s[$e]<='9'))||($s[$e]=='.'))) {$hasver=1;$e++;}
       if($hasver) {$distronumber=trim(substr($_GET['distro'],$n,$e-$n+1));}
       //
       if(!$hasver) {
	  //$distroname=trim(str_replace(" ","",$s));
	  $distronumber=trim(substr($s,strripos($s," "),999));
	  if(strpos($distronumber,"/")) $distronumber=substr($distronumber,0,strpos($distronumber,"/"));
	  $hasver=1;
       }
       $check=1;
       while($check){
           if($DEBUG) echo "DistroName=".$distroname.". - DistroNumber=".$distronumber.".<br>";
	   $found=0;
	   $p=strstr($downloads,"<a");
	   while($p) {
	       $t=strpos($p,"<br>");
	       $d=substr($p,0,$t);
	       $dcmp=str_replace($prerelver."_","",str_replace($prerelver."-","",str_replace("hardinfo2-","",str_replace("hardinfo2_","",$d))));

               $arch=0;
               if($_GET['arch']=="x86_64") if(strstr($dcmp,"amd64")) $arch=1;
               if(strstr($dcmp,trim($_GET['arch']))) $arch=1;

               $distro=0;
               if(strstr($dcmp,$distroname)) $distro=1;
	       if($hasver && !strstr($dcmp,$distronumber)) $distro=0;
	       //echo $arch.$distro.$d."<br>";
	       if($DEBUG) echo $arch.$distro.$dcmp."<br>";
	       if($arch && $distro) {$found=1; echo "New Package: ".$d."<br>";}

	       $p=strstr($p,"<br>");
               $p=strstr($p,"<a");
           }
           if($found) $check=0;
           else if(strpos($distronumber,'.')) {$distronumber=substr($distronumber,0,strpos($distronumber,'.'));}
           else $check=0;
       }
       if(!$found) {
          echo "Please build new package from source <a href='https://github.com/hardinfo2/hardinfo2'>https://github.com/hardinfo2/hardinfo2</a>";
	  mysqli_query($db,"insert into settings values ('updates-".$db->real_escape_string($_GET['arch']."-".$_GET['distro'])."','')");
       }
    }

    echo "</article><br><br><br><br><br>";

    mysqli_close($db);
?>
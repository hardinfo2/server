<?php
    $db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");

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

    if($showupdate){
       $downloads=file_get_contents("/var/www/html/server/www/downloads.ids");
       //Simple First name
       $distroname=$_GET['distro'];
       if(strpos($distroname,' ')) $distroname=substr($distroname,0,strpos($distroname,' '));
       //
       $distrnumber="";
       $s=$_GET['distro'];
       if(strpos($s," (")) $s=substr($s,0,strpos($s," ("));
       $n=0;
       while($s[$n] && (($s[$n]<'0') || ($s[$n]>'9'))) $n++;
       $e=$n;
       $hasver=0;
       while($s[$e] && ((($s[$e]>='0') && ($s[$e]<='9'))||($s[$e]=='.'))) {$hasver=1;$e++;}
       if($hasver) {$distronumber=substr($_GET['distro'],$n,$e-$n+1);}
       //
       if(!$hasver) {
	  $distroname=str_replace(" ","",$s);
       }
       $check=1;
       while($check){
           //echo "DistroName=".$distroname.". - DistroNumber=".$distronumber.".<br>";
	   $found=0;
	   $p=strstr($downloads,"<a");
	   while($p) {
	       $t=strpos($p,"<br>");
	       $d=substr($p,0,$t);

               $arch=0;
               if($_GET['arch']=="x86_64") if(strstr($d,"amd64")) $arch=1;
               if(strstr($d,$_GET['arch'])) $arch=1;
	       //if(strlen($_GET['arch'])<=1) $arch=1;

               $distro=0;
               if(strstr($d,$distroname)) $distro=1;
	       if($hasver && !strstr($d,$distronumber)) $distro=0;
	       //if(strlen($_GET['distro'])<=1) $distro=1;
	       //echo $arch.$distro.$d."<br>";
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
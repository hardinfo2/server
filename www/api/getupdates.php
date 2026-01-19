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
	$showupdate=2;
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

       $downloads=file_get_contents("/var/www/html/server/www/downloads.ids");
       //
       $distronumber="";
       $s=str_replace("/","",str_replace("!",".",$_GET['distro']));
       if(strpos($s," (")) $s=substr($s,0,strpos($s," ("));
       $n=0;
       while( (strlen($s)>$n) && (($s[$n]<'0') || ($s[$n]>'9'))) $n++;
       $e=0;
       $hasver=0;
       while( (strlen($s)>($n+$e)) && ( (($s[$n+$e]>='0') && ($s[$n+$e]<='9')) || ($s[$n+$e]=='.') ) ) {$hasver=1;$e++;}
       if($hasver) {$distronumber=trim(substr($s,$n,$e));}
       //
       if($hasver) $distroname=trim(substr($s,0,$n)); else $distroname=$s;
       if(($distroname[strlen($distroname)-2]==" ")&&($distroname[strlen($distroname)-1]=="v")) $distroname=str_replace(" v","",$distroname);
       $distroname=trim(str_replace(" ","",$distroname));
       //
       if(!$hasver) {
	  $distronumber=trim(substr($s,strripos($s," "),999));
	  if(strpos($distronumber,"/")) $distronumber=substr($distronumber,0,strpos($distronumber,"/"));
	  if(($distronumber==$distroname) || ($distronumber=="Linux")) $distronumber="";
          $distroname=str_replace($distronumber,"",$distroname);
	  if(strlen($distronumber)) $hasver=1;
       }
       //Special changes
       if(strstr($distroname,"MX")) $distroname="MXLinux";
       if(strstr($distroname,"buntu")) $distroname="Ubuntu";
       if(strstr($distroname,"Fedora") && strlen($distronumber)>3) $distroname="FedoraAtomic";
       if(strstr($distronumber,"sid")) $distronumber=str_replace("sid","",$distronumber);
       if(strstr($distroname,"SystemRescue")) $distronumber="";
       if(strstr($distroname,"Alma")) $distroname="RedHatEnterpriseLinux";
       if(strstr($distroname,"Rocky")) $distroname="RedHatEnterpriseLinux";
       //
       $check=1;
       $filenames="";
       while($check){
           if($DEBUG) echo "DistroName=".$distroname.". - DistroNumber=".$distronumber.".<br>";
	   $found=0;
	   $p=strstr($downloads,"<a");
	   while($p) {
	       //url & filename
	       $t=strpos($p,"<br>");
	       $d=substr($p,0,$t);//url with filename

               //filename only
	       $t=strpos($d,"\">")+2;
	       $dcmp=substr($d,$t,strpos($d,"</a")-2);
	       $dcmp=trim(str_replace($prerelver."_","",str_replace($prerelver."-","",str_replace("hardinfo2-","",str_replace("hardinfo2_","",$dcmp)))));

               $arch=0;
               if(trim($_GET['arch'])=="x86_64") if(strstr($dcmp,"amd64")) $arch=1;
               if(strstr($dcmp,trim($_GET['arch']))) $arch=1;

               $distro=0;
	       if( (strstr($dcmp,$distronumber)) && (strstr($dcmp,$distroname)) ) $distro=1;

	       if($DEBUG) echo $arch.$distro.$dcmp."<br>";
	       if($arch && $distro) {
	           $found++;
		   if($showupdate==1) echo "New Package: ".$d."</a><br>";
		   if($showupdate==2) echo "Stable Package: ".$d."</a><br>";
		   if(strlen($filenames)) {$filenames.=", ";}
		   $filenames.=substr($d,strpos($d,'">')+2,strpos($d,'</')-strpos($d,'">')-2);
	       }

	       $p=strstr($p,"<br>");
               $p=strstr($p,"<a");
           }
           if($found) $check=0;
           else if(strpos($distronumber,'.')) {$distronumber=trim(substr($distronumber,0,strripos($distronumber,'.')));}
           else if(strpos($distroname,"Linux")) {$distroname=trim(str_replace("Linux","",$distroname));}
           else $check=0;
       }
       if(!$found) 
          echo "Please build new package from source <a href='https://github.com/hardinfo2/hardinfo2'>https://github.com/hardinfo2/hardinfo2</a>";
       mysqli_query($db,"insert into updates values ('".$db->real_escape_string($_GET['arch'])."','".$db->real_escape_string($_GET['distro'])."','".$db->real_escape_string($distroname)."','".$db->real_escape_string($distronumber)."',".$found.",'".$filenames."',1) on duplicate key update times=times*1 + 1,distroname='".$db->real_escape_string($distroname)."',distronumber='".$db->real_escape_string($distronumber)."',found=".$found.",filenames='".$db->real_escape_string($filenames)."';");

    echo "</article><br><br><br><br><br>";

    mysqli_close($db);
?>
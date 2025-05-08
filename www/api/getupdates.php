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
    $beta=0;$maxver=$relver;
    if(version_compare($prerelver,$relver,">")){$beta=1;$maxver=$prerelver;}
    
    if(version_compare($_GET['ver'],$maxver,">")){
        echo "<font color=blue><br><b>Your version is DEVELOPMENT edition.</b><br><br></font>";
    } else if(version_compare($_GET['ver'],$relver,"=")){
        echo "<font color=green><br><b>Your version is the newest DISTRO released.</b><br><br></font>";
	if($beta) echo "<font color=green><br><b>Beta/prerelease available</b><br><br></font>";
    } else if(!$beta && version_compare($_GET['ver'],$prerelver,">=")){
        echo "<font color=green><br><b>Your version is the newest released.</b><br><br></font>";
    } else if($beta && version_compare($_GET['ver'],$prerelver,"<")){
        echo "<font color=orange><br><b>Your version can be updated</b><br><br></font>";
    } else {
        echo "<font color=red><br><b>Your version needs update!</b><br><br></font>";
    }

    echo "Your version ".$_GET['ver']."<br>";
    echo "Your arch ".$_GET['arch']."<br>";
    echo "Your distro ".$_GET['distro']."<br>";
    echo "<br>";
    echo "Lastest release version ".$relver."<br>";
    echo "Lastest prerelease version ".$prerelver." (".($beta?"Beta":"Same as $relver").")<br>";
    echo "</article>";

    mysqli_close($db);
?>
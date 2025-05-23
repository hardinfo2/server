<?php
//Copyright hardinfo2.org, written by hwspeedy
//License: GPL2+

$db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
$r=mysqli_fetch_row($db->query("select value<unix_timestamp(now())-3600*24 from settings where name='github-refresh'"));

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
    $releasetxt = file_get_contents('https://api.github.com/repos/hardinfo2/hardinfo2/releases', false, $context);
    $q=$db->prepare("update settings set value=? where name='github-releasetxt'");
    $q->bind_param('b',$releasetxt);
    $q->send_long_data(0,$releasetxt);
    $q->execute();

    mysqli_query($db,"update settings set value=unix_timestamp(now()) where name='github-refresh'");

    //Find release and prerelease versions and store in settings
    $releases = json_decode($releasetxt);
    $n=0;
    while(isset($releases[$n])){
        if($releases[$n]->prerelease && !isset($prerelver) ){
            $prerelver = $releases[$n]->name;
	}
        if(!$releases[$n]->prerelease && !isset($relver)){
            $relver = $releases[$n]->name;
        }
	$n++;
    }
    $release_ver=str_replace("v","",$relver);
    $pre_release_ver=str_replace("pre","",str_replace("v","",$prerelver));
    mysqli_query($db,"update settings set value='".$release_ver."' where name='latest-release-version'");
    mysqli_query($db,"update settings set value='".$pre_release_ver."' where name='latest-prerelease-version'");


}else{

    $r=mysqli_fetch_row(mysqli_query($db,"select value from settings where name='github-releasetxt'"));
    $releasetxt=$r[0];
}
mysqli_close($db);

$releases = json_decode($releasetxt);

$action="";
if(isset($_GET['latest'])) $action="latest";
if(isset($_GET['credits'])) $action="credits";
if(isset($_GET['release_info'])) $action="release_info";
if(isset($_GET['latest_release'])) $action="latest_release";
if(isset($_GET['latest_prerelease'])) $action="latest_prerelease";
if(isset($_GET['download'])) $action="latest_prerelease";
if(isset($_GET['downloadlist'])) $action="latest_prerelease";
if(isset($_GET['ordereddownloadlist'])) $action="ordereddownloadlist";
if(isset($_GET['latest_git_release'])) $action="latest_git_release";

$url="";

if($action=="credits"){
    echo file_get_contents("/var/www/html/server/www/credits.ids");
    exit(0);
}

if($action=="ordereddownloadlist"){
    echo file_get_contents("/var/www/html/server/www/downloads.ids");
    exit(0);
}

if($action=="release_info"){
    //latest release
    $url="https://github.com/hardinfo2/hardinfo2/releases/latest";
    $relver="Latest";
    $release_info="Se <a href='https://github.com/hardinfo2/hardinfo2/releases/latest'>https://github.com/hardinfo2/hardinfo2/releases/latest</a><br>";
    $n=0;
    while(isset($releases[$n])){
        if(!$releases[$n]->prerelease){
            $url = $releases[$n]->html_url;
	    $relver=$releases[$n]->name;
            $release_info=$releases[$n]->body;
	    $n=-2;
        }
        $n++;
    }
    //
    $release_info=preg_replace('"\b(https?://\S+)"', '<a target="_blank" href="$1">Link</a>', $release_info);
    $release_info=str_replace("-----------\r\n","<hr>",$release_info);
    $release_info=str_replace("**\r\n","</b><br>",$release_info);
    $release_info=str_replace("**","<b>",$release_info);
    $release_info=str_replace("\r\n","<br>",$release_info);
    $release_split=explode("Updates from",$release_info);
    $release_info=$release_split[0]."Updates from".$release_split[1]."Updates from".$release_split[2];
    $release_ver=str_replace("v","",$relver);
    //
    echo "<b><font color=blue>Version: ".$release_ver."</font></b><br><br>".$release_info;
    echo "See complete change list at github release: <a href='".$url."'>".$release_ver."</a>";
    exit(0);
}

//Redirect to latest release/prerelease
if($action=="latest"){
    $url = $releases[0]->html_url;
}

//Redirect to latest release fallback to any release
if($action=="latest_release"){
    $n=0;
    $url="https://github.com/hardinfo2/hardinfo2/releases/latest";
    while(isset($releases[$n])){
        if(!$releases[$n]->prerelease){
            $url = $releases[$n]->html_url;
	    $n=-2;
        }
        $n++;
    }
}

//Redirect to latest prerelease fallback to any release
if($action=="latest_prerelease"){
    $n=0;
    $url="https://github.com/hardinfo2/hardinfo2/releases/latest";
    while(isset($releases[$n])){
        if($releases[$n]->prerelease){
            $url = $releases[$n]->html_url;
	    $n=-2;
        }
        $n++;
    }
    if(isset($_GET['downloadlist'])) $url=str_replace("tag","expanded_assets",$url);
}

//get latest git release
//used by users to switch to stable builds
//will get latest prerelease just before release
//release is only build by distros, but the prerelease just before is the same as distro release
if($action=="latest_git_release"){
    $n=0;
    $url="master";
    $foundrelease=0;
    while(isset($releases[$n])){
        if($foundrelease==0){
            if(!$releases[$n]->prerelease){
	        $foundrelease=1;
                $url = $releases[$n]->tag_name;
            }
	} else {
            if($releases[$n]->prerelease){
                $url = $releases[$n]->tag_name;
	        $n=-2;
            }
	}
        $n++;
    }
    echo $url;
    exit(0);
}


if($url!=""){
    header('Location: ' . $url);
}else if(0) {//Debug
    echo $action."<br>";
    echo $url."<br>";
    echo $releasetxt;
}
?>
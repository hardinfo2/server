<?php
$db=new mysqli("127.0.0.1","hardinfo","hardinfo","hardinfo");
$r=mysqli_fetch_row($db->query("select value<unix_timestamp(now())-3600 from settings where name='github-refresh'"));

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

}else{

    $r=mysqli_fetch_row(mysqli_query($db,"select value from settings where name='github-releasetxt'"));
    $releasetxt=$r[0];
}
mysqli_close($db);

$releases = json_decode($releasetxt);

$action="";
if(isset($_GET['latest'])) $action="latest";
if(isset($_GET['latest_release'])) $action="latest_release";
if(isset($_GET['latest_prerelease'])) $action="latest_prerelease";

$url="";

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
}

if($url!=""){
    header('Location: ' . $url);
}else if(0) {//Debug
    echo $action."<br>";
    echo $url."<br>";
    echo $releasetxt;
}
?>
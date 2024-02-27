<?php

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
//Copyright hardinfo2 project 2024, written by hwspeedy
//License: GPL2+


function changeLanguage(){
    console.log("Changing language to " + document.getElementById("language").value);
    event.preventDefault();
    for(var i=0; i<window["translate"].length; i++){
	if(window["translate"][i][1]===document.getElementById("language").value) document.getElementById(window["translate"][i][0]).innerHTML=window["translate"][i][2];
    }
}

//Showing a chart.js from bmval
function create_chart(bmtype,bmval,name) {
    var cpus = new Array();
    var marks = new Array();
    for(var i=0; i<bmval.length; i++){
	cpus.push(bmval[i][0]);
	marks.push(bmval[i][1]);
    }
    var color="red";
    if(bmtype>="CPU Crypto") color="orange";
    if(bmtype>="FPU FFT") color="magenta";
    if(bmtype>="GPU") color="brown";
    if(bmtype>="Internal") color="lightblue";
    if(bmtype>="SysBench") color="green";
    graph={type:"bar",
	   height: ((i+1)*24),
	   data: {labels: cpus,
		  datasets: [{
		      borderColor: color,
		      backgroundColor: color,
		      label: bmtype,
		      data: marks,
		  }]
		 },

	   options: {
	       maintainAspectRatio:false,
	       indexAxis:"y",
	       responsive: true,
               scales: {x:{type:"logarithmic", y:{type:"category"}} }
	   }
	  };
    var ctx = document.getElementById(name).getContext('2d');
    if(window.hasOwnProperty(name)) window[name].destroy();
    //set Height
    document.getElementById("Page-" + name).style.height=graph.height+"px";
    document.getElementById(name).style.height=graph.height+"px";
    //create Chart
    window[name] = new Chart(ctx, graph);
};

//Showing a chart.js for compare of cpus
function create_chart_compare() {
    var minval=100;
    var maxval=100;
    var bc1=document.getElementById("bc1").value;
    var bc2=document.getElementById("bc2").value;
    var bc3=document.getElementById("bc3").value;
    //console.log("Updating Compare "+bc1+","+bc2+","+bc3);
    var cpu1 = new Array(window["bmtypes"].length);
    var cpu2 = new Array(window["bmtypes"].length);
    var cpu3 = new Array(window["bmtypes"].length);
    var bcount=(bc1>=0?1:0)+(bc2>=0?1:0)+(bc3>=0?1:0);
    var datas=new Array();
    var bmtypesavg=new Array();
    //Find data and add from bmtypes index
    for(var i=0; i<window["bmtypes"].length; i++){
	bmtypesavg.push(window["bmtypes"][i]);
        if(window["bmval"][i]) for(var j=0; j<window["bmval"][i].length; j++){
	    if(window["bmval"][i][j][0]===window["bmcpus"][bc1]) cpu1[i]=window["bmval"][i][j][1];
	    if(window["bmval"][i][j][0]===window["bmcpus"][bc2]) cpu2[i]=window["bmval"][i][j][1];
	    if(window["bmval"][i][j][0]===window["bmcpus"][bc3]) cpu3[i]=window["bmval"][i][j][1];
	}
    }
    bmtypesavg.push("Average");
    //procent
    avg1=0;avg2=0;avg3=0;b=0;
    for(var i=0; i<window["bmtypes"].length; i++){
	if(bcount>1){
            if(bc1>=0) {b=1;b100=cpu1[i];} else
		if(bc2>=0) {b=2;b100=cpu2[i];} else
	            if(bc3>=0) {b=3;b100=cpu3[i];}
	  cpu1[i]=(100*cpu1[i])/b100;
	  cpu2[i]=(100*cpu2[i])/b100;
	  cpu3[i]=(100*cpu3[i])/b100;
	}
	//min/max
	if(bc2>=0) if(cpu2[i]>maxval) maxval=cpu2[i];
	if(bc3>=0) if(cpu3[i]>maxval) maxval=cpu3[i];
	if(bc2>=0) if(cpu2[i]<minval) minval=cpu2[i];
	if(bc3>=0) if(cpu3[i]<minval) minval=cpu3[i];
	//avg with Fix for missing values
	if((bc1>=0) && !cpu1[i]>0) {avg1+=100;}else{avg1+=cpu1[i];}
	if((bc2>=0) && !cpu2[i]>0) {avg2+=100;}else{avg2+=cpu2[i];}
	if((bc3>=0) && !cpu3[i]>0) {avg3+=100;}else{avg3+=cpu3[i];}
    }
    //add system average
    if(b==1){
      cpu1.push((avg1*100)/avg1);
      cpu2.push((avg2*100)/avg1);
      cpu3.push((avg3*100)/avg1);
    }
    if(b==2){
      cpu1.push((avg1*100)/avg2);
      cpu2.push((avg2*100)/avg2);
      cpu3.push((avg3*100)/avg2);
    }
    if(b==3){
      cpu1.push((avg1*100)/avg3);
      cpu2.push((avg2*100)/avg3);
      cpu3.push((avg3*100)/avg3);
    }
    //add data
    if(bc1>=0) datas.push({ borderColor: "red", backgroundColor: "red", label: window["bmcpus"][bc1], data: cpu1});
    if(bc2>=0) datas.push({ borderColor: "blue", backgroundColor: "blue", label: window["bmcpus"][bc2], data: cpu2});
    if(bc3>=0) datas.push({ borderColor: "green", backgroundColor: "green", label: window["bmcpus"][bc3], data: cpu3});
    h=bcount*bmtypesavg.length*16;
    if(bcount<=1) h=bmtypesavg.length*24;
    graph={type:"bar",
	   height: h,
	   data: {labels: bmtypesavg,
		  datasets: datas
		 },
	   options: {
	       maintainAspectRatio:false,
	       indexAxis:"y",
	       responsive: true,
               scales: {x:{/*min:minval, max:maxval,*/ type:"logarithmic"} }
	   }
	  };
    if(bcount>1) graph.options.scales.x.type="linear";
    var ctx = document.getElementById("bcchart").getContext('2d');
    if(window.hasOwnProperty("bcchart")) window["bcchart"].destroy();
    //set Height
    document.getElementById("bcchart").style.height=graph.height+"px";
    document.getElementById("bcchartdiv").style.height=(graph.height)+"px";
    document.getElementById("Page-benchmarkcompare").style.height=(graph.height+100)+"px";
    //create Chart
    window["bcchart"] = new Chart(ctx, graph);
};


let slideIndex = 0;

function showSlides() {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");
    for (i = 0; i < slides.length; i++) {
	slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}
    for (i = 0; i < dots.length; i++) {
	dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
    setTimeout(showSlides, 3500);
}


function toggleMenu() {
    event.preventDefault();
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
	x.className += " responsive";
    } else {
	x.className = "topnav";
    }
}

function showPage() {
    //console.log("showPage " + this.name);
    event.preventDefault();
    var topnav=document.querySelector('#myTopnav');
    if(event.target.tagName === 'A') topnav.classList.remove('responsive');
    //
    let navlist = document.querySelectorAll('.navlist');
    //
    var obj=this;
    if (obj == document.getElementById('app')) {
	obj=document.getElementById('home');
    }
    //
    for (let x = 0; x < navlist.length; x++) {
        if (navlist[x] == document.getElementById('app')) {
	    navlist[x].classList.remove('active');
	} else if (navlist[x] == obj) {
	    navlist[x].classList.add('active');
	    document.getElementById("Page-" + navlist[x].name).style.display="block";
	} else {
	    navlist[x].classList.remove('active');
	    document.getElementById("Page-" + navlist[x].name).style.display="none";
	}
    }
}

function html_table(bmtypes,bmval,htmltable) {
    text="<table border=1 cellspacing=0 cellpadding=3><tr><td colspan=2 bgcolor=lightblue><b>"+bmtypes+"</b></td></tr>";
    for(var i=0;i<bmval.length;i++){
	text=text+"<tr><td>"+bmval[i][0]+"</td><td align=right>"+parseFloat(bmval[i][1]).toFixed(2)+"</td></tr>";
    }
    text=text+"</table>";
    htmltable.innerHTML=text;
}
function create_tables_graphs(bm) {
    var text="";
    var textg="";
    const bmval=new Array(15);
    const bmtypes=new Array();
    const bmcpus=new Array();
    //Create top nav menu
    //Json: 0:cpu,1:bmtype,2:value
    //find bm types, cpus
    for(var i=0; i<bm.length; i++){
	if(! bmcpus.includes(bm[i][0].toString()) ) {
	    bmcpus.push(bm[i][0].toString());
	}
	if(! bmtypes.includes(bm[i][1].toString()) ) {
	    bmtypes.push(bm[i][1].toString());
            t=bmtypes.indexOf(bm[i][1].toString());
        }
    }
    bmtypes.sort();
    bmcpus.sort();
    //create data array for types
    for(var i=0; i<bmtypes.length; i++){
	bmval[i]=new Array();
    }
    //fill data
    for(var i=0; i<bm.length; i++){
       t=bmtypes.indexOf(bm[i][1].toString());
       bmval[t].push( [bm[i][0],bm[i][2]] );
    }
    //create navtop
    for(var i=0; i<bmtypes.length; i++){
	text = text + '<a href="#" name="bs'+i+'" class="navlist">'+bmtypes[i].toString()+'</a>';
	textg = textg + '<a href="#" name="bg'+i+'" class="navlist">'+bmtypes[i].toString()+'</a>';
    }
    benchstat.innerHTML=text;
    benchgraph.innerHTML=textg;
    let navlist = document.querySelectorAll('.navlist');
    for (let i = 0; i < navlist.length; i++) {
	e=navlist[i];
	navlist[i].addEventListener('click', showPage, false);
    }
    //Create html tables
    if(bmtypes.length>0) bmval[0].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>1) bmval[1].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>2) bmval[2].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>3) bmval[3].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>4) bmval[4].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>5) bmval[5].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>6) bmval[6].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>7) bmval[7].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>8) bmval[8].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>9) bmval[9].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>10) bmval[10].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>11) bmval[11].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>12) bmval[12].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>13) bmval[13].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>14) bmval[14].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>15) bmval[15].sort((a,b)=>b[1]-a[1]);
    if(bmtypes.length>0) html_table(bmtypes[0],bmval[0],htmltables0);
    if(bmtypes.length>1) html_table(bmtypes[1],bmval[1],htmltables1);
    if(bmtypes.length>2) html_table(bmtypes[2],bmval[2],htmltables2);
    if(bmtypes.length>3) html_table(bmtypes[3],bmval[3],htmltables3);
    if(bmtypes.length>4) html_table(bmtypes[4],bmval[4],htmltables4);
    if(bmtypes.length>5) html_table(bmtypes[5],bmval[5],htmltables5);
    if(bmtypes.length>6) html_table(bmtypes[6],bmval[6],htmltables6);
    if(bmtypes.length>7) html_table(bmtypes[7],bmval[7],htmltables7);
    if(bmtypes.length>8) html_table(bmtypes[8],bmval[8],htmltables8);
    if(bmtypes.length>9) html_table(bmtypes[9],bmval[9],htmltables9);
    if(bmtypes.length>10) html_table(bmtypes[10],bmval[10],htmltables10);
    if(bmtypes.length>11) html_table(bmtypes[11],bmval[11],htmltables11);
    if(bmtypes.length>12) html_table(bmtypes[12],bmval[12],htmltables12);
    if(bmtypes.length>13) html_table(bmtypes[13],bmval[13],htmltables13);
    if(bmtypes.length>14) html_table(bmtypes[14],bmval[14],htmltables14);
    if(bmtypes.length>15) html_table(bmtypes[15],bmval[15],htmltables15);
    //create graphs
    if(bmtypes.length>0) create_chart(bmtypes[0],bmval[0],'bg0');
    if(bmtypes.length>1) create_chart(bmtypes[1],bmval[1],'bg1');
    if(bmtypes.length>2) create_chart(bmtypes[2],bmval[2],'bg2');
    if(bmtypes.length>3) create_chart(bmtypes[3],bmval[3],'bg3');
    if(bmtypes.length>4) create_chart(bmtypes[4],bmval[4],'bg4');
    if(bmtypes.length>5) create_chart(bmtypes[5],bmval[5],'bg5');
    if(bmtypes.length>6) create_chart(bmtypes[6],bmval[6],'bg6');
    if(bmtypes.length>7) create_chart(bmtypes[7],bmval[7],'bg7');
    if(bmtypes.length>8) create_chart(bmtypes[8],bmval[8],'bg8');
    if(bmtypes.length>9) create_chart(bmtypes[9],bmval[9],'bg9');
    if(bmtypes.length>10) create_chart(bmtypes[10],bmval[10],'bg10');
    if(bmtypes.length>11) create_chart(bmtypes[11],bmval[11],'bg11');
    if(bmtypes.length>12) create_chart(bmtypes[12],bmval[12],'bg12');
    if(bmtypes.length>13) create_chart(bmtypes[13],bmval[13],'bg13');
    if(bmtypes.length>14) create_chart(bmtypes[14],bmval[14],'bg14');
    if(bmtypes.length>15) create_chart(bmtypes[15],bmval[15],'bg15');
    //create selectors for benchmark compare
    text="";
    for (let i = 1; i <= 3; i++) {
	text=text+"<select name=\"bc"+i+"\" id=\"bc"+i+"\">";
	text=text+"<option value=-1>Please Select</option>";
        for(var t=0; t<bmcpus.length; t++){
	    text=text+"<option ";
	    if(i==1 && bmcpus[t].toString()==="AMD Ryzen 9 5950X") text=text+"selected ";
	    if(i==2 && bmcpus[t].toString()==="AMD Ryzen 9 7950X") text=text+"selected ";
	    //if(i==3 && bmcpus[t].toString()==="AMD EPYC 9354P") text=text+"selected ";
	    text=text+"value="+t+">"+bmcpus[t].toString()+"</option>";
	}
	text=text+"</select> ";
    }
    bcsel.innerHTML=text+"<br><font size='2'>Select 1: Shows numbers for cpu type instead of % compare</font>";
    //save calculated data globally
    window["bmtypes"]=bmtypes;
    window["bmcpus"]=bmcpus;
    window["bmval"]=bmval;
    //create graph compare
    create_chart_compare();
    //event for selecting
    document.getElementById("bc1").addEventListener('change', create_chart_compare, false);
    document.getElementById("bc2").addEventListener('change', create_chart_compare, false);
    document.getElementById("bc3").addEventListener('change', create_chart_compare, false);
}

document.addEventListener('DOMContentLoaded', function() {
    //hamburger icon
    var elements=document.getElementsByClassName("icon");
    for (var i = 0; i < elements.length; i++) {
	elements[i].addEventListener('click', toggleMenu, false);
    }
    //Added for navigation if no benchmarks are received
    let navlist = document.querySelectorAll('.navlist');
    for (let i = 0; i < navlist.length; i++) {
	e=navlist[i];
	navlist[i].addEventListener('click', showPage, false);
    }
    let url=window.location.href;
    if(url.includes("news")) {const event=new Event('click');navlist[1].dispatchEvent(event);}
    if(url.includes("benchcompare")) {const event=new Event('click');navlist[2].dispatchEvent(event);}
    if(url.includes("app")) {const event=new Event('click');navlist[3].dispatchEvent(event);}
    if(url.includes("userguide")) {const event=new Event('click');navlist[4].dispatchEvent(event);}
    if(url.includes("history")) {const event=new Event('click');navlist[5].dispatchEvent(event);}
    if(url.includes("credits")) {const event=new Event('click');navlist[6].dispatchEvent(event);}
    if(url.includes("about")) {const event=new Event('click');navlist[6].dispatchEvent(event);}
    //language
    if(document.getElementById("language"))
	document.getElementById("language").addEventListener('change', changeLanguage, false);
    if(document.getElementById("language"))
	document.getElementById("language").addEventListener('click', function() {event.preventDefault();}, false);
    //get benchmark data
    fetch('/api/getbenchmarks?'+window.location.search.substr(1))
	.then((response) => response.text())
        .then((text) => {
	    create_tables_graphs(JSON.parse(text));
	});
    //get dbstats
    fetch('/api/getdbstats')
	.then((response) => response.text())
        .then((text) => {
	    dbstats.innerHTML=text;
	});
    //get github release info
    fetch('/github/?release_info')
	.then((response) => response.text())
        .then((text) => {
	    githubnews.innerHTML=text;
	});
    //get credits
    fetch('/github/?credits')
	.then((response) => response.text())
        .then((text) => {
	    githubcredits.innerHTML=text;
	});
    showSlides();
  },false);

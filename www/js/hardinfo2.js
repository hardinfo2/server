//Copyright hardinfo2 2024, written by hwspeedy
//Showing a chart.js graph
function draw_chart(graph,name) {
    var ctx = document.getElementById(name).getContext('2d');
    if(window.hasOwnProperty(name)) window[name].destroy();
    document.getElementById(name).height=graph.height;
    window[name] = new Chart(ctx, graph);
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
    console.log("showPage " + this.name);
    //
    event.preventDefault();
    let navlist = document.querySelectorAll('.navlist');
    for (let x = 0; x < navlist.length; x++) {
	if (navlist[x] == this) {
	    navlist[x].classList.add('active');
	    document.getElementById("Page-" + navlist[x].name).style.display="block";
	} else {
	    navlist[x].classList.remove('active');
	    document.getElementById("Page-" + navlist[x].name).style.display="none";
	}
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var elements=document.getElementsByClassName("icon");
    for (var i = 0; i < elements.length; i++) {
	elements[i].addEventListener('click', toggleMenu, false);
    }

    let navlist = document.querySelectorAll('.navlist');
    for (let i = 0; i < navlist.length; i++) {
	e=navlist[i];
	navlist[i].addEventListener('click', showPage, false);
    }

    fetch('/api/gethtmltables')
	.then((response) => response.text())
        .then((text) => {
	    htmltables1.innerHTML=text;
	    htmltables2.innerHTML=text;
	});
    fetch('/api/getbenchmarkchart?BT=CPU+N-Queens')
	.then((response) => response.text())
        .then((text) => {
	    draw_chart(JSON.parse(text),'chart1');
	});    
//    fetch('/api/getbenchmarkchart?BT=SysBench+CPU+(Multi-thread)')
    fetch('/api/getcomparechart?CPU1=AMD+Ryzen+9+7950X&CPU2=AMD+Ryzen+9+5950X&CPU3=AMD+EPYC+9354P')
	.then((response) => response.text())
        .then((text) => {
	    draw_chart(JSON.parse(text),'chart2');
	});    
    fetch('/api/getbenchmarkchart?BT=Internal+Network+Speed')
	.then((response) => response.text())
        .then((text) => {
	    draw_chart(JSON.parse(text),'chart3');
	});
    showSlides();
  },false);


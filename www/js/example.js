//Just an example - using vanilla js

//Showing a chart.js graph
function draw_chart(graph,name,id) {
    var ctx = document.getElementById(name).getContext('2d');
    if(id) window.id.destroy();
    id = new Chart(ctx, graph);
};

//Fetching data - Can be improved...
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/gethtmltables')
	.then((response) => response.text())
        .then((text) => {
	    htmltables.innerHTML=text;
	});
    fetch('/api/getbenchmarkchart?BT=1')
	.then((response) => response.text())
        .then((text) => {
	    draw_chart(JSON.parse(text),"chart1",window.myChart1);
	});    
    fetch('/api/getbenchmarkchart?BT=2')
	.then((response) => response.text())
        .then((text) => {
	    draw_chart(JSON.parse(text),"chart2",window.myChart2);
	});    
    fetch('/api/getbenchmarkchart?BT=3')
	.then((response) => response.text())
        .then((text) => {
	    draw_chart(JSON.parse(text),"chart3",window.myChart3);
	});    
  },false);


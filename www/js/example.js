//Just an example - using vanilla js

//Showing a chart.js graph
function draw_chart(graph,name) {
    var ctx = document.getElementById(name).getContext('2d');
    if(window.hasOwnProperty(name)) window[name].destroy();
    document.getElementById(name).height=graph.height;
    window[name] = new Chart(ctx, graph);
};

//Fetching data - Can be improved...
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/gethtmltables')
	.then((response) => response.text())
        .then((text) => {
	    htmltables.innerHTML=text;
	});
    fetch('/api/getbenchmarkchart?BT=CPU+N-Queens')
	.then((response) => response.text())
        .then((text) => {
	    draw_chart(JSON.parse(text),'chart1');
	});    
    fetch('/api/getbenchmarkchart?BT=SysBench+CPU+(Multi-thread)')
	.then((response) => response.text())
        .then((text) => {
	    draw_chart(JSON.parse(text),'chart2');
	});    
    fetch('/api/getbenchmarkchart?BT=CPU+CryptoHash')
	.then((response) => response.text())
        .then((text) => {
	    draw_chart(JSON.parse(text),'chart3');
	});    
  },false);


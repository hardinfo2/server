//Just an example - using vanilla js

document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/gethtmltables')
	.then((response) => response.text())
        .then((text) => {
	    htmltables.innerHTML=text;
	});
  },false);

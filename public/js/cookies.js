console.log('test');
window.onload = (event) => {
	console.log('Hello world')
	let decodedCookie = decodeURIComponent(document.cookie);
	let ca = decodedCookie.split(';');
	let accepted = false;
	ca.forEach(cookie => {
		if(cookie == 'accept_cookies=true') accepted = true
	});

	if(!accepted) document.getElementById("cookie-banner").classList.remove('hidden');

};

function acceptCookies() {
	// Set cookie for 1 year
	document.cookie = "accept_cookies=true; max-age=31536000";

	// Hide banner
	document.getElementById("cookie-banner").style.display = "none";
}

function declineCookies() {
	document.getElementById("cookie-banner").style.display = "none";

}
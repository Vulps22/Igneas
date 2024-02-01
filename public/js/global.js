// Burger menus
document.addEventListener('DOMContentLoaded', function() {
	// open
	const burger = document.querySelectorAll('.navbar-burger');
	const menu = document.querySelectorAll('.navbar-menu');

	if (burger.length && menu.length) {
		for (var i = 0; i < burger.length; i++) {
			burger[i].addEventListener('click', function() {
				for (var j = 0; j < menu.length; j++) {
					menu[j].classList.toggle('hidden');
				}
			});
		}
	}

	// close
	const close = document.querySelectorAll('.navbar-close');
	const backdrop = document.querySelectorAll('.navbar-backdrop');

	if (close.length) {
		for (var i = 0; i < close.length; i++) {
			close[i].addEventListener('click', function() {
				for (var j = 0; j < menu.length; j++) {
					menu[j].classList.toggle('hidden');
				}
			});
		}
	}

	if (backdrop.length) {
		for (var i = 0; i < backdrop.length; i++) {
			backdrop[i].addEventListener('click', function() {
				for (var j = 0; j < menu.length; j++) {
					menu[j].classList.toggle('hidden');
				}
			});
		}
	}
});

function showLogin(){
	console.log(document.getElementById('login-view').classList)
	document.getElementById('login-view').classList.remove('max-md:hidden');
	document.getElementById('register-view').classList.add('max-md:hidden');
}

function showRegister(){
	console.log(document.getElementById('register-view').classList)

	document.getElementById('login-view').classList.add('max-md:hidden');
	document.getElementById('register-view').classList.remove('max-md:hidden');
}
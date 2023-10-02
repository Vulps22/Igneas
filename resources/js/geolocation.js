export default function initGeoLocation() {
	if (window.location.pathname === '/home') {
		if (!window.user) return;
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
				console.log("got position");
				var latitude = position.coords.latitude;
				var longitude = position.coords.longitude;
				console.log(latitude, longitude);
				// Send the latitude and longitude to the backend using AJAX
				var xhr = new XMLHttpRequest();
				xhr.open('POST', '/set_user_location');
				xhr.setRequestHeader('Content-Type', 'application/json');
				xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

				//handle response
				xhr.onload = function () {
					if (xhr.status === 401) window.location.href = '/login';
				};
				console.log(window.user);
				//send request
				xhr.send(JSON.stringify({
					user: window.user,
					latitude: latitude,
					longitude: longitude
				}));
			});
		}
	}
}
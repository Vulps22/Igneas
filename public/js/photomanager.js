
function handleImageUpload(event) {
	const position = event.target.dataset.position;
	const imageFile = event.target.files[0];
	const userId = window.user;
	const token = document.head.querySelector('meta[name="csrf-token"]')?.content;

	const formData = new FormData();
	formData.append('image', imageFile);
	formData.append('position', position);
	formData.append('user_id', userId);

	fetch('/user_profile_image', {
		method: 'POST',
		headers: {
			'X-CSRF-TOKEN': token
		},
		body: formData
	})
		.then(response => {
			return response.json();
		}).then(data => {
			if (data.url) {
				// Assuming 'position' is the position of the photo card
				updatePhotoCard(data.position, data.url);
			}
		})
		.catch(error => {
			console.error(error);
		})
}

function removeImage(event) {
	const position = event.currentTarget.dataset.position;
	const userId = window.user;
	const token = document.head.querySelector('meta[name="csrf-token"]')?.content;


	fetch(`/user_profile_image?user_id=${userId}&position=${position}`, {
		method: 'DELETE',
		headers: {
			'X-CSRF-TOKEN': token
		},
	})
		.then(response => {
			return response.json();
		}).then(data => {
			// Assuming 'position' is the position of the photo card
			removePhotoCard(data.position);
		})
		.catch(error => {
			console.error(error);
		})
}

function removePhotoCard(position) {
	const photoCardContainer = document.getElementById(`photo-card-${position}`);

	// Create outer div
	const div = document.createElement('div');
	div.className = 'relative w-full h-40 rounded-lg border-2 border-dashed border-neutral-600 flex items-center justify-center'

	// Create input
	const input = document.createElement('input');
	input.type = 'file';
	input.name = `image-${position}`;
	input.className = 'absolute inset-0 opacity-0';
	input.setAttribute('data-position', position);
	input.addEventListener('change', handleImageUpload);

	// Create inner div 
	const innerDiv = document.createElement('div');
	innerDiv.className = 'text-neutral-400 text-center';

	// Create p 
	const p = document.createElement('p');
	p.textContent = 'Upload profile images';

	// Append elements
	innerDiv.appendChild(addSvgElement());
	innerDiv.appendChild(p);
	div.appendChild(input);
	div.appendChild(innerDiv);

	// Clear and append outer div 
	photoCardContainer.textContent = '';
	photoCardContainer.appendChild(div);
}


function updatePhotoCard(position, url) {
	const photoCardContainer = document.getElementById(`photo-card-${position}`);
	if (photoCardContainer) {

		const newImage = renderImage(url);
		const del = delButton(position);

		// Replace the existing content with the new image
		photoCardContainer.innerHTML = '';
		photoCardContainer.appendChild(newImage);
		photoCardContainer.appendChild(del);
		del.appendChild(delSVGElement());
	}
}

function renderImage(url) {
	// Create a new image element and set its attributes
	const newImage = document.createElement("img");
	newImage.src = url;
	newImage.alt = "Profile image";
	newImage.className = "w-full h-40 object-cover rounded-lg";
	return newImage;
}

function delButton(position) {
	const delButton = document.createElement('button');
	delButton.type = "button";
	delButton.className = "absolute top-0 right-0 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none";
	delButton.setAttribute('data-position', position);
	delButton.addEventListener('click', removeImage);
	return delButton;
}

function delSVGElement() {
	// Create SVG element
	const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");

	// Set attributes
	svg.setAttribute("class", "h-4 w-4");
	svg.setAttribute("fill", "none");
	svg.setAttribute("viewBox", "0 0 24 24");
	svg.setAttribute("stroke", "currentColor");

	// Create path element
	const path = document.createElementNS("http://www.w3.org/2000/svg", "path");

	// Set path data  
	path.setAttribute("d", "M6 18L18 6M6 6l12 12");
	path.setAttribute("stroke-width", 2);
	path.setAttribute("stroke-linecap", "round");
	path.setAttribute("stroke-linejoin", "round");

	// Add path to SVG  
	svg.appendChild(path);

	return svg;
}

function addSvgElement() {
	// Create SVG element
	const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");

	// Set attributes
	svg.setAttribute("class", "mx-auto h-12 w-12 mb-3");
	svg.setAttribute("fill", "none");
	svg.setAttribute("viewBox", "0 0 24 24");
	svg.setAttribute("stroke", "currentColor");

	// Create path element
	const path = document.createElementNS("http://www.w3.org/2000/svg", "path");

	// Set path data  
	path.setAttribute("d", "M12 6v6m0 0v6m0-6h6m-6 0H6");
	path.setAttribute("stroke-width", 2);
	path.setAttribute("stroke-linecap", "round");
	path.setAttribute("stroke-linejoin", "round");

	// Add path to SVG  
	svg.appendChild(path);

	return svg;

}
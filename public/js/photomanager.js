
function handleImageUpload(event) {
	const position = event.target.dataset.position;
	const imageFile = event.target.files[0];
	const userId = window.user;
	const token = document.head.querySelector('meta[name="csrf-token"]')?.content;

	const formData = new FormData();
	formData.append('image', imageFile);
	formData.append('position', position);
	formData.append('user_id', userId);

	fetch('/save_user_profile_image', {
		method: 'POST',
		headers: {
			'X-CSRF-TOKEN': token
		},
		body: formData
	})
		.then(response => {
			console.log("success")
			console.log(response);
			return response.json();
		}).then(data => {
			if (data.url) {
				console.log(data)
				// Assuming 'position' is the position of the photo card
				updatePhotoCard(data.position, data.url);
			}
		})
		.catch(error => {
			console.error(error);
		})
}

function updatePhotoCard(position, url) {
	const photoCardContainer = document.getElementById(`photo-card-${position}`);
	console.log(photoCardContainer);
	if (photoCardContainer) {
		
		// Create a new image element and set its attributes
		const newImage = document.createElement("img");
		newImage.src = data.url;
		newImage.alt = "Profile image";
		newImage.className = "w-full h-40 object-cover rounded-lg";
		
		const delButton = document.createElement('button');
		delButton.type = "button";
		delButton.className = "absolute top-0 right-0 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none";
		delButton.onclick="removeImage(event)";
		
		// Replace the existing content with the new image
		photoCardContainer.innerHTML = '';
		photoCardContainer.appendChild(newImage);
		photoCardContainer.appendChild(delButton);
		delButton.appendChild(delSVGElement());
		console.log("Update complete")
	}
}
function delSVGElement(){
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
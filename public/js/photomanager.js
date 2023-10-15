
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
				const position = data.position;
				const photoCardContainer = document.getElementById(`photo-card-${position}`);
				console.log(photoCardContainer);
				if (photoCardContainer) {
					console.log("Container found")
					// Create a new image element and set its attributes
					const newImage = document.createElement("img");
					newImage.src = data.url;
					newImage.alt = "Profile image";
					newImage.className = "w-full h-40 object-cover rounded-lg";
					console.log("New Image created")
					// Replace the existing content with the new image
					photoCardContainer.innerHTML = '';
					photoCardContainer.appendChild(newImage);
					console.log("Update complete")
				}
			}
		})
		.catch(error => {
			console.error(error);
		})
}
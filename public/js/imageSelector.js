function selectImage(photo, event) {
  var selectedImage = document.getElementById('selected-image');
  var clickedImage = event.target;

  // Remove the "image-selected" class from any other images
  var selectedImages = document.querySelectorAll('.image-selected');
  for (var i = 0; i < selectedImages.length; i++) {
    selectedImages[i].classList.remove('image-selected');
  }

  // Add the "image-selected" class to the clicked image
  clickedImage.classList.add('image-selected');

  // Fade out the selected image
  selectedImage.style.transition = 'opacity 0.5s ease-in-out';
  selectedImage.style.opacity = 0;

  // Wait for the fade out to finish
  setTimeout(function() {
    // Set the new source of the selected image
    selectedImage.src = photo;

    // Fade in the selected image
    selectedImage.style.opacity = 1;
  }, 400);
}
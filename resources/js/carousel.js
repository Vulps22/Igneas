const images = document.querySelectorAll('.carousel img');
const middleIndex = Math.floor(images.length / 2);
let currentIndex = middleIndex;

images.forEach((image, index) => {
	image.addEventListener('click', () => {
		const distance = index - currentIndex;
		currentIndex = index;
		updateCarousel(distance);
	});
});

function updateCarousel(distance) {
	images.forEach((image, index) => {
		const offset = index - currentIndex;
		const translateX = distance > 0 ? offset - 1 : offset + 1;
		const scale = Math.pow(0.8, Math.abs(offset));
		image.style.transform = `translateX(${translateX * 25}%) scale(${scale})`;
	});
}
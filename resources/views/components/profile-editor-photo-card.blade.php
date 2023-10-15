<div id="photo-card-{{$position}}" class="relative">
	@if ($url)
	<img src="{{ $url }}" alt="Profile image" class="w-full h-40 object-cover rounded-lg">
	<button type="button" class="absolute top-0 right-0 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none" data-position="{{$position}}" onclick="removeImage(event)">
		<svg class="h-4 w-4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
			<path d="M6 18L18 6M6 6l12 12"></path>
		</svg>
	</button>
	@else
	<div class="relative w-full h-40 rounded-lg border-2 border-dashed border-neutral-600 flex items-center justify-center">
		<input type="file" name="image-{{$position}}" class="absolute inset-0 opacity-0" data-position="{{ $position }}" onchange="handleImageUpload(event)">
		<div class="text-neutral-400 text-center">
			<svg class="mx-auto h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
			</svg>
			<p class="text-sm">Upload profile images</p>
		</div>
	</div>
	@endif
</div>
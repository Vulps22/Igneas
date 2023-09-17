<div class="h-full mx-3">
	@foreach($messages as $message)
		<x-message :message="$message" />
	@endforeach
</div>
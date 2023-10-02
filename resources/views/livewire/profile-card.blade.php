<a href="/profile/{{ $user->id }}">
	<!--use tailwindCSS to create a card-->
	@if($using_default)
	<div class="flex lg:h-60 rounded border-2 border-white" style="background-image: url('{{ $picture_url }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
	@else
	<div class="flex lg:h-60 rounded border-2 border-white" style="background-image: url('{{ $picture_url }}'); background-size: cover;">
	@endif
		<div class="flex bg-gradient-to-t from-gray-900 to-transparent w-full h-full">
			<div class="self-end w-full h-1/4 pl-3">
				<h3><span class="w-2/3">{{ $profile->display_name }}</span> | {{ $profile->age() }}</h3>
				<h4> {{ $user->distance(auth()->user()->location) }} away</h4>
			</div>
		</div>
	</div>
</a>
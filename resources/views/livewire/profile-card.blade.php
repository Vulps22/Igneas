<a href="/profile/{{ $user->id }}">
	<!--use tailwindCSS to create a card-->
	@if($using_default)
	<div class="flex h-40 border-1 border-black md:h-60 md:rounded md:border-2 md:border-white" style="background-image: url('{{ $picture_url }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
	@else
	<div class="flex h-40 rounded border-1 border-black md:h-60 md:border-2 border-white" style="background-image: url('{{ $picture_url }}'); background-size: cover;">
	@endif
		<div class="flex bg-gradient-to-t from-gray-900 to-transparent w-full h-full">
			<div class="self-end w-full h-1/4 pl-3">
				<h3 class="text-small md:text-xl"><span class="w-2/3">{{ $profile->display_name }}</span> | {{ $profile->age() }}</h3>
				<h4 class="text-xs md:text-base"> {{ $user->distance(auth()->user()->location) }} away</h4>
			</div>
		</div>
	</div>
</a>
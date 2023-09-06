<!--use tailwindCSS to create a card-->

<div class="flex lg:h-60 rounded border-2 border-white " style="background-image: url('{{ $picture_url }}'); background-size: cover;">

	<div class="self-end w-full h-1/3 pl-3">
		<h3><span class="w-2/3">{{ $profile->display_name }}</span> | {{ $profile->age() }}</h3>
	</div>

</div>
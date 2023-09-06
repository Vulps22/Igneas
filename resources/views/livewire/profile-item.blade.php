<div>
	@if($value != '')
	<div class="flex flex-row">
		@if($title)
		<div class="w-1/4">
		@else
		<div class="w-10">
		@endif
			<i class="fa-solid fa-{{ $icon }}"></i> <span class="font-bold pr-4 text-neutral-400">{{$title}}</span>
		</div>
		<div class="text-left grow"> {{$value}}</div>
	</div>
	@endif
</div>
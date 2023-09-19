<div class="flex items-center justify-between p-4 hover:bg-gray-100 cursor-pointer conversation-list-item">
	<div class="flex items-center">
		<img src="{{ $user->primaryImageURL() }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full mr-4">
		<div>
			<h3 class="font-bold">{{ $user->display_name }}  {{ $user->age() }}</h3>
			@if ($latestMessage)
			<p id="message{{$conversationId}}" class="text-gray-600">{{ $latestMessage }}</p>
			@endif
		</div>
	</div>
</div>
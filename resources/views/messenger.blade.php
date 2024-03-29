@extends('layouts.app')
@push('scripts')
<script type="text/javascript" src="/js/messenger.js"></script>
@endpush
@section('content')
<div class="flex h-full">
	<div id="conversation-list" class="md:w-1/4 md:border-r">
		@if(count($conversations) == 0)
		<p id="noConvo" class="ml-52 pt-10">You have no conversations.</p>
		@else
		@foreach ($conversations as $conversation)
		<div id="conversation{{ $conversation['id'] }}" name="conversation-list-item" class="block hover:bg-gray-100 conversation" data-conversation-id="{{ $conversation['id'] }}" onclick="selectConversation('<?= $conversation['id']; ?>')">
			<x-conversation-list-item :conversation-id="$conversation['id']" :user="$conversation['user']" :latest-message="$conversation['latest'] ? $conversation['latest']->text : ''" />
		</div>
		@endforeach
		@endif
	</div>
	<div id="convo-wrapper" class="md:w-3/4 p-4">
		<x-conversation :conversationId="$selectedConversation" />

	</div>
</div>
@endsection
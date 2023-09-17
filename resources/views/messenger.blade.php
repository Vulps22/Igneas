@extends('layouts.app')
@push('scripts')
<script type="text/javascript" src="/js/messenger.js"></script>
@endpush
@section('content')
<div class="flex h-full">
	<div class="w-1/4 border-r">
		@if(count($conversations) == 0)
		<p class="ml-52 pt-10">You have no conversations.</p>
		@else
		@foreach ($conversations as $conversation)
		<a href="/messenger/{{$conversation['user']->id }}" class="block hover:bg-gray-100">
			<x-conversation-list-item :conversation-id="$conversation['id']" :user="$conversation['user']" :latest-message="$conversation['latest']->text" />
		</a>
		@endforeach
		@endif
	</div>
	<div class="w-3/4 p-4">
		@if ($selectedConversation)
		<x-conversation :conversation-id="$selectedConversation->id" />
		@else
		<p>Select a conversation to view messages.</p>
		@endif
	</div>
</div>
@endsection
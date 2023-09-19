<div id="conversation" class="h-full w-full flex flex-col justify-end" data-conversation-id="{{$conversationId ?? ''}}" hidden>
	<div class="flex-1 message-list overflow-y-auto hooky-scrollbar px-3">

	</div>
	<div class="flex-none p-4">
		<form id="message-form" onsubmit="handleSubmit(event)">
			<div class="flex items-center">
				<input type="text" id="message-input" class="flex-1 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white pl-5 py-2 mr-2" placeholder="Type a message...">
				<button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4"><i class="fa-regular fa-circle-right text-2xl"> </i></button>
			</div>
		</form>
	</div>
</div>
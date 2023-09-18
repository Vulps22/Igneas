<div class="h-full w-full flex flex-col justify-end">
	<div class="flex-1 message-list">

	</div>
	<div class="flex-none p-4">
		<form id="message-form" onsubmit="handleSubmit(event)" data-conversation-id="{{$conversation->id}}" data-sender-id="{{$userId}}">
			<div class="flex items-center">
				<input type="text" id="message-input" class="flex-1 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white pl-5 py-2 mr-2" placeholder="Type a message...">
				<button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4"><i class="fa-regular fa-circle-right text-2xl"> </i></button>
			</div>
		</form>
	</div>
</div>

<component :is="'script'">
	

</component>

// Send a new message when the form is submitted
function handleSubmit(event) {
	event.preventDefault();

	var conversationId = event.target.getAttribute('data-conversation-id');
	var senderId = event.target.getAttribute('data-sender-id');
	var text = document.getElementById('message-input').value;
	if(!text) return;
	sendMessage(conversationId, senderId, text);
	document.getElementById('message-input').value = '';
}

// Send a new message to the server
function sendMessage(conversationId, senderId, text) {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		url: '/messenger/send',
		type: 'POST',
		data: {
			conversation_id: conversationId,
			sender_id: senderId,
			text: text,
		},
		success: function (response) {
			// Update the message list with the new message
			updateMessageList(response.messages);
		},
		error: function (xhr, status, error) {
			console.error(error);
		},
	});
}

// Update the message list with the new messages
function updateMessageList(messages) {
	var messageList = $('.message-list');
	messageList.empty();
	messages.forEach(function (message) {
		var messageElement = $('<div>').addClass('message').text(message.text);
		messageList.append(messageElement);
	});
}

//when doument is ready get messages

$(document).ready(function () {
	getMessages();
});

function getMessages() {
	var conversationId = document.getElementById('message-form').getAttribute('data-conversation-id');
	var senderId = document.getElementById('message-form').getAttribute('data-sender-id');

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		url: '/messenger/' + conversationId + '/messages',
		type: 'GET',
		success: function (response) {
			// Update the message list with the new message
			updateMessageList(response.messages, senderId);
		},
		error: function (xhr, status, error) {
			console.error(error);
		},
	});
}

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
			updateMessageList(response.messages, senderId);
		},
		error: function (xhr, status, error) {
			console.error(error);
		},
	});
}

function updateMessageList(messages, senderId) {
	var messageList = $('.message-list');
	messageList.empty();
	messages.forEach(function (message) {
		var messageElement;
		if (message.sender_id == senderId) {
			messageElement = userMessage(message);
		} else {
			messageElement = recipientMessage(message);
		}
		messageList.append(messageElement);
	});
}


function userMessage(message) {
	var messageBubble = document.createElement('div');
	messageBubble.classList.add('flex', 'justify-end');
	var innerBubble = document.createElement('div');
	innerBubble.classList.add('bg-blue-500', 'text-white', 'rounded-lg', 'px-4', 'py-2', 'max-w-xs', 'break-words', 'my-2');
	innerBubble.innerHTML = message.text;
	messageBubble.appendChild(innerBubble);
	return messageBubble;
}

function recipientMessage(message) {
	var messageBubble = document.createElement('div');
	messageBubble.classList.add('flex', 'justify-start');
	var innerBubble = document.createElement('div');
	innerBubble.classList.add('text-white', 'rounded-lg', 'px-4', 'py-2', 'max-w-xs', 'break-words', 'my-2');
	innerBubble.style.backgroundColor = 'rgb(127 29 29)';
	innerBubble.innerHTML = message.text;
	messageBubble.appendChild(innerBubble);
	return messageBubble;
}
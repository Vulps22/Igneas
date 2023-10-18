
var conversationOpen = false;

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
			setMessages(response.messages, senderId);
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
	if (!text) return;
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

function updateMessageList(message, senderId) {
	var messageList = $('.message-list');
	var messageElement;
	if (message.sender_id == senderId) {
		messageElement = userMessage(message);
	} else {
		messageElement = recipientMessage(message);
	}
	messageList.append(messageElement);
	//scroll to bottom
	messageElement.scrollIntoView();
}

function setMessages(messages, senderId) {
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
		messageElement.scrollIntoView();
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

function conversationItem(conversation) {
	if (!conversation) throw new Error('Conversation is Was Null or Undefined');
	// Create a new conversation item element
	const conversationItem = document.createElement('div');
	conversationItem.classList.add('flex', 'items-center', 'justify-between', 'p-4', 'hover:bg-gray-100', 'cursor-pointer', 'conversation-list-item');
	conversationItem.id = 'conversation' + conversation.id;
	conversationItem.setName('data-conversation-id', conversation.id);
	conversationItem.addEventListener('click', () => {
		selectConversation(conversation.id);
	});

	// Add the user image and display name to the conversation item
	const userImage = document.createElement('img');
	userImage.src = conversation.user.image;
	userImage.alt = conversation.user.name;
	userImage.classList.add('w-12', 'h-12', 'rounded-full', 'mr-4');
	const displayName = document.createElement('h3');
	displayName.innerText = conversation.user.name + ' ' + conversation.user.age;
	displayName.classList.add('font-bold');
	const userContainer = document.createElement('div');
	userContainer.classList.add('flex', 'items-center');
	const textContainer = document.createElement('div');
	textContainer.appendChild(displayName);
	userContainer.appendChild(userImage);
	userContainer.appendChild(textContainer);
	conversationItem.appendChild(userContainer);

	// Add the latest message to the conversation item
	if (conversation.latest) {
		const latestMessage = document.createElement('p');
		latestMessage.innerText = conversation.latest;
		latestMessage.classList.add('text-gray-600');
		latestMessage.id = 'message' + conversation.id;
		textContainer.appendChild(latestMessage);
	}

	return conversationItem;
}

function selectConversation(id) {

	var conversation = document.getElementById('conversation' + id);
	conversation.classList.add('bg-neutral-800');
	//remove bg-gray-300 from other conversations
	var conversations = document.getElementsByClassName('conversation');
	for (var i = 0; i < conversations.length; i++) {
		if (conversations[i].id != conversation.id) {
			conversations[i].classList.remove('bg-neutral-800');
		}
		conversationOpen = true;
	}

	//set conversation id on form
	var messageForm = document.getElementById('message-form');
	messageForm.setAttribute('data-conversation-id', id);

	//set sender id on form
	messageForm.setAttribute('data-sender-id', window.user);

	//unhide the conversation component
	var conversationComponent = document.getElementById('conversation');
	conversationComponent.removeAttribute('hidden');

	//get messages for conversation
	getMessages();
}

function initMessenger() {
	console.log('Initializing messenger');
	// Your code that uses the Echo object goes here
	window.Echo.private(`conversation.user.${window.user}`)
		.listen('MessageSent', (event) => {
			console.log(event);

			if (conversationOpen && event.message.conversation_id == document.getElementById('message-form').getAttribute('data-conversation-id')) {
				updateMessageList(event.message, window.user);
			}

			updateConversationList(event.message)
				.then(() => {
					var noConvoElement = document.getElementById('noConvo')
					if (noConvoElement) noConvoElement.setAttribute('hidden', true);
					console.log(event.message)
					//update conversation list with latest message
					var message = document.getElementById('message' + event.message.conversation_id);
					message.innerHTML = event.message.text;
				})
				.catch((error) => {
					console.error(error);
				});
		});
}


function updateConversationList(message) {

	console.log("Update")
	return new Promise((resolve, reject) => {
		//get an array of all the conversation id's in the conversation list
		let conversationExists = false;
		conversation = null;
		var conversations = document.getElementsByName('conversation-list-item');
		for (var i = 0; i < conversations.length; i++) {
			console.log(conversations[i].getAttribute('data-conversation-id'))
			if (conversations[i].getAttribute('data-conversation-id') == message.conversation_id) {
				conversationExists = true;
				conversation = conversations[i];
			}
		}

		//if the conversation id is not in the conversation list, add it
		if (!conversationExists) {
			console.log("Conversation Not Found in List");
			//get the conversation from the server
			$.ajax({
				url: '/messenger/find/' + message.conversation_id,
				type: 'GET',
				success: function (response) {
					console.log(response)
					// Add the conversation to the conversation list
					var conversationList = document.getElementById('conversation-list');
					conversationList.prepend(conversationItem(response));
					resolve();
				},
				error: function (xhr, status, error) {
					reject(error);
				},
			});

		} else { //the conversation is in the list; Move it to the top
			if (!conversation) resolve();

			conversation.remove();
			var conversationList = document.getElementById('conversation-list');
			conversationList.prepend(conversation);

			resolve();
		}
	});

}

function loadConversation() {
	// Get the conversation ID from the conversation element
	const conversationId = document.getElementById('conversation').getAttribute('data-conversation-id');

	// If the conversation ID is present, load the conversation
	if (conversationId) {
		// Set the conversation ID on the message form
		selectConversation(conversationId);
	}
}

window.addEventListener('load', () => {
	loadConversation();
});

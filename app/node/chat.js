/**
 * Required Modules
 */
var fs 			= require("fs");
//var underscore 	= require("underscore");
var io 			= require("socket.io");
var http		= require("http");

/**
 * Configure the application
 */
try {
	if (!fs.existsSync('chatConfig.json')) {
		throw "Configuration file does not exist.";
	}

	var config = JSON.parse( fs.readFileSync('chatConfig.json') );

	if (!('port' in config) || !('apiEndPoint' in config)) {
		throw "Your configuration must have atleast a port and apiEndPoint";
	}
} catch ( error ) {
	console.error("Configuration file error: " + error);
	process.exit(1);
}

/**
 * Main Application
 */
var chat = io.listen(config.port);
// chat.set('transports', ['xhr-polling', 'jsonp-polling']);

function removeItem(array, item){
    for(var i in array){
        if(array[i]==item){
            array.splice(i,1);
            break;
            }
    }
}

var messages = new Array();
var userList = new Array();

var room = new Array();

chat.sockets.on('connection', function(client) {

	function sendMessage(type, message) {
		client.get('clientInfo', function (error, clientInformation) {

			if (type == 'connectionMessage' || type == 'message') {
				// Only log connection and chat messages.

				if (room[clientInformation.room]['messages'].length >= config.backLog) {
					room[clientInformation.room]['messages'].shift();
				}

				room[clientInformation.room]['messages'].push(message);
			}

			if (type == 'backFillChatLog') {
				// Use client emit to just send to the requester.
				client.emit(type, message);
			} else {
				// Use chat socket emit to send to all user in a room.
				chat.sockets.in(clientInformation.room).emit(type, message);
			}
		});
	}


	client.on('subscribe', function(clientInformation) {
		// If the room does not exist in memory fill create the data tables for it.
		if ( typeof chat.sockets.manager.rooms['/' + clientInformation.room] ==  'undefined') {
			room[clientInformation.room] = new Array();
			room[clientInformation.room]['userList'] = new Array();

			// backfill chat logs 30 lines

			room[clientInformation.room]['messages'] = new Array();
		}

		// Join the chat room
		client.join(clientInformation.room);

		// Save client data
		client.set('clientInfo', clientInformation);

		// Add client to user list
		room[clientInformation.room]['userList'].push(clientInformation.username);

		// Broadcast new client list to room
		sendMessage('userListUpdate', room[clientInformation.room]['userList']);

		// Back fill the chat log for the newly connected user
		sendMessage('backFillChatLog', room[clientInformation.room]['messages']);

		if (config.connectionMessage) {
			sendMessage('connectionMessage', '<small class="muted">' + clientInformation.username + ' has joined the chatroom.</small> <br />');
		}
	});

	client.on('message', function (message) {

		if (room[message.room]['messages'].length >= config.backLog) {
			room[message.room]['messages'].shift();
		}

		// Add the chat message to the in memory chat log
		room[message.room]['messages'].push(message.text);

		// Broadcast the message to the room
		chat.sockets.in(message.room).emit('message', message.text);
	});

	client.on('disconnect', function() {
		client.get('clientInfo', function (error, clientInformation) {
			// Check for client information before showing disconnect messages
			if (clientInformation != null) {
				// Remove client from user list
				removeItem(room[clientInformation.room]['userList'], clientInformation.username);

				// Broadcast new client list to room
				sendMessage('userListUpdate', room[clientInformation.room]['userList']);

				if (config.connectionMessage) {
					sendMessage('connectionMessage', '<small class="muted">' + clientInformation.username + ' has left the chatroom.</small> <br />');
				}

				if ( typeof chat.sockets.manager.rooms['/' + clientInformation.room] ==  'undefined') {
					// Clear room from memory when no one is left in it.
					room[clientInformation.room] = new Array();
				}
			}
		});
	});
});

// console.log(config);


	// client.on('username', function (username) {
	// 	client.set('nickname', username);
		
	// 	userList.push(username);

	// 	chat.sockets.emit('userListUpdate', userList);

	// 	// backfill clients chat

	// 	if (config.connectionMessage) {
	// 		chat.sockets.emit('connectionMessage', {'username': username, 'action':'join'});
	// 	}
	// });


	// client.on('message', function (message) {

	// 	chat.sockets.emit('message', message);
	// })


	// client.on('disconnect', function() {
	// 	client.get('nickname', function (error, name) {
	// 		if (name) {
	// 			removeItem(userList, name);

	// 			chat.sockets.emit('userListUpdate', userList);

	// 			if (config.connectionMessage) {
	// 				chat.sockets.emit('connectionMessage', {'username': name, 'action':'leave'});
	// 			}
	// 		}
	// 	});
	// });

// // Need to move this to a function file
// Array.prototype.inject = function ( element ) {
// 	if (this.length >= config.backLog) {
// 		this.shift();
// 	}

// 	this.push(element);
// }


// chat.sockets.on('connection', function(client) {

//  client.on('action', function (data) {
//     console.log('here we are in action event and data is: ' + data);
//   });

// 	client.on('clientData', function (user) {
// 		client.set('data', user);
// 		console.log(user);
// 	});

// 	// messages.inject({'username':'Server', 'message':'New User Connected'});
// 	// client.broadcast.emit('msg', {'username':'Server', 'message':'New User Connected'});

// 	// if (config.debug) {
// 	// 	console.log("New Connection: ", client.id);
// 	// }

// 	// client.emit("init", JSON.stringify(messages));

// 	client.on('msg', function(msg) {

// 		// if (config.debug) {
// 			console.log("Message: " + msg);
// 		// }

// 		// var message = JSON.parse(msg);
// 		// message.username = client.get('data').username;
// 		// messages.inject(message);

// 		// client.broadcast.emit('msg', msg);
// 	});

// 	client.on('disconnect', function() {
// 		// var clientData = client.get('data');

// 		// if (config.sendConnectionMessage) {
// 		// 	client.broadcast.emit('sys', clientData.username + 'Disconnected from chat')
// 		// }

// 		// if (config.debug) {
// 			console.log("Disconnected: ", client.id);
// 		// }
// 	});
// });
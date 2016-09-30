# RockstarCode StreamLine (new and improved)

## A simple alternative to 3rd party push services and realtime libraries.


###Quick Background
I needed data sync capabilities in the likes of firebase and other nosql services without
the additional data storage facilities.

The goal was to create a fast message broadcast system that would be push to browser and not
polling or pinging for server status on interval.

It allows for User -> Users and Server -> User(s) communication streams
Allowing features such as

	* Message Notifications
	* Chat
	* Status Notifications
	* Dynamic Content Push

## Features

	* Javascript Allows for multiple events on same channel
	* Auto reconnects on dropped connections
	* No need for external services


#How It Works
There is a server side and client side component,
The client side creates a stream channel to the target subscription via a GET
request and waits for data to stream down the pipe. The javascript is parsed
as recieved from the stream and therefore the channel remains open until a connection drop.
Should the connection drop occur, the JS library will automatically reconnect.

The server side is basically a reciever which maintains an open connection to the stream
while pushing out buffers from data it recieves. The result is a on demand data stream
being pushed to the client side.

## How to Use
So far the installation is really based on how you'd like to use it.
I've made the library to be integrated into a already existing application, however
you can set it up to be standalone.

# Requires
	* Redis
	* if PHP serverside, redis PECL driver


# Setup

### Stand Alone
clone the repo, set up a server with docroot to the repo
You can use the .htaccess to modify which server you'd like to be resposible for handling responses.

Then include the javascript contained in client into the client-side and
use the .subscribe(channel,handling function) or .publish(channel, data, handling function) methods
to push data to different clients.

I've included a index.html which assumes the application is loaded under http://localhost/streamline
to give it a try, load http://localhost/streamline/index.html into different browsers and change
the value of the text box [ hit enter to submit ] messages should appear in other browsers.


### With built app
Your app must support the client and server side components,
Create path(s) to your app which will invoke the desired server side listener
Use those paths in your javascript to subscribe to channels.

The javascript library was coded minimally to be stand alone, however you can employ which
ever technique you'd like to communicate with your subscription channels.


#NODEJS
in package.json
add  "streamline":"82rules/streamline"
npm instlal

### Testing Channels
I use redis-cli to publish to my test channels to make sure my server side broadcasts
are pushed to the client successfully.

Client side end to end is easy to replicate with multiple browsers and private session windows.


### Why Redis?
I am taking advantage of redis's built in PubSub mechanism. However, you can easily
exchange redis for NodeJS or any other broadcast system as long as it persists connection
between message blocks.

#Contributing
Please feel free to contribute additional server listeners and javascript.

If you are going to create a server side listener and publisher, share config with config.json

I'm really happy with how this solution turned out.
Questions, comments, just ask.

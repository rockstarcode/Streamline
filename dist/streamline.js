window['StreamLine'] = window['StreamLine'] ? window['StreamLine'] : {};
(function(StreamLine){

    StreamLine = StreamLine ? StreamLine : {};


    StreamLine.maxReconnects = 10;
    StreamLine.channelProperties = {};


    StreamLine.events = function(stream){
        console.log(stream);
    }

    StreamLine.request = function() {
        return window.XMLHttpRequest ? new XMLHttpRequest()  : new XMLHttpRequest();
    }

    StreamLine.subscribe = function(channel, handle){

        /// timeout and reconnects per channel to avoid race or conflict
        if (!StreamLine.channelProperties[channel]) {

            StreamLine.channelProperties[channel] = {};

            StreamLine.channelProperties[channel].reconnectAttempts=StreamLine.maxReconnects;
            StreamLine.channelProperties[channel].timeout;
            StreamLine.channelProperties[channel].handlers = [handle];

        }
        else {
            /// allows multiple functions to subscribe to same channel (multi event updates)
            if (StreamLine.channelProperties[channel].handlers.indexOf(handle) == -1) {

                StreamLine.channelProperties[channel].handlers.push(handle);
                return; /// we dont want to resent connection and handling if another function already has

            }
        }

        var filterLoaded = '';  /// used to filter data paseed thru progress to avoid replicated data imports

        setTimeout(function(){


            var xhr = StreamLine.request();

            xhr.open("GET", channel, true)

            xhr.onprogress = function () {
                var command =  xhr.responseText.toString().replace(filterLoaded,'');

                filterLoaded = xhr.responseText.toString();

                for(var i =0; i<StreamLine.channelProperties[channel].handlers.length; i++){

                    /// push data to every handle that susbscribed to the channel
                    StreamLine.channelProperties[channel].handlers[i](command);

                }

            }

            xhr.onreadystatechange = function() { //Call a function when the state changes.
                if(xhr.readyState == 4) {
                    /// connection drop, reconnecting

                    /// if timeout is available, a reconnection attempt was made but not successful, so clear it
                    if ( StreamLine.channelProperties[channel].timeout ){
                        clearTimeout( StreamLine.channelProperties[channel].timeout );
                    }

                    /// draw from reconnect attempts to avoid forever attempts to reconnect on a non responsive channel
                    StreamLine.channelProperties[channel].reconnectAttempts = StreamLine.channelProperties[channel].reconnectAttempts - 1;

                    if (StreamLine.channelProperties[channel].reconnectAttempts > 0 ) {

                        console.log("Attempting to reconnect to channel " + channel + " attempts left: " + StreamLine.channelProperties[channel].reconnectAttempts );

                        StreamLine.subscribe(channel,handle);

                        filterLoaded = '';

                        /// if a reconnection was made, timeout will reset number of reconnection attempts available
                        StreamLine.channelProperties[channel].timeout = setTimeout(function(){
                            StreamLine.channelProperties[channel].reconnectAttempts = StreamLine.maxReconnects;
                        }, 2000);
                    }
                    else {
                        console.log("Maxed out on reconnect attempts on channel" + channel);
                    }
                }
            }

            xhr.send();

        },1000); // setTimeout to avoid forever load of browser


    }

    StreamLine.publish = function(channel,data, handle){

        var xhr = StreamLine.request();
        xhr.open("POST", channel, true)

        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {//Call a function when the state changes.
            if(xhr.readyState == 4 && xhr.status == 200) {
                handle(xhr.responseText);
            }
        }

        xhr.send(data);
    }



})(window['StreamLine']);
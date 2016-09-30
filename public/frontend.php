<html>
<head>
    <script src="http://larastream.app/dist/streamline.js"></script>
    <script>

         window.onload = function(){
             window.StreamLine.subscribe("http://larastream.app/subscribe/1000", function(data){
                document.getElementById("chat").innerHTML += (data + "<br>");
             });
         };
        function sendMessage(event, item){
            if (event.keyCode == 13) {
                console.log(item.value);
                StreamLine.publish('http://larastream.app/publish/1000',item.value,console.log)
                item.value = '';
            }
        }
    </script>
</head>
<body>
<input type="text" style="width:100%" placeholder="Send Message [press enter]" onkeyup="sendMessage(event, this)">
<p>Messages: </p>
<div id="chat"></div>
</body>
</html>
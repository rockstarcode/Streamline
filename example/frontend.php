<html>
<head>
    <script src="//<?PHP echo $base; ?>/streamline.js"></script>
    <script>

         window.onload = function(){
             window.StreamLine.subscribe("//<?PHP echo $base; ?>/subscribe/test", function(json){
                 var data = JSON.parse(json)
                 if (data.user){
                     var template = document.getElementById("template").innerHTML;
                     template = template.replace('{user}',data.user);
                     template = template.replace('{message}',data.message);
                     document.getElementById("chat").innerHTML += template;

                 }
             });
         };
        function sendMessage(event, item){
            if (event.keyCode == 13) {
                var data = [];
                data.push('user='+document.getElementById('user').value);
                data.push('message='+item.value);
                StreamLine.publish('//<?PHP echo $base; ?>/publish/test',data.join("&"),console.log)
                item.value = '';
            }
        }
    </script>
</head>
<body>
    <input type="text" style="width:25%" placeholder="Name" id="user">
    <br><br>
    <input type="text" style="width:100%" placeholder="Send Message [press enter]" onkeyup="sendMessage(event, this)">
    <p>Messages: </p>
    <div id="chat"></div>
<script type="text/html" id="template">
    <div>
    <strong>{user} <i>says:</i></strong><br>
        {message}
        <hr/>
    </div>
</script>
</body>
</html>
jQuery(function($){
  if (!("WebSocket" in window)) {
    alert("Your browser does not support web sockets");
  }else{
    setup();
  }
  function setup(){
   
    // Note: You have to change the host var 
    // if your client runs on a different machine than the websocket server
    
    var host = "ws://192.168.1.88:9393/ws";
    window.wsocket = new WebSocket(host);

  }

});

function sendMessage(msg){
    // Wait until the state of the socket is not ready and send the message when it is...
    waitForSocketConnection(wsocket, function(){
        console.log("message sent!!!");
        wsocket.send(msg);
    });
}

// Make the function wait until the connection is made...
function waitForSocketConnection(socket, callback){
    setTimeout(
        function () {
            if (socket.readyState === 1) {
                console.log("Connection is made")
                if(callback != null){
                    callback();
                }
                return;

            } else {
                console.log("wait for connection...")
                waitForSocketConnection(socket, callback);
            }

        }, 5); // wait 5 milisecond for the connection...
}

// Load the TCP Library
net = require('net');
var port = 2323;
 
// Keep track of the chat clients
var clients = [];

net.createServer(function (socket) {
  socket.name = socket.remoteAddress //+ ":" + socket.remotePort 
  clients.push(socket);
  process.stdout.write("Connected: "+socket.name + "\n");
  socket.write("Hello " + socket.name + "\n");

  process.on('uncaughtException', function (err) {
    console.error(err.stack);
    console.log("Node NOT Exiting...");
  });

  socket.on('data', function (data) {
    socket.write("Let me check that real quick..\n");

    setTimeout(function(){
      var length = (data.length-2);
      if(length<=80 || length>=400)
        socket.write("Sorry.. no password for you.. restart the level and try again..\n");
      else
        socket.write("GOT IT!\n==================\nPassword is: "+makeid(Math.round(length/3))+"\n==================\n");
      socket.end();
    },randbetween(5000,10000));

    setTimeout(function(){
      socket.write("still checking..\n");
    },randbetween(1500,4500));
    
  });
 
  socket.on('end', function () {
    process.stdout.write("Disconnected: "+socket.name + "\n\n");
    clients.splice(clients.indexOf(socket), 1);
  });

  socket.on("error", function(err){
    console.log("Error ")
    console.log(err.stack)
  });

  function makeid(count)
  {
      var text = "";
      var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

      for( var i=0; i < count; i++ )
          text += possible.charAt(Math.floor(Math.random() * possible.length));

      return text;
  }

  function randbetween(min,max)
  {
    return Math.floor(Math.random() * max) + min
  }
 
}).listen(port);
 
// Put a friendly message on the terminal of the server.
console.log("Server running at port "+port+"\n");
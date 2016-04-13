// Load the TCP Library
net = require('net');
var port = 2727;
 
// Keep track of the chat clients
var newid = 0;
var clients = [];
var answers = [];
var answerscount = [];

net.createServer(function (socket) {
  socket.name = socket.remoteAddress //+ ":" + socket.remotePort 
  socket.id = newid++;//socket.remoteAddress + ":" + socket.remotePort ;
  answerscount[socket.id] = 0;
  clients.push(socket);
  process.stdout.write("Connected: "+socket.name + "ID: "+socket.id+"\n");
  socket.write("Hello! Let's play a game..\nI'll give you simple math problems to solve and you have to submit the answers.. fast.. faster than a human can type.. 4-10 answers in 4 seconds\n\nTo start the game send me: GO\n");

  process.on('uncaughtException', function (err) {
    console.error(err.stack);
    console.log("Node NOT Exiting...");
    socket.write("sry, gotta go..\n");
    socket.end();
  });

  setTimeout(function(){
        socket.end();
  },50000);

  socket.on('data', function (data) {
    data = String(data).replace(/(\r\n|\n|\r)/gm,"");

    console.log(data);

    if(data=='GO' && answers[socket.id]===undefined)
    {
      setTimeout(function(){
        socket.write("GAME OVER: too slow\n");
        socket.end();
      },4000);

      var n1 = randbetween(1,500)
      var n2 = randbetween(1,500)
      answers[socket.id] = n1*n2;
      socket.write(n1+"*"+n2+"=?\n");
    }
    else if(data==answers[socket.id])
    {
      if(answerscount[socket.id]>=randbetween(4,10))
      {
        var pw = makeid(32)
        var fs = require('fs');
        fs.writeFile("../data/tmp/"+pw, "OK", function(err) {
          fs.chmodSync("../data/tmp/"+pw, 0777);
            if(err) {
                return console.log(err);
            }
        });

        
        socket.write("EPIC WIN!\n==================\nYour password for level 27 is: "+pw+"\n==================\n");
        socket.end();
      }
      else
      { 
        var n1 = randbetween(1,500)
        var n2 = randbetween(1,500)
        answers[socket.id] = n1*n2;
        socket.write("Correct!\n");
        socket.write(n1+"*"+n2+"=?\n");
        
        answerscount[socket.id]++;
      }
    }
    else
    {
      socket.write("Game over: Wrong answer\n\n");
      socket.end();
    }

  });
 
  socket.on('end', function () {
    process.stdout.write("Disconnected: "+socket.name + " "+ socket.id+"\n\n");
    clients.splice(clients.indexOf(socket), 1);
  });

  socket.on("error", function(err){
    //console.log("Error ")
    //console.log(err.stack)
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
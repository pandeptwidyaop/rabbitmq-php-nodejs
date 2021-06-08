let amqp = require("amqplib/callback_api")

amqp.connect("amqp://localhost", function(error0, connection) {
    if(error0) {
        throw new error0;
    }

    connection.createChannel(function(err1, channel) {
        if(err1) {
            throw err1;
        }

        channel.consume('hello', function(msg) {
            console.log("[x] Received "+msg.content.toString());
        })
    })
})
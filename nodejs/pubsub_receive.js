let amqp = require("amqplib/callback_api")

amqp.connect("amqp://localhost",function(error0, connection) {
    if(error0) throw error0

    connection.createChannel(function(error1, channel){
        if(error1) throw error1
        channel.assertExchange("GLOBAL_X",'topic',{
            durable: false,
            autoDelete: false,
        })

        let queue = 'app04'

        channel.assertQueue(queue, {exclusive: false, durable: false}, function(error2){
            if (error2) throw error2

           channel.bindQueue(queue,'GLOBAL_X','user.created')

           console.log("Ready To Go")

            channel.consume(queue, function(msg){
                console.log(`[${msg.fields.routingKey}] ${msg.content.toString()}`)
                channel.ack(msg)
            })
        })        
    })
})
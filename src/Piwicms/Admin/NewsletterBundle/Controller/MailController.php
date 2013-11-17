<?php

namespace Piwicms\Admin\NewsletterBundle\Controller;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class MailController implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        //Process picture upload.
        //$msg will be an instance of `PhpAmqpLib\Message\AMQPMessage` with the $msg->body being the data sent over RabbitMQ.
        var_dump($msg->body);
        return true;
    }
}
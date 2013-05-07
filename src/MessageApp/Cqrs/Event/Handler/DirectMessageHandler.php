<?php

namespace MessageApp\Cqrs\Event\Handler;


use LiteCQRS\DomainObjectChanged;

class DirectMessageHandler
{
    public function onSaveDirectMessage(DomainObjectChanged $event)
    {
        echo
              '====== Message Saved ======' . PHP_EOL
            . 'Subject: ' . $event->subject . PHP_EOL
            . 'Message: ' . $event->message . PHP_EOL
            . 'Author: ' . $event->author . PHP_EOL
            . 'Id: ' . $event->id . PHP_EOL
            . '===========================' . PHP_EOL
            . PHP_EOL
        ;
    }

    public function onSendDirectMessage(DomainObjectChanged $event)
    {
        echo
              '====== Message Sent ======' . PHP_EOL
            . 'Subject: ' . $event->subject . PHP_EOL
            . 'Message: ' . $event->message . PHP_EOL
            . 'Author: ' . $event->author . PHP_EOL
            . 'Receiver: ' . $event->receiver . PHP_EOL
            . '==========================' . PHP_EOL
            . PHP_EOL
        ;
    }
}
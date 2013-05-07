<?php
namespace MessageApp\Cqrs\Service;

use LiteCQRS\Bus\EventMessageBus;
use LiteCQRS\Bus\IdentityMap\SimpleIdentityMap;
use LiteCQRS\DomainObjectChanged;
use MessageApp\Cqrs\Event\Command\SaveDirectMessageCommand;
use MessageApp\Cqrs\Event\Command\SendDirectMessageCommand;
use MessageApp\Domain\DirectMessage;

class DirectMessageService
{
    private $map;
    private $directMessages = array();

    public function __construct(SimpleIdentityMap $map, EventMessageBus $eventBus)
    {
        $this->map      = $map;
        $this->eventBus = $eventBus;
    }

    public function saveDirectMessage(SaveDirectMessageCommand $command)
    {
        if (!isset($command->id)) {
            $this->directMessages[] = new DirectMessage();
            end($this->directMessages);
            $command->id = key($this->directMessages);
        }

        /** @var DirectMessage $directMessage */
        $directMessage = $this->directMessages[$command->id];
        $directMessage->setId($command->id);
        $directMessage->setSubject($command->subject);
        $directMessage->setMessage($command->message);
        $directMessage->setAuthor($command->author);

        $this->eventBus->publish(new DomainObjectChanged(
            'SaveDirectMessage',
            array(
                'id' => $directMessage->getId(),
                'subject' => $directMessage->getSubject(),
                'message' => $directMessage->getMessage(),
                'author' => $directMessage->getAuthor()
            )
        ));
    }

    public function sendDirectMessage(SendDirectMessageCommand $command)
    {
        /** @var DirectMessage $directMessage */
        $directMessage = $this->directMessages[$command->id];

        $this->eventBus->publish(new DomainObjectChanged(
            'SendDirectMessage',
            array(
                'id' => $directMessage->getId(),
                'subject' => $directMessage->getSubject(),
                'message' => $directMessage->getMessage(),
                'author' => $directMessage->getAuthor(),
                'receiver' => $command->receiver
            )
        ));
    }
}
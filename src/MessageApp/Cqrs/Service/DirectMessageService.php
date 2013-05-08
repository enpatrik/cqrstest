<?php
namespace MessageApp\Cqrs\Service;

use LiteCQRS\Bus\EventMessageBus;
use LiteCQRS\DomainObjectChanged;
use MessageApp\Cqrs\Command\SaveDirectMessageDraftCommand;
use MessageApp\Cqrs\Command\SendDirectMessageCommand;
use MessageApp\Domain\DirectMessage;

class DirectMessageService
{
    private $directMessageDrafts = array();

    public function __construct(EventMessageBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function saveDirectMessageDraft(SaveDirectMessageDraftCommand $command)
    {
        if (!isset($command->id)) {
            $this->directMessageDrafts[] = new DirectMessage();
            end($this->directMessageDrafts);
            $command->id = key($this->directMessageDrafts);
        }

        /** @var DirectMessage $directMessage */
        $directMessage = $this->directMessageDrafts[$command->id];
        $directMessage->setId($command->id);
        $directMessage->setSubject($command->subject);
        $directMessage->setMessage($command->message);
        $directMessage->setAuthor($command->author);

        $this->eventBus->publish(new DomainObjectChanged(
            'SaveDirectMessageDraft',
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
        $directMessage = $this->directMessageDrafts[$command->id];

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
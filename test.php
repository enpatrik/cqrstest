<?php

namespace MessageApp;

use LiteCQRS\Bus\DirectCommandBus;
use LiteCQRS\Bus\EventMessageHandlerFactory;
use LiteCQRS\Bus\InMemoryEventMessageBus;
use MessageApp\Cqrs\Event\Command\SaveDirectMessageDraftCommand;
use MessageApp\Cqrs\Event\Command\SendDirectMessageCommand;
use MessageApp\Cqrs\Event\Handler\DirectMessageHandler;
use MessageApp\Cqrs\Service\DirectMessageService;

require_once __DIR__ . "/vendor/autoload.php";

//@todo fix autoloader
require_once __DIR__ . '/src/MessageApp/Cqrs/Event/Command/SaveDirectMessageDraftCommand.php';
require_once __DIR__ . '/src/MessageApp/Cqrs/Event/Command/SendDirectMessageCommand.php';
require_once __DIR__ . '/src/MessageApp/Cqrs/Event/Handler/DirectMessageHandler.php';
require_once __DIR__ . '/src/MessageApp/Cqrs/Service/DirectMessageService.php';
require_once __DIR__ . '/src/MessageApp/Domain/DirectMessage.php';


// 1. Setup the Library with InMemory Handlers
$messageBus  = new InMemoryEventMessageBus();
$directMessageHandler = new DirectMessageHandler();
$messageBus->register($directMessageHandler);

$commandBus  = new DirectCommandBus(array(
    new EventMessageHandlerFactory($messageBus)
));

// 2. Register a command service and an event handler
$directMessageService = new DirectMessageService($messageBus);
$commandBus->register('MessageApp\Cqrs\Event\Command\SaveDirectMessageDraftCommand', $directMessageService);
$commandBus->register('MessageApp\Cqrs\Event\Command\SendDirectMessageCommand', $directMessageService);

// 3. Invoke command!
$commandBus->handle(new SaveDirectMessageDraftCommand(array(
    'subject' => 'Hello!',
    'message' => 'How are you?',
    'author' => 'patnil'
)));

$commandBus->handle(new SendDirectMessageCommand(array(
    'id' => 0,
    'receiver' => 'flohel'
)));

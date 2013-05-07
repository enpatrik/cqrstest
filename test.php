<?php

namespace MessageApp;

use LiteCQRS\Bus\DirectCommandBus;
use LiteCQRS\Bus\EventMessageHandlerFactory;
use LiteCQRS\Bus\IdentityMap\EventProviderQueue;
use LiteCQRS\Bus\IdentityMap\SimpleIdentityMap;
use LiteCQRS\Bus\InMemoryEventMessageBus;
use MessageApp\Cqrs\Event\Command\SaveDirectMessageCommand;
use MessageApp\Cqrs\Event\Command\SendDirectMessageCommand;
use MessageApp\Cqrs\Event\Handler\DirectMessageHandler;
use MessageApp\Cqrs\Service\DirectMessageService;

require_once __DIR__ . "/vendor/autoload.php";

//@todo fix autoloader
require_once __DIR__ . '/src/MessageApp/Cqrs/Event/Command/SaveDirectMessageCommand.php';
require_once __DIR__ . '/src/MessageApp/Cqrs/Event/Command/SendDirectMessageCommand.php';
require_once __DIR__ . '/src/MessageApp/Cqrs/Event/Handler/DirectMessageHandler.php';
require_once __DIR__ . '/src/MessageApp/Cqrs/Service/DirectMessageService.php';
require_once __DIR__ . '/src/MessageApp/Domain/DirectMessage.php';


// 1. Setup the Library with InMemory Handlers
$messageBus  = new InMemoryEventMessageBus();
$directMessageHandler = new DirectMessageHandler();
$messageBus->register($directMessageHandler);

$identityMap = new SimpleIdentityMap();
$queue = new EventProviderQueue($identityMap);
$commandBus  = new DirectCommandBus(array(
    new EventMessageHandlerFactory($messageBus, $queue)
));

// 2. Register a command service and an event handler
$directMessageService = new DirectMessageService($identityMap, $messageBus);
$commandBus->register('MessageApp\Cqrs\Event\Command\SaveDirectMessageCommand', $directMessageService);
$commandBus->register('MessageApp\Cqrs\Event\Command\SendDirectMessageCommand', $directMessageService);

// 3. Invoke command!
$commandBus->handle(new SaveDirectMessageCommand(array(
    'subject' => 'Hello!',
    'message' => 'How are you?',
    'author' => 'patnil'
)));

$commandBus->handle(new SendDirectMessageCommand(array(
    'id' => 0,
    'receiver' => 'flohel'
)));

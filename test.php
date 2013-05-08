<?php

namespace MessageApp;

use LiteCQRS\Bus\DirectCommandBus;
use LiteCQRS\Bus\EventMessageHandlerFactory;
use LiteCQRS\Bus\InMemoryEventMessageBus;
use LiteCQRS\Plugin\Monolog\MonologDebugLogger;
use MessageApp\Cqrs\Command\SaveDirectMessageDraftCommand;
use MessageApp\Cqrs\Command\SendDirectMessageCommand;
use MessageApp\Cqrs\Event\Handler\DirectMessageHandler;
use MessageApp\Cqrs\Service\DirectMessageService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . "/vendor/autoload.php";

//@todo fix autoloader
require_once __DIR__ . '/src/MessageApp/Cqrs/Command/SaveDirectMessageDraftCommand.php';
require_once __DIR__ . '/src/MessageApp/Cqrs/Command/SendDirectMessageCommand.php';
require_once __DIR__ . '/src/MessageApp/Cqrs/Event/Handler/DirectMessageHandler.php';
require_once __DIR__ . '/src/MessageApp/Cqrs/Service/DirectMessageService.php';
require_once __DIR__ . '/src/MessageApp/Domain/DirectMessage.php';


// Setup the Library with InMemory Handlers
$messageBus  = new InMemoryEventMessageBus();
$directMessageHandler = new DirectMessageHandler();
$messageBus->register($directMessageHandler);

// Monolog logger
$loggerProxyFactory = function($handler) {
    $logger = new Logger('Cqrs Command Logger');
    $logger->pushHandler(new StreamHandler('/var/tmp/mono.log', Logger::DEBUG));
    return new MonologDebugLogger($handler, $logger);
};

// Create command bus
$commandBus  = new DirectCommandBus(array(
    new EventMessageHandlerFactory($messageBus),
    $loggerProxyFactory
));

// Register a command service and an event handler
$directMessageService = new DirectMessageService($messageBus);
$commandBus->register('MessageApp\Cqrs\Command\SaveDirectMessageDraftCommand', $directMessageService);
$commandBus->register('MessageApp\Cqrs\Command\SendDirectMessageCommand', $directMessageService);

// Invoke commands
$commandBus->handle(new SaveDirectMessageDraftCommand(array(
    'subject' => 'Hello!',
    'message' => 'How are you?',
    'author' => 'patnil'
)));

$commandBus->handle(new SendDirectMessageCommand(array(
    'id' => 0,
    'receiver' => 'flohel'
)));

$commandBus->handle(new SendDirectMessageCommand(array(
    'id' => 0,
    'receiver' => 'patler'
)));

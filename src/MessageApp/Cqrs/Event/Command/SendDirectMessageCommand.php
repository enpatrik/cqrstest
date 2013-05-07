<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vsdev
 * Date: 5/7/13
 * Time: 3:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace MessageApp\Cqrs\Event\Command;


use LiteCQRS\DefaultCommand;

class SendDirectMessageCommand extends DefaultCommand
{
    /** @var int */
    public $id;
    /** @var string */
    public $receiver;
}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vsdev
 * Date: 5/7/13
 * Time: 4:04 PM
 * To change this template use File | Settings | File Templates.
 */

namespace MessageApp\Cqrs\Event\Command;


use LiteCQRS\DefaultCommand;

class SaveDirectMessageCommand extends DefaultCommand
{
    /** @var int */
    public $id;
    /** @var string */
    public $subject;
    /** @var string */
    public $message;
    /** @var string */
    public $author;
}
<?php

namespace Datto\JsonRpc\Logged;

use Datto\JsonRpc\Simple;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\TestHandler;
use Monolog\Logger;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    public function testLoggedServerWithReply()
    {
        $handler = new TestHandler();

        $server = new Server(
            new Simple\Evaluator(),
            new Logger('API', array($handler))
        );

        $server->reply('{"jsonrpc": "2.0", "method": "math/subtract", "params": [6, 2], "id": 1}');

        $this->assertTrue($handler->hasRecordThatContains('Message received: {"jsonrpc": "2.0", "method": "math/subtract", "params": [6, 2], "id": 1}', Logger::INFO));
        $this->assertTrue($handler->hasRecordThatContains('Sending reply: {"jsonrpc":"2.0","id":1,"result":4}', Logger::INFO));
    }

    public function testLoggedServerWithoutReply()
    {
        $handler = new TestHandler();

        $server = new Server(
            new Simple\Evaluator(),
            new Logger('API', array($handler)),
            Logger::DEBUG
        );

        $server->reply('{"jsonrpc": "2.0", "method": "math/dontWorryBeHappy"}');

        $this->assertTrue($handler->hasRecordThatContains('Message received: {"jsonrpc": "2.0", "method": "math/dontWorryBeHappy"}', Logger::DEBUG));
        $this->assertTrue($handler->hasRecordThatContains('Completed notification. No reply.', Logger::DEBUG));
    }

    public function testLoggedServerWithError()
    {
        $handler = new TestHandler();

        $server = new Server(
            new Simple\Evaluator(),
            new Logger('API', array($handler)),
            Logger::WARNING
        );

        $server->reply('{"jsonrpc": "2.0", "method": "math/doesNotExist", "id": 123}');

        $this->assertTrue($handler->hasRecordThatContains('Message received: {"jsonrpc": "2.0", "method": "math/doesNotExist", "id": 123}', Logger::WARNING));
        $this->assertTrue($handler->hasRecordThatContains('Sending reply: {"jsonrpc":"2.0","id":123,"error":{"code":-32601,"message":"Method not found"}}', Logger::WARNING));
    }
}

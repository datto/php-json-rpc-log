<?php

namespace Datto\JsonRpc\Logged;

use Datto\JsonRpc;
use Psr\Log\LoggerInterface;

/**
 * Extended JSON-RPC server class to additionally log
 *
 * @author Philipp Heckel <ph@datto.com>, Matt Coleman <matt@datto.com>
 */
class Server extends JsonRpc\Server
{
    /** @var \Psr\Log\LoggerInterface Logger */
    private $logger;

    /** @var mixed Log level to use */
    private $level;

    /**
     * Creates an instance of the JSON RPC server
     *
     * @param JsonRpc\Evaluator $evaluator Underlying evaluator to be used
     * @param LoggerInterface $logger Logger to log requests to
     * @param mixed $level Psr Log level
     */
    public function __construct(JsonRpc\Evaluator $evaluator, LoggerInterface $logger, $level = 'INFO')
    {
        parent::__construct($evaluator);

        $this->logger = $logger;
        $this->level = $level;
    }

    /**
     * Reads the JSON request/payload from the POST request input and
     * echoes the response (if any).
     *
     * The actual processing is done in JsonRpc\Server's reply() method.
     *
     * @param string $message JSON-RPC request message string
     * @return null|string JSON-RPC response message string (or null if the request was a notification)
     */
    public function reply($message)
    {
        $this->logger->log($this->level, 'Message received: ' . str_replace("\n", ' ', $message));

        $reply = parent::reply($message);

        if ($reply !== null) {
            $this->logger->log($this->level, 'Sending reply: ' . str_replace("\n", ' ', $reply));
            return $reply;
        } else {
            $this->logger->log($this->level, 'Completed notification. No reply.');
            return null;
        }
    }
}

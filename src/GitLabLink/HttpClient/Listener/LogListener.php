<?php namespace GitLabLink\HttpClient\Listener;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;

class LogListener implements ListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function preSend(RequestInterface $request)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function postSend(RequestInterface $request, MessageInterface $response)
    {
        echo "===== REQUEST =====\n";
        echo sprintf("=== Method: %s %s\n", $request->getMethod(), $request->getResource());
        echo "=== Headers:" . print_r($request->getHeaders(), true) . "\n";
        echo "=== Content:" . print_r($request->getContent(), true) . "\n";

        echo "===== RESPONSE =====\n";
        echo "=== Headers:" . print_r($response->getHeaders(), true) . "\n";
        echo "=== Content:" . print_r($response->getContent(), true) . "\n";

    }
}

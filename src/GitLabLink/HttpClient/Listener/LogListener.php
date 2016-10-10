<?php namespace GitLabLink\HttpClient\Listener;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;
use Gitlab\HttpClient\Message\FormRequest;

class LogListener implements ListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function preSend(RequestInterface $request)
    {
        // json encode if POST
        if ($request->getMethod() == RequestInterface::METHOD_POST) {
            //echo "Adding content-type Header\n";
            //$request->addHeader("Content-Type: application/x-www-form-urlencoded");
        }
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

        //var_dump($response);
    }
}

<?php

namespace App\EventSubscriber;

/* 
    Subscriber: Listening for any error response and logged the error
*/

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ErrorResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(LoggerInterface $logger){
        $this->logger = $logger;
    }

    public function onResponse(ResponseEvent $event){
        $response = $event->getResponse();
        $request = $event->getRequest();

        $ip = $request->getClientip();

        $content = $response->getContent();

        $content = json_decode($content, true);

        if (is_array($content) && !empty($content['error'])){
            $this->logger->info('Unauthorized API request made by ' .$ip.'. Status Code: ' . $response->getStatusCode());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE=>'onResponse'
        ];
    }
}

?>
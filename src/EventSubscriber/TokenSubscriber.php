<?php
    namespace App\EventSubscriber;

    /*
        Subscriber: Listening to the request and making sure that it has valid token
    */

    use App\Controller\ApiController;
    use App\Controller\TokenAuthenticatedController;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpKernel\Event\ControllerEvent;
    use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
    use Symfony\Component\HttpKernel\KernelEvents;

    class TokenSubscriber implements EventSubscriberInterface{
        
        private $tokens;

        public function __construct()
        {
            $this->tokens = [
                'user1' => 'token1',
                'user2' => 'token2'
            ];
        }
        public function beforeController(ControllerEvent $event)
        {
            $controller = $event->getController();
            /*
                Because we're using API controller to specify 
                which controller we would like to hook in this token subscriber to,
                it now won't work for any other kind of controller.
                So if we want to implement our token authentication into 
                more than one controller, 
                we'd have to chain a list of if statements into here, 
                which is obviously not a very good idea. 
                Instead, what we should do is use an interface. 
                
                Implementing instanceof TokenAuthenticatedController instead of ApiController.
            */
            if (is_array($controller) && $controller[0] instanceof TokenAuthenticatedController){
                $token = $event->getRequest()->query->get('token');
                if (!in_array($token, $this->tokens)){
                    throw new AccessDeniedHttpException('This needs a valid token');
                }
            }
        }
        public static function getSubscribedEvents()
        {
            return[
                KernelEvents::CONTROLLER=> 'beforeController'
            ];
        }
    }

?>
<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Class UserLocaleSubscriber
 * @package App\EventSubscriber
 */
class UserLocaleSubscriber implements EventSubscriberInterface
{
    /** @var SessionInterface */
    private $session;

    /**
     * UserLocaleSubscriber constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (null !== $user->getLocale()) {
            $this->session->set('_locale', $user->getLocale());
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            SecurityEvents::INTERACTIVE_LOGIN => array(array('onInteractiveLogin', 15)),
        );
    }
}
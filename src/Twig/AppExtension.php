<?php

namespace App\Twig;

use App\Entity\User;
use App\Navbar\NavbarBuilder;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AppExtension extends \Twig_Extension
{
    /** @var RequestStack $requestStack */
    private $requestStack;

    /** @var Packages $packages */
    private $packages;

    /** @var \Twig_Environment $twigEnvironment */
    private $twigEnvironment;

    /** @var NavbarBuilder $navBuilder */
    private $navBuilder;

    /** @var TokenStorageInterface tokenStorage */
    private $tokenStorage;

    /** @var User $user */
    private $user;

    public function __construct(
        RequestStack $requestStack,
        Packages $packages,
        \Twig_Environment $twigEnvironment,
        NavbarBuilder $navBuilder,
        TokenStorageInterface $tokenStorage
    ) {
        $this->requestStack = $requestStack;
        $this->packages = $packages;
        $this->twigEnvironment = $twigEnvironment;
        $this->navBuilder = $navBuilder;
        $this->tokenStorage = $tokenStorage;
        $token = $this->tokenStorage->getToken();

        if ($token && $token->getUser() instanceof User) {
            $this->user = $token->getUser();
        }
    }

    public function getName()
    {
        return 'app_extension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getSupportedLocales', array($this, 'getSupportedLocales')),
            new \Twig_SimpleFunction('renderNavbar', array($this, 'renderNavbar'), ['is_safe' => ['html' => true]]),
            new \Twig_SimpleFunction('ppboxRedirect', array($this, 'ppboxRedirect')),
            new \Twig_SimpleFunction('ppboxConfirm', array($this, 'ppboxConfirm')),
            new \Twig_SimpleFunction('ppboxAlert', array($this, 'ppboxAlert')),
        );
    }

    public function renderNavbar()
    {
        return $this->twigEnvironment->render(
            'navbar.html.twig',
            [
                'navbar' => $this->navBuilder->createNavbar(),
                'user' => $this->user,
            ]
        );
    }

    public function ppboxRedirect($url)
    {
        return 'PPbox.redirect(\'' . $url . '\');';
    }

    public function ppboxAlert($id, $title, $text, $theme, $width, $buttons1, $buttons2 = [])
    {
        $id = json_encode($id);
        $title = json_encode($title);
        $text = json_encode($text);
        $theme = json_encode($theme);
        $width = json_encode($width);
        $buttons1 = json_encode($buttons1);
        $buttons2 = json_encode($buttons2);

        return 'PPbox.alert(' . $id . ', ' . $title . ', ' . $text . ', ' . $theme . ', ' . $width . ', ' . $buttons1 . ', ' . $buttons2 . ');';
    }

    public function ppboxConfirm($id, $title, $text, $theme, $width, $buttons1, $buttons2 = [])
    {
        $id = json_encode($id);
        $title = json_encode($title);
        $text = json_encode($text);
        $theme = json_encode($theme);
        $width = json_encode($width);
        $buttons1 = json_encode($buttons1);
        $buttons2 = json_encode($buttons2);

        return 'PPbox.confirm(' . $id . ', ' . $title . ', ' . $text . ', ' . $theme . ', ' . $width . ', ' . $buttons1 . ', ' . $buttons2 . ');';
    }

    public static function getSupportedLocales()
    {
        return [
            'fr' => 'fr',
            'en' => 'en',
        ];
    }
}

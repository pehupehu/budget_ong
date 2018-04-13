<?php

namespace App\Twig;

use App\Navbar\NavbarBuilder;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\RequestStack;

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

    /** @var int */
    private static $ppbox_dialog = 0;

    public function __construct(
        RequestStack $requestStack,
        Packages $packages,
        \Twig_Environment $twigEnvironment,
        NavbarBuilder $navBuilder
    ) {
        $this->requestStack = $requestStack;
        $this->packages = $packages;
        $this->twigEnvironment = $twigEnvironment;
        $this->navBuilder = $navBuilder;
    }

    public function getName()
    {
        return 'app_extension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('renderNavbar', array($this, 'renderNavbar'), ['is_safe' => ['html' => true]]),
            new \Twig_SimpleFunction('renderSidebarAdmin', array($this, 'renderSidebarAdmin'), ['is_safe' => ['html' => true]]),
            new \Twig_SimpleFunction('ppboxRedirect', array($this, 'ppboxRedirect')),
            new \Twig_SimpleFunction('ppboxConfirm', array($this, 'ppboxConfirm')),
        );
    }

    public function renderNavbar()
    {
        return $this->twigEnvironment->render(
            'navbar.html.twig',
            ['navbar' => $this->navBuilder->createNavbar()]
        );
    }

    public function renderSidebarAdmin()
    {
        return $this->twigEnvironment->render(
            'sidebar_admin.html.twig',
            ['navbar' => $this->navBuilder->createSidebarAdmin()]
        );
    }

    public function ppboxRedirect($url)
    {
        return 'PPbox.redirect(\'' . $url . '\');';
    }

    public function ppboxConfirm($title, $text, $theme, $width, $buttons1, $buttons2 = [])
    {
        $id = json_encode(++self::$ppbox_dialog);
        $title = json_encode($title);
        $text = json_encode($text);
        $theme = json_encode($theme);
        $width = json_encode($width);
        $buttons1 = json_encode($buttons1);
        $buttons2 = json_encode($buttons2);

        return 'PPbox.alert(' . $id . ', ' . $title . ', ' . $text . ', ' . $theme . ', ' . $width . ', ' . $buttons1 . ', ' . $buttons2 . ');';
    }
}

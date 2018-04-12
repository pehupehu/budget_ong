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
        return 'ppbox.redirect(\'' . $url . '\');';
    }
}

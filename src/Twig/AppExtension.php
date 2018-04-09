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
            new \Twig_SimpleFunction('renderNavbarTop', array($this, 'renderNavbarTop'), ['is_safe' => ['html' => true]])
        );
    }

    public function renderNavbarTop()
    {
        return $this->twigEnvironment->render(
            'navbar.html.twig',
            ['navbar' => $this->navBuilder->createNavbarTop()]
        );
    }
}

<?php

namespace App\Navbar;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

final class NavbarBuilder
{
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        RequestStack $requestStack,
        TranslatorInterface $translator
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function createNavbarTop()
    {
//        <li class="nav-item active">
//            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
//        </li>
//            <li class="nav-item">
//                <a class="nav-link" href="{{ path('admin') }}">Admin</a>
//            </li>
        $navbar = [
            [
                'name' => 'home',
                'link' => '/',
                'is_active' => false,
            ],
            [
                'name' => 'admin',
                'link' => '/admin',
                'is_active' => true,
            ],
        ];

        if (substr($this->urlGenerator->generate('admin'), 0, strlen($this->urlGenerator->generate('admin'))) === $this->requestStack->getCurrentRequest()->getRequestUri()) {

        }
    }
}
<?php

namespace App\Navbar;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class NavbarBuilder
 * @package App\Navbar
 */
final class NavbarBuilder
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var RequestStack */
    private $requestStack;

    /** @var TranslatorInterface */
    private $translator;

    /** @var LoggerInterface */
    private $logger;

    /**
     * NavbarBuilder constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param RequestStack $requestStack
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        RequestStack $requestStack,
        TranslatorInterface $translator,
        LoggerInterface $logger
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->logger = $logger;
    }

    private function _processNavbar($items)
    {
        $navbar = new Navbar();
        foreach ($items as $item) {
            $navbarItem = new NavbarItem($item['title'], $item['path']);

            if (isset($item['icon'])) {
                $navbarItem->setIcon($item['icon']);
            }

            try {
                $path = $this->urlGenerator->generate($item['path']);
                $is_active = substr($path, 0, strlen($path)) === $this->requestStack->getCurrentRequest()->getRequestUri();
                $navbarItem->setIsActive($is_active);
                $navbar->add($navbarItem);

                $this->logger->debug('Navbar item : ' . $navbarItem);
            } catch (\Exception $e) {
                $this->logger->error('Navbar item : ' . $navbarItem . ' : ' . $e->getMessage());
            }
        }

        return $navbar;
    }

    /**
     * @return Navbar
     */
    public function createNavbar()
    {
        // TODO store into a config file
        $items = [
            [
                'title' => 'navbar.home',
                'path' => 'home',
                'credentials' => 'ROLE_USER',
            ],
            [
                'title' => 'navbar.admin',
                'path' => 'admin',
                'credentials' => 'ROLE_ADMIN',
            ],
        ];

        return $this->_processNavbar($items);
    }

    public function createSidebarAdmin()
    {
        // TODO store into a config file
        $items = [
            [
                'title' => 'sidebar.admin-user',
                'path' => 'admin_user',
                'icon' => 'oi oi-person',
                'credentials' => 'ROLE_ADMIN',
            ],
            [
                'title' => 'sidebar.admin-reports',
                'path' => 'admin_reports',
                'icon' => 'oi oi-graph',
                'credentials' => 'ROLE_ADMIN',
            ],
        ];

        return $this->_processNavbar($items);
    }
}
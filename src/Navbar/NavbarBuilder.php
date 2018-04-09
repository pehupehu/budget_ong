<?php

namespace App\Navbar;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
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

            // TODO credentials
//            if (isset($item['credentials'])) {
//                continue;
//            }

            if (isset($item['icon'])) {
                $navbarItem->setIcon($item['icon']);
            }

            try {
                $path = $this->urlGenerator->generate($item['path']);
                $is_active = substr($this->requestStack->getCurrentRequest()->getRequestUri(), 0, strlen($path)) === $path;
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
                'title' => 'navbar.admin',
                'path' => 'admin',
                'credentials' => User::ROLE_ADMIN,
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
                'credentials' => User::ROLE_ADMIN,
            ],
            [
                'title' => 'sidebar.admin-reports',
                'path' => 'admin_reports',
                'icon' => 'oi oi-graph',
                'credentials' => User::ROLE_ADMIN,
            ],
        ];

        return $this->_processNavbar($items);
    }
}
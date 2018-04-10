<?php

namespace App\Navbar;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Yaml\Yaml;

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

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /**
     * NavbarBuilder constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param RequestStack $requestStack
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        RequestStack $requestStack,
        TranslatorInterface $translator,
        LoggerInterface $logger,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->logger = $logger;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param $setup
     * @return Navbar
     */
    private function _processNavbar($setup)
    {
        $navbar = new Navbar();
        foreach ($setup as $translation_key => $conf) {
            $navbarItem = new NavbarItem($translation_key, $conf['route']);

            if (isset($conf['roles'])) {
                if (!$this->authorizationChecker->isGranted($conf['roles'])) {
                    continue;
                }
            }

            if (isset($conf['icon'])) {
                $navbarItem->setIcon($conf['icon']);
            }

            try {
                $path = $this->urlGenerator->generate($conf['route']);
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
        $conf = Yaml::parseFile(__DIR__ . '/../../config/navbar.yaml');

        return $this->_processNavbar($conf);
    }

    /**
     * @return Navbar
     */
    public function createSidebarAdmin()
    {
        $conf = Yaml::parseFile(__DIR__ . '/../../config/sidebar_admin.yaml');

        return $this->_processNavbar($conf);
    }
}
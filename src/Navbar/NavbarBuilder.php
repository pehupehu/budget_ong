<?php

namespace App\Navbar;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
    private function _processNavbar($setup, $translation_key_prefix)
    {
        $navbar = new Navbar();
        
        $current_module = $this->getCurrentModule();
        
        foreach ($setup as $module_name => $module) {
            if ($module_name !== 'all' && $current_module !== null && $current_module !== $module_name) {
                continue;
            }
            foreach ($module as $translation_key => $conf) {
                if (isset($conf['disabled']) && $conf['disabled']) {
                    continue;
                }

                $navbarItem = new NavbarItem($translation_key_prefix . '.' . $translation_key, $conf['route']);

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
        }

        return $navbar;
    }

    /**
     * @return Navbar
     */
    public function createNavbar()
    {
        $conf = Yaml::parseFile(__DIR__ . '/../../config/navbar.yaml');

        return $this->_processNavbar($conf, 'navbar');
    }

    /**
     * @return Navbar
     */
    public function createSidebar()
    {
        $conf = Yaml::parseFile(__DIR__ . '/../../config/sidebar.yaml');

        return $this->_processNavbar($conf, 'sidebar');
    }

    private function getCurrentModule()
    {
        $uri = explode('/', $this->requestStack->getCurrentRequest()->getRequestUri());
        $uri = array_filter($uri, function ($value) {
            return strlen(trim($value, '/')) > 0;
        });

        return array_shift($uri);
    }
}
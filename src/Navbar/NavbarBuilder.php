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

                if (isset($conf['children']) && $conf['children']) {
                    $dropdownMenu = new DropdownMenu($translation_key_prefix . '.' . $translation_key);

                    if (isset($conf['icon'])) {
                        $dropdownMenu->setIcon($conf['icon']);
                    }

                    $this->logger->debug('Dropdown menu start');
                    $menu_is_active = false; 
                    // Iterator children
                    foreach ($conf['children'] as $children_translation_key => $conf_children) {
                        $dropdownItem = new DropdownItem($translation_key_prefix . '.' . $children_translation_key, $conf_children['route']);

                        if (isset($conf_children['role'])) {
                            if (!$this->authorizationChecker->isGranted($conf_children['role'])) {
                                continue;
                            }
                        } elseif (isset($conf['role'])) {
                            if (!$this->authorizationChecker->isGranted($conf['role'])) {
                                continue;
                            }
                        } else {
                            continue;
                        }

                        if (isset($conf_children['icon'])) {
                            $dropdownItem->setIcon($conf_children['icon']);
                        }

                        try {
                            $path = $this->urlGenerator->generate($conf_children['route']);
                            $is_active = substr($this->requestStack->getCurrentRequest()->getRequestUri(), 0, strlen($path)) === $path;
                            $dropdownItem->setIsActive($is_active);
                            $dropdownMenu->add($dropdownItem);
                            if ($is_active) {
                                $menu_is_active = true;
                            }

                            $this->logger->debug('Dropdown item : ' . $dropdownItem);
                        } catch (\Exception $e) {
                            $this->logger->error('Dropdown item : ' . $dropdownItem . ' : ' . $e->getMessage());
                        }
                    }

                    if ($dropdownMenu->hasChildren()) {
                        $dropdownMenu->setIsActive($menu_is_active);
                        $this->logger->debug('Dropdown menu end : ' . $dropdownMenu);

                        $navbar->add($dropdownMenu);
                    }
                } else {
                    $navbarItem = new NavbarItem($translation_key_prefix . '.' . $translation_key, $conf['route']);

                    if (isset($conf['role'])) {
                        if (!$this->authorizationChecker->isGranted($conf['role'])) {
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

    private function getCurrentModule()
    {
        $uri = explode('/', $this->requestStack->getCurrentRequest()->getRequestUri());
        $uri = array_filter($uri, function ($value) {
            return strlen(trim($value, '/')) > 0;
        });

        return array_shift($uri);
    }
}
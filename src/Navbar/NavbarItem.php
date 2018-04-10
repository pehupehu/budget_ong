<?php

namespace App\Navbar;

/**
 * Class NavbarItem
 * @package App\Navbar
 */
class NavbarItem
{
    /** @var string */
    private $translation_key;

    /** @var string */
    private $route;

    /** @var string */
    private $icon;

    /** @var boolean */
    private $is_active;

    /**
     * NavbarItem constructor.
     * @param $translation_key
     * @param $route
     * @param string $icon
     * @param bool $is_active
     */
    public function __construct($translation_key, $route, $icon = '', $is_active = false)
    {
        $this->setTranslationKey($translation_key);
        $this->setRoute($route);
        $this->setIcon($icon);
        $this->setIsActive($is_active);
    }

    /**
     * @return string
     */
    public function getTranslationKey(): string
    {
        return $this->translation_key;
    }

    /**
     * @param string $translation_key
     * @return NavbarItem
     */
    public function setTranslationKey(string $translation_key): NavbarItem
    {
        $this->translation_key = $translation_key;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return NavbarItem
     */
    public function setRoute(string $route): NavbarItem
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return NavbarItem
     */
    public function setIcon(string $icon): NavbarItem
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * @param bool $is_active
     * @return NavbarItem
     */
    public function setIsActive(bool $is_active): NavbarItem
    {
        $this->is_active = $is_active;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode([
            'translation_key' => $this->getTranslationKey(),
            'route' => $this->getRoute(),
            'icon' => $this->getIcon(),
            'is_active' => $this->isActive()
        ]);
    }
}
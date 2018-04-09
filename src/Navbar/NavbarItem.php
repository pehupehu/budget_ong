<?php

namespace App\Navbar;

/**
 * Class NavbarItem
 * @package App\Navbar
 */
class NavbarItem
{
    /** @var string */
    private $title;

    /** @var string */
    private $path;

    /** @var string */
    private $icon;

    /** @var boolean */
    private $is_active;

    public function __construct($title, $path, $icon = null, $is_active = true)
    {
        $this->setTitle($title);
        $this->setPath($path);
        $this->setIcon($icon);
        $this->setIsActive($is_active);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return NavbarItem
     */
    public function setTitle(string $title): NavbarItem
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return NavbarItem
     */
    public function setPath(string $path): NavbarItem
    {
        $this->path = $path;
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
}
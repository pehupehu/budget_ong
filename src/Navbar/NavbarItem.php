<?php

namespace App\Navbar;

/**
 * Class NavbarItem
 * @package App\Navbar
 */
class NavbarItem extends GenericItem
{
    public function hasChildren()
    {
        return false;
    }
}
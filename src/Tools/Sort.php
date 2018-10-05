<?php

namespace App\Tools;

/**
 * Class Sort
 * @package App\Tools
 */
abstract class Sort
{
    /**
     * @param string $order
     * @param string $default
     * @return string
     */
    public static function checkOrder($order, $default = 'asc')
    {
        if (!in_array($order, ['asc', 'desc'])) {
            return $default;
        }

        return $order;
    }
}
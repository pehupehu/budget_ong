<?php

namespace App\Tools;

/**
 * Class Filters
 * @package App\Tools
 */
class Filters
{
    /**
     * @param $filters
     * @return int
     */
    public static function getNbActiveFilters($filters)
    {
        $nb_active_filters = 0;
        foreach ($filters as $key => $value) {
            if ($value !== null) {
                $nb_active_filters++;
            }
        }

        return $nb_active_filters;
    }
}
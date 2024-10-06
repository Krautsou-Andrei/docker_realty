<?php
function get_apartaments_on_floor($map_apartaments, $current_liter)
{
    $max_apartaments_on_liter = '';
    $floor_apartaments = [];
    $unique_apartaments = [];
    
    foreach ($map_apartaments[$current_liter]['floors'] as $floor) {
        if (intval($max_apartaments_on_liter) <= count($floor)) {
            $max_apartaments_on_liter = count($floor);
            $temp = array_column($floor, 'rooms');

            foreach ($unique_apartaments as $unique) {
                if (in_array($unique, $temp)) {
                    $temp =  array_diff($temp, [$unique]);
                }
            }

            $unique_apartaments = array_merge($temp, $unique_apartaments);
        }
    }

    foreach ($unique_apartaments as $index => $a) {
        $unique_apartaments[$index] =  ['rooms' => $a];
    }

    $floor_apartaments = array_values($unique_apartaments);

    return $floor_apartaments;
}

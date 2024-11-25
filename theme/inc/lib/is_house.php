<?php
function is_house($name)
{
    return  strpos(mb_strtolower($name, 'UTF-8'), 'коттедж') !== false || mb_strpos($name, 'Дома ', 0, 'UTF-8') !== false;
};

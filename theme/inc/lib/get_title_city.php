<?php
require_once get_template_directory() . '/inc/lib/declension_of_words/to_prepositional_pred.php';

function get_title_city($str)
{
    return 'в ' . to_prepositional_pred($str);
}

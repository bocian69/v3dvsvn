<?php

function smarty_modifier_polskie_na_url($string)
{
	$a = array( 'Ę', 'Ó', 'Ą', 'Ś', 'Ł', 'Ż', 'Ź', 'Ć', 'Ń', 'ę', 'ó', 'ą',
                'ś', 'ł', 'ż', 'ź', 'ć', 'ń' );
	$b = array( 'E', 'O', 'A', 'S', 'L', 'Z', 'Z', 'C', 'N', 'e', 'o', 'a',
            's', 'l', 'z', 'z', 'c', 'n' );
	$string = str_replace( $a, $b, $string );
    $string = preg_replace( '#[^a-z0-9]#is', ' ', $string );
    $string = trim( $string );
    $string = preg_replace( '#\s{2,}#', ' ', $string );
    $string = str_replace( ' ', '-', $string );
    return $string;
}

?>

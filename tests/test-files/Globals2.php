
<?php
$a = 1;
$b = 2;

function Sum()
{
	$zaloha = 'b';
    $GLOBALS["b"] = $GLOBALS['a'] + $GLOBALS['b'];
} 

Sum();
echo $b;
?>

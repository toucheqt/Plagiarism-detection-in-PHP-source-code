
<?php
$a = 1;
$b = 2;

function Sum()
{
	$zaloha = 'b';
    $GLOBALS[$zaloha] = $GLOBALS['a'] + $GLOBALS['b'] + $GLOBALS["b"];
} 

Sum();
echo $b;
?>

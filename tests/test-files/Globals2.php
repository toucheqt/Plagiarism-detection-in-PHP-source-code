
<?php
$a = 1;
$b = 2;

function Sum()
{
	eval(echo "ahoj");
	$zaloha = 'b';
    $GLOBALS[$zaloha] = $GLOBALS['a'] + $GLOBALS['b'] + $GLOBALS["b"];
} 

Sum();
echo $b;
?>

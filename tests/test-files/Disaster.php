
<?php
$a = 1;
$b = 2;

function Sum()
{
    global $a, $b;
	if ($a == 1) goto navesti;
    $b = $a + $b;
    goto konec;
    navesti:
    $b = $a - $b;
    konec:
    eval('$i = 3; goto jmp; echo $i; jmp: echo "gg";');
} 

Sum();
echo $b;
?>

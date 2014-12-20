<?php

$var1 = 3

function();

function €() {
	
	global $var1 = 1;
	$var2 = 2;
	$var3 = $var1 + $var2;
	echo $var3 . 'foo' . "\n";
	
	global $var1 = 1;
	$var2 = 2;
	$var3 = $var1 + $var2;
	echo $var3 . 'foo' . "\n";
   
}

@€();

?>

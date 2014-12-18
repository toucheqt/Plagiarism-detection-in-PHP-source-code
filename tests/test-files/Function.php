<?php

$var1 = 3

function €() {
	
	global $var1 = 1;
	echo "foo\n";
	$var2 = 2;
	echo "foo\n";
	$var3 = $var1 + $var2;
	echo $var3 . 'foo' . "\n";
   
}

@€();

?>

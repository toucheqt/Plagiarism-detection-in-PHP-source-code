<?php

function main() {
    fscanf(STDIN, "%d %d %d", $a, $b, $c);
    $avg = ($a + $b + $c) / 3;
    echo "avg = " . $avg;
} 
main();
?>

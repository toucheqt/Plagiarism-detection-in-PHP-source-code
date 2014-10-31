<?php

require 'getTokens.php';

$tokenizer = new Tokenizer("./../Tests/Function.php");
if (!$tokenizer->getTokens()) echo "fail\n";

?>



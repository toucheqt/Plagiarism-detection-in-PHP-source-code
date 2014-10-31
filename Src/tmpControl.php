<?php

require 'getTokens.php';

$tokenizer = new Tokenizer("./../Tests/HelloWorld.php");
if (!$tokenizer->getTokens()) echo "fail\n";

?>


